<?php namespace BookStack\Orz;

use BookStack\Actions\Tag;
use BookStack\Entities\Book;
use BookStack\Entities\Entity;
use BookStack\Orz\Repos\Repository;
use BookStack\Actions\TagRepo;
use Illuminate\Support\Collection;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\DB;
use BookStack\Entities\SearchService;
use BookStack\Auth\Permissions\PermissionService;
use Illuminate\Support\Facades\Route;

class SpaceRepo extends Repository
{
    /**
     * @var PermissionService
     */
    protected $permissionService;
    
    /**
     * @var SearchService
     */
    protected $searchService;
    
    /**
     * @var TagRepo
     */
    protected $tagRepo;
    
    
    public function __construct(App $app, Collection $collection, PermissionService $permissionService, TagRepo $tagRepo, SearchService $searchService)
    {
        parent::__construct($app, $collection);
        $this->permissionService = $permissionService;
        $this->tagRepo = $tagRepo;
        $this->searchService = $searchService;
    }
    
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'BookStack\Orz\Space';
    }
    
    public function checkPrivateSpace()
    {
        $space = $this->model
            ->where('type',2)
            ->where(['created_by'=>user()->id])->first();
        if (!$space) {
            $space = $this->model->create([
                'name'=> trans('space.my_space'),
                'description'=> trans('space.my_space'),
            ]);
            $space->type = 2;
            $space->created_by = user()->id;
            $space->updated_by = user()->id;
            $space->save();
        }
        return $space;
    }
    
    /**
     * Create a new entity from request input.
     * @param array $input
     * @return Entity
     */
    public function createFromInput($input = [])
    {
        $space = $this->model->create($input);
        $space->created_by = user()->id;
        $space->updated_by = user()->id;
        $space->save();
        
        if (isset($input['tags'])) {
            $this->tagRepo->saveTagsToEntity($space, $input['tags']);
        }
        
        if (isset($input['users'])) {
            $this->saveUsersToSpace($space, $input['users']);
        }
        
        if (isset($input['books'])) {
            $this->saveBooksToSpace($space, $input['books']);
        }
        
        //$this->permissionService->buildJointPermissionsForSpace($space);
        $this->permissionService->buildJointPermissionsForEntity($space);
        $this->searchService->indexEntity($space);
        return $space;
    }
    
    public function updateFromInput(Space $space, $input = [])
    {
        $space->fill($input);
        $space->updated_by = user()->id;
        $space->save();
        
        if (isset($input['tags'])) {
            $this->tagRepo->saveTagsToEntity($space, $input['tags']);
        }
        
        if (isset($input['users'])) {
            $this->saveUsersToSpace($space, $input['users']);
        }
        
        if (isset($input['books'])) {
            $this->saveBooksToSpace($space, $input['books']);
        }
        
        //$this->permissionService->buildJointPermissionsForSpace($space);
        $this->permissionService->buildJointPermissionsForEntity($space);
        $this->searchService->indexEntity($space);
        return $space;
    }
    
    public function storePrivateBook($books)
    {
        $space = $this->checkPrivateSpace();
        $this->saveBooksToSpace($space, $books);
        return $space;
    }
    
    //invite users
    public function saveUsersToSpace(Space $space, $users = [])
    {
        DB::table('space_user')->where(['space_id' => $space->id])->delete();
        //清除用户未处理的消息
        DB::table('messages')->whereIn('to', $users)->where('status',0)->delete();
        $all = $messages = [];
        foreach ($users as $key => $value) {
            //omit self
            if ($value['user_id'] == user()->id)
                continue;
            $all[] = ['user_id' => $value['user_id'], 'space_id' => $space->id,'is_admin'=>isset($value['is_admin'])?1:0];
            $messages[] = [
              'type'=>'space_invite', 
              'from'=>user()->id, 
              'to'=>$value['user_id'], 
              'content_key'=>isset($value['is_admin'])?'message.add_as_admin':'message.add_as_normal', 
              'status'=>0, 
              'rel_id'=>$space->id . '|' . $value['user_id'], 
            ];
        }
        
        DB::table('space_user')->insert($all);
        DB::table('messages')->insert($messages);
    }
    //space select books
    public function saveBooksToSpace(Space $space, $books = [])
    {
        DB::table('space_book')->where(['space_id' => $space->id])->delete();
        
        $all = [];
        foreach ($books as $key => $value) 
            $all[] = [
                'book_id' => $value, 
                'space_id' => $space->id,
                'user_id' => user()->id,
            ];
        
        DB::table('space_book')->insert($all);
    }
    
    //book select space,will  be effect to [saveBooksToSpace]
    public function saveBookToSpace(Book $book, $space = [])
    {
        DB::table('space_book')->where(['book_id' => $book->id])->delete();
        
        $all = [];
        foreach ($space as $key => $value) 
            $all[] = [
                'book_id' => $book->id, 
                'space_id' => $value,
                'user_id' => user()->id,
                ];
        DB::table('space_book')->insert($all);
    }
    
    public function getUsersId(Space $space)
    {
        return DB::table('space_user')->where(['space_id' => $space->id])->pluck('user_id')->all();
    }
    
    public function getAdminsId(Space $space)
    {
        return DB::table('space_user')->where(['space_id' => $space->id])->where(['is_admin'=>1])->pluck('user_id')->all();
    }
    
    public function getBooksId(Space $space)
    {
        return DB::table('space_book')->where(['space_id' => $space->id])->pluck('book_id')->all();
    }
    
    public function getPrivateBooks()
    {
        $space = Space::where('created_by', user()->id)->where('type',2)->first();
        return DB::table('space_book')
            ->where(['space_id' => $space->id])
            ->pluck('book_id')
            ->all();
    }
    
    public function getSpaceIdByBook(Book $book)
    {
        return DB::table('space_book')->where(['book_id' => $book->id])->pluck('space_id')->all();
    }
    
    public function destroySpace(Space $space)
    {
        //delete users
        DB::table('space_user')->where(['space_id' => $space->id])->delete();
        //delete books
        DB::table('space_book')->where(['space_id' => $space->id])->delete();
        $space->delete();
    }
    
    public function recordReadHistory($space_id, $page_id)
    {
        $url = route('space.page', ['id' => $space_id, 'oid' => $page_id], false);
        DB::table('spacext')->updateOrInsert(['user_id' => user()->id, 'key' => 'current_read',], ['value' => $url]);
    }
    
    public function checkReadHistory()
    {
        $read = DB::table('spacext')->where('user_id', user()->id)->where('key', 'current_read')->first();
        return $read && $read->value ? $read->value : false;
    }


    //被邀请用户权限判断 role [viewer,admin]
    public function checkUserPermission(Space $space, $role)
    {
        $user_id = user()->id;
        $space_user = DB::table('space_user')
            ->where(['space_id' => $space->id])
            ->where('user_id', $user_id)
            ->where('status', 1)
            ->first();
        if (!$space_user)
            return false;
        if ($role=='viewer')
            return true;
        if ($role=='admin')
            return $space_user->is_admin>0;
    }

}
