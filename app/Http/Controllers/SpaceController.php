<?php namespace BookStack\Http\Controllers;

use Activity;
use BookStack\Actions\TagRepo;
use BookStack\Auth\UserRepo;
use BookStack\Entities\Book;
use BookStack\Entities\EntityContextManager;
use BookStack\Entities\Repos\EntityRepo;
use BookStack\Entities\ExportService;
use BookStack\Uploads\ImageRepo;
use BookStack\Orz\Criteria\AllSpace;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Views;
use BookStack\Orz\SpaceRepo;
use BookStack\Orz\Space;
use BookStack\Entities\Repos\PageRepo;

class SpaceController extends Controller
{

    protected $entityRepo;
    protected $userRepo;
    protected $exportService;
    protected $entityContextManager;
    protected $imageRepo;
    protected $tagRepo;
    protected $pageRepo;

    /**
     * SpaceController constructor.
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
        TagRepo $tagRepo,
        PageRepo $pageRepo,
        SpaceRepo $spaceRepo
    ) {
        $this->entityRepo = $entityRepo;
        $this->userRepo = $userRepo;
        $this->exportService = $exportService;
        $this->entityContextManager = $entityContextManager;
        $this->imageRepo = $imageRepo;
        $this->tagRepo = $tagRepo;
        $this->spaceRepo = $spaceRepo;
        $this->pageRepo = $pageRepo;
        parent::__construct();
    }

    /**
     * list.
     * @return Response
     */
    public function index()
    {
        $r = $this->spaceRepo->checkReadHistory();
        if ($r)
            return redirect($r);
        $view = setting()->getUser($this->currentUser, 'books_view_type', config('app.views.books'));
        //用户所有空间的books
        $this->spaceRepo->pushCriteria(new AllSpace());
        $share = $this->spaceRepo->all();
        $books = collect();
        foreach ($share as $item) {
            $books->push($item->books);
        }
        $books = $books->collapse()->take(12);
        $this->setPageTitle(trans('space.space'));
        return view('space.index', [
            'books' => $books,
            'view' => $view,
        ]);
    }

    protected function getUserAndBookList(Request $request, $listDetails=[])
    {
        if (!$listDetails)
            $listDetails = [
                'order' => $request->get('order', 'asc'),
                'search' => $request->get('search', ''),
                'sort' => $request->get('sort', 'name'),
            ];
        $users = $this->userRepo->getAllUsersPaginatedAndSorted(20, $listDetails);
        $books = $this->entityRepo->getAllPaginated('book', 18, 'name', 'asc');
        return ['users'=>$users,'books'=>$books];
    }
    /**
     * Show the form for creating space.
     */
    public function create(Request $request)
    {
        $listDetails = [
            'order' => $request->get('order', 'asc'),
            'search' => $request->get('search', ''),
            'sort' => $request->get('sort', 'name'),
        ];
        $list = $this->getUserAndBookList($request, $listDetails);
        $this->setPageTitle(trans('space.create'));
        return view('space.create',[
            'users'=>$list['users'], 
            'books'=>$list['books'], 
            'listDetails' => $listDetails]);
    }

