<?php namespace BookStack\Orz;

use BookStack\Actions\Tag;
use BookStack\Orz\Repos\Repository;
use BookStack\Actions\TagRepo;
use Illuminate\Support\Collection;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\DB;
use BookStack\Entities\SearchService;
use BookStack\Auth\Permissions\PermissionService;

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
    
    
    public function __construct(
        App $app, 
        Collection $collection,
        PermissionService $permissionService,
        TagRepo $tagRepo,
        SearchService $searchService
    ) {
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
        
        $this->permissionService->buildJointPermissionsForSpace($space);
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
    
        $this->permissionService->buildJointPermissionsForSpace($space);
        $this->searchService->indexEntity($space);
        return $space;
    }
    
    public function saveUsersToSpace(Space $space, $users = [])
    {
        DB::table('space_user')
            ->where(['space_id'=>$space->id])
            ->delete();
        
        $all = [];
        foreach ($users as $key => $value)
            $all[] = [
                'user_id' => $value,
                'space_id' => $space->id,
            ];
        
        DB::table('space_user')->insert($all);
    }
    
    public function saveBooksToSpace(Space $space, $books = [])
    {
        DB::table('space_book')
            ->where(['space_id'=>$space->id])
            ->whereNull('user_id')
            ->delete();
        
        $all = [];
        foreach ($books as $key => $value)
            $all[] = [
                'book_id' => $value,
                'space_id' => $space->id,
            ];
    
        DB::table('space_book')->insert($all);
    }
    
    public function getUsersId(Space $space)
    {
        return DB::table('space_user')
            ->where(['space_id'=>$space->id])
            ->pluck('user_id')
            ->all();
    }
    
    public function getBooksId(Space $space)
    {
        return DB::table('space_book')
            ->where(['space_id'=>$space->id])
            ->pluck('book_id')
            ->all();
    }
    
    public function destroySpace(Space $space)
    {
        //delete users
        DB::table('space_user')
            ->where(['space_id'=>$space->id])
            ->delete();
        //delete books
        DB::table('space_book')
            ->where(['space_id'=>$space->id])
            ->whereNull('user_id')
            ->delete();
        $space->delete();
    }
}
