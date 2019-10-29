<?php namespace BookStack\Orz;

use BookStack\Actions\Tag;
use BookStack\Entities\Book;
use BookStack\Entities\Entity;
use BookStack\Entities\Page;
use BookStack\Orz\Repos\Repository;
use BookStack\Actions\TagRepo;
use Illuminate\Support\Collection;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\DB;
use BookStack\Entities\SearchService;
use BookStack\Auth\Permissions\PermissionService;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Exceptions\HttpResponseException;

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
    
    
    public function __construct(App $app, Collection $collection, PermissionService $permissionService, TagRepo $tagRepo)
    {
        parent::__construct($app, $collection);
        $this->permissionService = $permissionService;
        $this->tagRepo = $tagRepo;
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
        
//        if (isset($input['users'])) {
//            $this->saveUsersToSpace($space, $input['users']);
//        }
        
        
        //$this->permissionService->buildJointPermissionsForSpace($space);
        $this->permissionService->buildJointPermissionsForEntity($space);
        //$this->searchService->indexEntity($space);
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
        
        /**
        if (isset($input['users'])) {
            $this->saveUsersToSpace($space, $input['users']);
        }
        
        if (isset($input['books'])) {
            $this->saveBooksToSpace($space, $input['books']);
        }
        */
        
        //$this->permissionService->buildJointPermissionsForSpace($space);
        $this->permissionService->buildJointPermissionsForEntity($space);
        //$this->searchService->indexEntity($space);
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
    
    //add user
    public function saveUserToSpace(Space $space, $user_id, $extra = [])
    {
        $user = ['user_id' => $user_id, 'space_id' => $space->id];
        $count = DB::table('space_user')->where($user)->count();
        if ($count > 0)
            return ;
        
        if ($extra)
            $user = array_merge($user, $extra);
        
        DB::table('space_user')->insert($user);
    
        if (user()->id == $user_id)
            return ;
        
        $message = [
            'type'=>'space_invite',
            'from'=>user()->id,
            'to'=>$user_id,
            'content_key'=>'message.add_as_normal',
            'status'=>0,
            'rel_id'=>$space->id . '|' . $user_id,            
        ];
        DB::table('messages')->insert($message);
    }
   
    //create book save to space 
    public function saveBookToSpace(Book $book, $space = null)
    {
        if (!$space)
            $space = getSpace();
        $all = [
            'book_id' => $book->id, 
            'space_id' => $space->id,
            'user_id' => user()->id,
            ];
        DB::table('space_book')->insert($all);
        return $space;
    }
    
    //获取空间邀请的用户
    public function getInvitedUsers(Space $space)
    {
        return $space->users;
    }
    
    public function getAdminsId(Space $space)
    {
        return DB::table('space_user')->where(['space_id' => $space->id])->where(['is_admin'=>1])->pluck('user_id')->all();
    }
    
    public function getBooksId(Space $space, $with_books = false)
    {
        $ids = DB::table('space_book')->where(['space_id' => $space->id])->pluck('book_id')->all();
        if (!$with_books) {
            return $ids;
        }
        return Book::whereIn('id',$ids)->get();
    }
    
    public function getPrivateBooks($single = true)
    {
        $space = Space::where('created_by', user()->id)->where('type',2)->first();
        if (!$space)
            return [];
        $c = DB::table('space_book')
            ->where(['space_id' => $space->id])
            ->pluck('book_id')
            ->all();
        if ($single)
            return $c;
        return Book::whereIn('id',$c)->get();
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
        //delete read history
        DB::table('spacext')->where(['space_id' => $space->id])->delete();
        
        $space->delete();
    }
    
    public function recordReadHistory($space_id, $page_id)
    {
        $url = route('space.page', ['id' => $space_id, 'oid' => $page_id], false);
        DB::table('spacext')->updateOrInsert(['user_id' => user()->id, 'key' => 'current_read',], ['value' => $url,'page_id'=>$page_id, 'space_id'=>$space_id]);
    }
    
    public function checkReadHistory()
    {
        $read = DB::table('spacext')
            ->where('user_id', user()->id)
            ->where('key', 'current_read')
            ->first();
        if ($read) {
            $page = Page::find($read->page_id);
            $space = Space::find($read->space_id);
            if ($space && $page && $read->value)
                return $read->value;
            else {
                DB::table('spacext')
                    ->where('user_id', user()->id)
                    ->where('key', 'current_read')
                    ->delete();
            }
        }
        return false;
    }


    //被邀请用户权限判断 role [viewer,admin]
    public function checkUserPermission(Space $space, $role='')
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
    
    public function checkIsAdmin(Space $space)
    {
        if (!isSpaceCreator($space)) {
            $this->showPermissionError();
        }
        return true;
    }
    
    public function showPermissionError()
    {
        if (request()->wantsJson()) {
            $response = response()->json(['error' => trans('errors.permissionJson')], 403);
        } else {
            $response = redirect()->back()->withInput();
            session()->flash('error', trans('errors.permission'));
        }
        throw new HttpResponseException($response);
    }
    
    public function checkSelfEntity(Entity $entity)
    {
        if ($entity->created_by != user()->id) {
            //$this->showPermissionError();
            return false;
        }
        return true;
    }
    
    public function copyDefaultRoles($space)
    {
        $roles = DB::table('roles')->where('space_id',0)->get();
        foreach ($roles as $key=>$role) {
            $insert_role = [
                'name' => $role->name,
                'display_name' => $role->display_name,
                'description' => $role->description,
                'system_name' => $role->system_name,
                'space_id' => $space->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $insert_role_id = DB::table('roles')->insertGetId($insert_role);
            
            $role_permissions = DB::table('permission_role')->where('role_id',$role->id)->get();
            $insert_new_permissions = [];
            foreach ($role_permissions as $per) {
                $insert_new_permissions[] = [
                    'permission_id' => $per->permission_id,
                    'role_id' => $insert_role_id,
                ];
            }
            if ($role->name=='admin')
                $insert_new_permissions[] = ['permission_id'=> 82, 'role_id'=>$insert_role_id];
            DB::table('permission_role')->insert($insert_new_permissions);
        }
    }
    
    //remove users from space
    public function removeUser(Space $space, $uid)
    {
        if (!is_array($uid))
            $uid = [$uid];
        DB::table('space_user')->where(['space_id' => $space->id])->whereIn('user_id', $uid)->delete();
        $role_ids = DB::table('roles')->where(['space_id' => $space->id])->pluck('id')->all();
        DB::table('role_user')->whereIn('user_id', $uid)->whereIn('role_id', $role_ids)->delete();
    }

    // users exit from space
    public function userExit(Space $space, $user)
    {
        $role = DB::table('roles')->where('name','admin')->where('space_id',$space->id)->first();
        $admin = DB::table('role_user')->where('role_id',$role->id)->pluck('user_id')->all();
        //管理员count
        $admin_count = count($admin);
        //creator是否存在
        $creator_exist = DB::table('space_user')
            ->where('space_id',$space->id)
            ->where('user_id',$space->created_by)
            ->count();
        //creator退出
        if ($space->created_by==$user->id) {
            if ($admin_count==0)
                return false;
        } else { //admin退出
            if (!$creator_exist && $admin_count <=1)
                return false;
        }
        
        $this->removeUser($space, $user->id);
        return true;

    }
    
    //判断user是否在空间中
    public function isUserInSpace($space,$user)
    {
        $user = ['user_id' => $user->id, 'space_id' => $space->id];
        $count = DB::table('space_user')->where($user)->count();
        return  $count > 0;
    }

    public function getAllSpaceId()
    {
        $user = user();
        $private_space = Space::where(['created_by' => $user->id])->where('type',2)->first();
        $ids = DB::table('space_user')->where('user_id', $user->id)->where('status',1)->pluck('space_id')->all();
        $ids[] = $private_space->id;
        return array_unique($ids);
    }
    
    //all space
    public function getAllSpace()
    {
        $user = user();
        $private_space = Space::where(['created_by' => $user->id])->where('type',2)->first();
        $ids = DB::table('space_user')->where('user_id', $user->id)->where('status',1)->pluck('space_id')->all();
        $share_space = Space::whereIn('id',$ids)->get();
        $share_space->prepend($private_space);
        return $share_space;
    }
}
