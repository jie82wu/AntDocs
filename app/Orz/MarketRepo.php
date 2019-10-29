<?php namespace BookStack\Orz;

use BookStack\Actions\Tag;
use BookStack\Entities\Book;
use BookStack\Entities\Chapter;
use BookStack\Entities\Entity;
use BookStack\Entities\Page;
use BookStack\Orz\Repos\Repository;
use BookStack\Actions\TagRepo;
use Illuminate\Support\Collection;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\DB;
use BookStack\Entities\SearchService;
use BookStack\Auth\Permissions\PermissionService;

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
    
    function publish(Book $book, $market_data)
    {
        $book->status = 1;
        $book->save();
    
        DB::table('market')->updateOrInsert(
            [
                'book_id'   => $book->id
            ],
            [
                'category'   => $market_data['category'],
                'description'   => $market_data['description'],
                'price'   => $market_data['price'],
                'created_at'   => date('Y-m-d H:i:s'),
            ]
        );
    }
    
    function beginCopy($space, $book, $spaceRepo=null)
    {
        $user = user();
        DB::beginTransaction();
        try {
            //add copy count
            $market = $book->market;
            $market->copy_count +=1;
            $market->save();
            
            //deduct ant coin
            $user->ant_coin -= $market->price;
            $user->save();
            //coin log
            $this->logCoin($market->price);
            
            //copy book
            $new_book = $this->copyBook($space, $book);
            
            //add book to space
            if ($spaceRepo)
                $spaceRepo->saveBookToSpace($new_book, $space);
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            $this->showError($e);
        }
    }
    
    function copyBook($space, $book)
    {
        $bookAttr = $book->attributesToArray();
        unset($bookAttr['id']);
        $new_book = new Book($bookAttr);
        $new_book->created_by = $new_book->updated_by = user()->id;
        $new_book->space_id = $space->id;
        $new_book->slug = $this->slug($book->name);
        $new_book->status = 2;
        $new_book->copy_origin_id = $book->id;
        $new_book->copy_at = date('Y-m-d H:i:s');
        $new_book->save();
        //chapters
        foreach($book->chapters as $chapter) {
            $chapterAttr = $chapter->attributesToArray();
            unset($chapterAttr['id']);
            $new_chapter = new Chapter($chapterAttr);
            $new_chapter->book_id = $new_book->id;
            $new_chapter->slug = $this->slug($new_book->name);
            $new_chapter->created_by = $new_chapter->updated_by = user()->id;
            $new_chapter->space_id = $space->id;
            $new_chapter->save();
    
            //chapter-pages
            foreach($chapter->pages as $page) {
                $pageAttr = $page->attributesToArray();
                unset($pageAttr['id']);
                $new_page = new Page($pageAttr);
                $new_page->book_id = $new_book->id;
                $new_page->chapter_id = $new_chapter->id;
                $new_page->slug = $this->slug($page->name);
                $new_page->created_by = $new_page->updated_by = user()->id;
                $new_page->space_id = $space->id;
                $new_page->save();
            }
        }
        
        //book-pages
        foreach($book->directPages as $page) {
            $pageAttr = $page->attributesToArray();
            unset($pageAttr['id']);
            $new_page = new Page($pageAttr);
            $new_page->book_id = $new_book->id;
            $new_page->slug = $this->slug($page->name);
            $new_page->created_by = $new_page->updated_by = user()->id;
            $new_page->space_id = $space->id;
            $new_page->save();
        }
        
        return $new_book;
    }
    
    
}
