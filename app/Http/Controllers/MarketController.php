<?php namespace BookStack\Http\Controllers;

use Activity;
use BookStack\Auth\UserRepo;
use BookStack\Entities\Book;
use BookStack\Entities\EntityContextManager;
use BookStack\Entities\Repos\EntityRepo;
use BookStack\Entities\ExportService;
use BookStack\Orz\Space;
use BookStack\Uploads\ImageRepo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Views;
use BookStack\Orz\SpaceRepo;
use BookStack\Orz\MarketRepo;

class MarketController extends Controller
{

    protected $entityRepo;
    protected $userRepo;
    protected $exportService;
    protected $entityContextManager;
    protected $imageRepo;
    protected $spaceRepo;
    protected $marketRepo;

    /**
     * BookController constructor.
     * @param EntityRepo $entityRepo
     * @param UserRepo $userRepo
     * @param ExportService $exportService
     * @param EntityContextManager $entityContextManager
     * @param ImageRepo $imageRepo
     */
    public function __construct(
        EntityRepo $entityRepo,
        UserRepo $userRepo,
        ExportService $exportService,
        EntityContextManager $entityContextManager,
        ImageRepo $imageRepo,
        SpaceRepo $spaceRepo,
        MarketRepo $marketRepo
    ) {
        $this->entityRepo = $entityRepo;
        $this->userRepo = $userRepo;
        $this->exportService = $exportService;
        $this->entityContextManager = $entityContextManager;
        $this->imageRepo = $imageRepo;
        $this->spaceRepo = $spaceRepo;
        $this->marketRepo = $marketRepo;
        parent::__construct();
    }
    
    /**
     * market.
     * @return Response
     */
    public function index(Request $request)
    {
        $view = setting()->getUser($this->currentUser, 'books_view_type', config('app.views.books'));
        $conditions = [];
        if ($request->has('term'))
            $conditions['like'] = $request->get('term');
        if ($request->has('category'))
            $conditions['category'] = $request->get('category');
        if ($request->has('sort')) {
            $conditions['sort'] = $request->get('sort');
        }
        $books = $this->entityRepo->getMarketBookPaginated(12, $conditions); 
        $categories = $this->marketRepo->getAllCategories();
        $allSpace = $this->spaceRepo->getAllSpace();
        $this->setPageTitle(trans('market.discovery'));
        return view('space.market.index', [
            'books' => $books,
            'view' => $view,
            'categories' => $categories,
            'allSpace' => $allSpace,
        ]);
    }
    
    /**
     * publish book.
     * @return Response
     */
    public function publish($slug, Request $request)
    {
        $book = $this->entityRepo->getBySlug('book', $slug);
        //if ($book->status != 0) 
        //    $this->marketRepo->showError('market.publish_error');
        
        //if not own, then check an not exists permission
        isOwnBook($book) || $this->checkOwnablePermission('book-publish', $book);

        $categories = $this->marketRepo->getAllCategories();
        $this->setPageTitle($book->getShortName());
        return view('space.market.publish', [
            'book' => $book,
            'model' => $book,
            'bookSel' => true,
            'space' => $book->space,
            'categories' => $categories,
         ]);
    }
    
    /**
     * put book into content-market
     * 
     * @param Request $request
     * @param $slug
     * 
     * @return response
     */
    public function store(Request $request, $slug)
    {
        $this->validate($request, [
            'category' => 'required|string|max:100',
            'description' => 'string|max:1000',
            'price' => 'required|integer|min:0',
        ]);
        
        $book = $this->entityRepo->getBySlug('book', $slug);
        //if ($book->status != 0)
        //    $this->marketRepo->showError('market.publish_error');
        
        $this->marketRepo->publish($book, $request->all());
    
        session()->flash('success', trans('market.publish_success'));
        return redirect($book->getSpaceUrl($book->space));
    }
    
    //confirm
    public function purchase(Request $request, $space_id, $book_id)
    {
        $book = $this->entityRepo->getById('book', $book_id);
        $space = $this->spaceRepo->find($space_id);
        return view('space.market.purchase-confirm', [
            'book' => $book,
            'space' => $space,
        ]);
    }
    
    //purchase copy book
    public function copyBook(Request $request, $space_id, $book_id)
    {
        $book = $this->entityRepo->getById('book', $book_id);
        $space = $this->spaceRepo->find($space_id);
        $user = user();
        if ( !$space || !$book || $book->status!=1 )
            $this->marketRepo->showError('market.purchase_resource_error','/market');
        if ( $user->ant_coin< $book->market->price )
            $this->marketRepo->showError('market.purchase_coin_error','/market');
        
        //begin copy
        $this->marketRepo->beginCopy($space, $book, $this->spaceRepo);
        
        session()->flash('success', trans('market.purchase_success'));
        return redirect('/market');
    }

}