    /**
     * Store space
     */
    public function store(Request $request)
    {
        $this->checkPermission('space-create-all');
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'string|max:1000',
            'image' => $this->imageRepo->getImageValidationRules(),
        ]);

        $input = $request->all();
        $space = $this->spaceRepo->createFromInput($input);
        $this->updateActions($space, $request);
        Activity::add($space, 'space_create', $space->id);

        return redirect($space->getUrl());
    }
    
    /**
     * Store private book
     */
    public function storePrivateBook(Request $request)
    {
        $this->validate($request, [
            'books' => 'required',
            ]
        );
        $input = $request->all();        
        if (isset($input['books'])) {
            $space = $this->spaceRepo->storePrivateBook($input['books']);
            Activity::add($space, 'book_add_favorite', $space->id);
        }
        return redirect($space->getUrl());
    }
    
    /**
     * show space by id
     */
    public function showSpace(Request $request, $id)
    {
        $space = $this->spaceRepo->find($id);
        return view('space.show',[
            'space'=>$space,
            'spaceSel'=>true,
        ]);
    }

    /**
     * show my space
     */
    public function showMySpace(Request $request)
    {
        $view = setting()->getUser($this->currentUser, 'books_view_type', config('app.views.books'));
        $bookIds = $this->spaceRepo->getPrivateBooks();
        $books = collect();
        foreach ($bookIds as $id) {
            $book = $this->entityRepo->getById('book', $id);
            $books->push($book);
        }
        $allBooks = $this->entityRepo->getAllPaginated('book', 18, 'name', 'asc');
        return view('space.show-my-space',[
            'books'=>$books,
            'view'=>$view,
            'allBooks'=>$allBooks,
        ]);
    }

    public function showSpaceBook(Request $request, $id, $oid)
    {
        $space = $this->spaceRepo->find($id);
        $book = $this->entityRepo->getById('book', $oid);
        $this->checkOwnablePermission('book-view', $book);
    
        $bookChildren = $this->entityRepo->getBookChildren($book);    
        Views::add($book);  
        $this->setPageTitle($book->getShortName());
        return view('space.book-show', [
            'space' => $space,
            'book' => $book,
            'current' => $book,
            'bookChildren' => $bookChildren,
            'bookSel' => true,
            'activity' => Activity::entityActivity($book, 20, 1)
        ]);
    }
    
    public function showSpaceChapter(Request $request, $id, $oid)
    {
        $space = $this->spaceRepo->find($id);
        $chapter = $this->entityRepo->getById('chapter', $oid);
        $this->checkOwnablePermission('chapter-view', $chapter);
        $sidebarTree = $this->entityRepo->getBookChildren($chapter->book);
        Views::add($chapter);
        $this->setPageTitle($chapter->getShortName());
        $pages = $this->entityRepo->getChapterChildren($chapter);
        return view('space.chapter-show', [
            'chapterSel' => true,
            'space' => $space,
            'book' => $chapter->book,
            'chapter' => $chapter,
            'current' => $chapter,
            'sidebarTree' => $sidebarTree,
            'pages' => $pages
        ]);
    }
    
    public function showSpacePage(Request $request, $id, $oid)
    {
        $space = $this->spaceRepo->find($id);
        $page = $this->entityRepo->getById('page', $oid);
        $this->checkOwnablePermission('page-view', $page);
    
        $page->html = $this->pageRepo->renderPage($page);
        $sidebarTree = $this->pageRepo->getBookChildren($page->book);
        $pageNav = $this->pageRepo->getPageNav($page->html);
    
        // check if the comment's are enabled
        $commentsEnabled = !setting('app-disable-comments');
        if ($commentsEnabled) {
            $page->load(['comments.createdBy']);
        }
        
        //record read history
        $this->spaceRepo->recordReadHistory($id, $oid);
    
        Views::add($page);
        $this->setPageTitle($page->getShortName());
        return view('space.page-show', [
            'space'=>$space,
            'pageSel'=>true,
            'page' => $page,'book' => $page->book,
            'current' => $page,
            'sidebarTree' => $sidebarTree,
            'commentsEnabled' => $commentsEnabled,
            'pageNav' => $pageNav
        ]);
    }
    

    /**
     * Display the specified book.
     * @param $slug
     * @param Request $request
     * @return Response
     * @throws \BookStack\Exceptions\NotFoundException
     */
    public function show($slug, Request $request)
    {
        $book = $this->entityRepo->getBySlug('book', $slug);
        $this->checkOwnablePermission('book-view', $book);

        $bookChildren = $this->entityRepo->getBookChildren($book);

        Views::add($book);
        if ($request->has('shelf')) {
            $this->entityContextManager->setShelfContext(intval($request->get('shelf')));
        }

        $this->setPageTitle($book->getShortName());
        return view('books.show', [
            'book' => $book,
            'current' => $book,
            'bookChildren' => $bookChildren,
            'activity' => Activity::entityActivity($book, 20, 1)
        ]);
    }

    /**
     * Show the form for editing the specified book.
     * @param $slug
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $list = $this->getUserAndBookList($request);
        $this->setPageTitle(trans('space.create'));
        $space = $this->spaceRepo->find($id);
        $user_ids = $this->spaceRepo->getUsersId($space);
        $admin_ids = $this->spaceRepo->getAdminsId($space);
        $book_ids = $this->spaceRepo->getBooksId($space);
        $this->checkOwnablePermission('space-update', $space);
        $this->setPageTitle(trans('space.books_edit_named', ['spaceName'=>$space->name]));
        return view('space.edit', [
            'space' => $space, 
            'current' => $space,
            'uids' => $user_ids,
            'aids' => $admin_ids,
            'bids' => $book_ids,
            'users'=>$list['users'],
            'books'=>$list['books']
            ]);
    }

    /**
     * Update the specified book in storage.
     * @param Request $request
     * @param          $slug
     * @return Response
     * @throws \BookStack\Exceptions\ImageUploadException
     * @throws \BookStack\Exceptions\NotFoundException
     */
    public function update(Request $request, $id)
    {
        
        $this->checkPermission('space-update-all');
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'string|max:1000',
            'image' => $this->imageRepo->getImageValidationRules(),
        ]);
        $input = $request->all();
        $space = $this->spaceRepo->find($id);
        $space = $this->spaceRepo->updateFromInput($space, $input);
        $this->updateActions($space, $request);
        Activity::add($space, 'space_update', $space->id);
    
        return redirect($space->getUrl());
    }

    /**
     * Shows the page to confirm deletion
     * @param $bookSlug
     * @return \Illuminate\View\View
     */
    public function showDelete($id)
    {
        $space = $this->spaceRepo->find($id);
        $this->checkOwnablePermission('space-delete', $space);
        $this->setPageTitle(trans('space.space_delete_named', ['spaceName'=>$space->name]));
        return view('space.delete', ['space' => $space, 'current' => $space]);
    }

    /**
     * Shows the view which allows pages to be re-ordered and sorted.
     * @param string $bookSlug
     * @return \Illuminate\View\View
     * @throws \BookStack\Exceptions\NotFoundException
     */
    public function sort($bookSlug)
    {
        $book = $this->entityRepo->getBySlug('book', $bookSlug);
        $this->checkOwnablePermission('book-update', $book);

        $bookChildren = $this->entityRepo->getBookChildren($book, true);

        $this->setPageTitle(trans('entities.books_sort_named', ['bookName'=>$book->getShortName()]));
        return view('books.sort', ['book' => $book, 'current' => $book, 'bookChildren' => $bookChildren]);
    }

    /**
     * Shows the sort box for a single book.
     * Used via AJAX when loading in extra books to a sort.
     * @param $bookSlug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSortItem($bookSlug)
    {
        $book = $this->entityRepo->getBySlug('book', $bookSlug);
        $bookChildren = $this->entityRepo->getBookChildren($book);
        return view('books.sort-box', ['book' => $book, 'bookChildren' => $bookChildren]);
    }

    /**
     * Saves an array of sort mapping to pages and chapters.
     * @param  string $bookSlug
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveSort($bookSlug, Request $request)
    {
        $book = $this->entityRepo->getBySlug('book', $bookSlug);
        $this->checkOwnablePermission('book-update', $book);

        // Return if no map sent
        if (!$request->filled('sort-tree')) {
            return redirect($book->getUrl());
        }

        // Sort pages and chapters
        $sortMap = collect(json_decode($request->get('sort-tree')));
        $bookIdsInvolved = collect([$book->id]);

        // Load models into map
        $sortMap->each(function ($mapItem) use ($bookIdsInvolved) {
            $mapItem->type = ($mapItem->type === 'page' ? 'page' : 'chapter');
            $mapItem->model = $this->entityRepo->getById($mapItem->type, $mapItem->id);
            // Store source and target books
            $bookIdsInvolved->push(intval($mapItem->model->book_id));
            $bookIdsInvolved->push(intval($mapItem->book));
        });

        // Get the books involved in the sort
        $bookIdsInvolved = $bookIdsInvolved->unique()->toArray();
        $booksInvolved = $this->entityRepo->getManyById('book', $bookIdsInvolved, false, true);
        // Throw permission error if invalid ids or inaccessible books given.
        if (count($bookIdsInvolved) !== count($booksInvolved)) {
            $this->showPermissionError();
        }
        // Check permissions of involved books
        $booksInvolved->each(function (Book $book) {
             $this->checkOwnablePermission('book-update', $book);
        });

        // Perform the sort
        $sortMap->each(function ($mapItem) {
            $model = $mapItem->model;

            $priorityChanged = intval($model->priority) !== intval($mapItem->sort);
            $bookChanged = intval($model->book_id) !== intval($mapItem->book);
            $chapterChanged = ($mapItem->type === 'page') && intval($model->chapter_id) !== $mapItem->parentChapter;

            if ($bookChanged) {
                $this->entityRepo->changeBook($mapItem->type, $mapItem->book, $model);
            }
            if ($chapterChanged) {
                $model->chapter_id = intval($mapItem->parentChapter);
                $model->save();
            }
            if ($priorityChanged) {
                $model->priority = intval($mapItem->sort);
                $model->save();
            }
        });

        // Rebuild permissions and add activity for involved books.
        $booksInvolved->each(function (Book $book) {
            $this->entityRepo->buildJointPermissionsForBook($book);
            Activity::add($book, 'book_sort', $book->id);
        });

        return redirect($book->getUrl());
    }

    /**
     * Remove the space.
     * @return Response
     */
    public function destroy($id)
    {
        $space = $this->spaceRepo->find($id);
        $this->checkOwnablePermission('space-delete', $space);
        Activity::addMessage('space_delete', 0, $space->name);

        if ($space->cover) {
            $this->imageRepo->destroyImage($space->cover);
        }
        $this->spaceRepo->destroySpace($space);

        return redirect('/space');
    }

    /**
     * Show the Restrictions view.
     * @param $bookSlug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPermissions($bookSlug)
    {
        $book = $this->entityRepo->getBySlug('book', $bookSlug);
        $this->checkOwnablePermission('restrictions-manage', $book);
        $roles = $this->userRepo->getRestrictableRoles();
        return view('books.permissions', [
            'book' => $book,
            'roles' => $roles
        ]);
    }

    /**
     * Set the restrictions for this book.
     * @param $bookSlug
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \BookStack\Exceptions\NotFoundException
     * @throws \Throwable
     */
    public function permissions($bookSlug, Request $request)
    {
        $book = $this->entityRepo->getBySlug('book', $bookSlug);
        $this->checkOwnablePermission('restrictions-manage', $book);
        $this->entityRepo->updateEntityPermissionsFromRequest($request, $book);
        session()->flash('success', trans('entities.books_permissions_updated'));
        return redirect($book->getUrl());
    }

    /**
     * Export a book as a PDF file.
     * @param string $bookSlug
     * @return mixed
     */
    public function exportPdf($bookSlug)
    {
        $book = $this->entityRepo->getBySlug('book', $bookSlug);
        $pdfContent = $this->exportService->bookToPdf($book);
        return $this->downloadResponse($pdfContent, $bookSlug . '.pdf');
    }

    /**
     * Export a book as a contained HTML file.
     * @param string $bookSlug
     * @return mixed
     */
    public function exportHtml($bookSlug)
    {
        $book = $this->entityRepo->getBySlug('book', $bookSlug);
        $htmlContent = $this->exportService->bookToContainedHtml($book);
        return $this->downloadResponse($htmlContent, $bookSlug . '.html');
    }

    /**
     * Export a book as a plain text file.
     * @param $bookSlug
     * @return mixed
     */
    public function exportPlainText($bookSlug)
    {
        $book = $this->entityRepo->getBySlug('book', $bookSlug);
        $textContent = $this->exportService->bookToPlainText($book);
        return $this->downloadResponse($textContent, $bookSlug . '.txt');
    }

    /**
     * Handles updating the cover image.
     * @param Space $space
     * @param Request $request
     * @throws \BookStack\Exceptions\ImageUploadException
     */
    protected function updateActions(Space $space, Request $request)
    {
        // Update the cover image if in request
        if ($request->has('image')) {
            $this->imageRepo->destroyImage($space->cover);
            $newImage = $request->file('image');
            $image = $this->imageRepo->saveNew($newImage, 'cover_space', $space->id, 512, 512, true);
            $space->image_id = $image->id;
            $space->save();
        }

        if ($request->has('image_reset')) {
            $this->imageRepo->destroyImage($space->cover);
            $space->image_id = 0;
            $space->save();
        }
    }
}
