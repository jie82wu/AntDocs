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

class MarketRepo extends Repository
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
    
    //所有分类
    function getAllCategories()
    {
        return DB::table('category')->get();
    }
    
    //save categories
    function saveCategoriesByString(string $string) 
    {
        if (strlen($string) == 0)
            return false;
        
        $data = preg_split('/[^0-9a-zA-Z\x{4e00}-\x{9fa5}]/u', $string, 0, PREG_SPLIT_NO_EMPTY);
        
        if (empty($data))
            return false;
        
        DB::table('category')->truncate();
        $insert = [];
        foreach ($data as $value)
            $insert[] = ['name'=>$value, 'display_name'=>$value];
    
        DB::table('category')->insert($insert);
        return true;
    }
}
