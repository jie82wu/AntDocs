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
use BookStack\Auth\Permissions\PermissionsRepo;
use BookStack\Auth\User;
use Illuminate\Support\Facades\Cache;

class SpaceController extends Controller
{
    
    protected $permissionsRepo;
    protected $entityRepo;
    protected $userRepo;
    protected $exportService;
    protected $entityContextManager;
    protected $imageRepo;
    protected $tagRepo;
    protected $pageRepo;
    protected $user;
    protected $space;

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
        PermissionsRepo $permissionsRepo,
        User $user,
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
        $this->permissionsRepo = $permissionsRepo;
        $this->user = $user;
        $space_id=request('id');
        if ($space_id) {
            $space = $this->spaceRepo->find($space_id);
            $this->space = $space;
            view()->share('space', $space);
            Cache::forever('current_space', $space);
        }
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
     * show share space by id
     */
    public function showSpace(Request $request, $id)
    {
        $space = $this->spaceRepo->find($id);
        return view('space.show',[
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
        $page = $this->entityRepo->getById('page', $oid, 1);
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
     * edit.
     * @param $id
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
        //如果是共享space管理者
        $this->spaceRepo->checkUserPermission($space, 'admin') ||
            $this->checkOwnablePermission('space-update', $space);
        $this->setPageTitle(trans('space.books_edit_named', ['spaceName'=>$space->name]));
        return view('space.edit', [
            'current' => $space,
            'uids' => $user_ids,
            'aids' => $admin_ids,
            'bids' => $book_ids,
            'users'=>$list['users'],
            'books'=>$list['books']
            ]);
    }

    /**
     * Update  .
     * @param Request $request
     * @param          $id
     * @return Response
     * @throws \BookStack\Exceptions\ImageUploadException
     * @throws \BookStack\Exceptions\NotFoundException
     */
    public function update(Request $request, $id)
    {
        $space = $this->spaceRepo->find($id);
        if (!$space)
            throw new PermissionsException(trans('errors.space_not_found'));
        
        //if not creator
        //if ($space->created_by != user()->id) {
        $this->spaceRepo->checkUserPermission($space, 'admin') ||
                $this->checkPermission('space-update-all');
        //}
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'string|max:1000',
            'image' => $this->imageRepo->getImageValidationRules(),
        ]);
        $input = $request->all();        
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
        $this->spaceRepo->checkUserPermission($space, 'admin') ||
            $this->checkOwnablePermission('space-delete', $space);
        $this->setPageTitle(trans('space.space_delete_named', ['spaceName'=>$space->name]));
        return view('space.delete', [
            'current' => $space]);
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
        $this->spaceRepo->checkUserPermission($space, 'admin') ||
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
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPermissions($id)
    {
        $space = $this->spaceRepo->find($id);
        $this->spaceRepo->checkUserPermission($space, 'admin') ||
        $this->checkOwnablePermission('restrictions-manage', $space);
        $roles = $this->userRepo->getRestrictableRoles();
        return view('space.permissions', [
            'roles' => $roles
        ]);
    }

    /**
     * Set the restrictions for this book.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \BookStack\Exceptions\NotFoundException
     * @throws \Throwable
     */
    public function permissions($id, Request $request)
    {
        $space = $this->spaceRepo->find($id);
        $this->spaceRepo->checkUserPermission($space, 'admin') ||
        $this->checkOwnablePermission('restrictions-manage', $space);
        $this->entityRepo->updateEntityPermissionsFromRequest($request, $space);
        session()->flash('success', trans('space.space_permissions_updated'));
        return redirect($space->getUrl());
    }
    
    
    public function showRoles($id)
    {
        $space = $this->spaceRepo->find($id);
        
        //permissions check
        //$this->spaceRepo->checkUserPermission($space, 'admin') || $this->checkOwnablePermission('restrictions-manage', $space);
        
        //$roles = $this->userRepo->getRestrictableRoles();
        return view('space.roles', [
        ]);
    }
    
    public function createRole(Request $request, $id)
    {
        $space = $this->spaceRepo->find($id);
        $this->checkPermission('user-roles-manage');
        return view('space.roles.create',[
        ]);   
    }
    
    public function storeRole(Request $request, $id)
    {
        $space = $this->spaceRepo->find($id);
        $this->spaceRepo->checkIsAdmin($space);
        $this->validate($request, [
            'display_name' => 'required|min:3|max:200',
            'description' => 'max:250'
        ]);
    
        $this->permissionsRepo->saveNewRole($request->all(), $space->id);
        session()->flash('success', trans('settings.role_create_success'));
        return redirect('/space/'.$space->id.'/roles');
    }

    public function editRole($id,$role_id)
    {
        $space = $this->spaceRepo->find($id);
        $this->spaceRepo->checkIsAdmin($space);
        $role = $this->permissionsRepo->getRoleById($role_id);
        if ($role->hidden) {
            throw new PermissionsException(trans('errors.space_not_found'));
        }
        return view('space.roles.edit', [
            'role' => $role, 
        ]);
    }

    public function updateRole($id, $role_id, Request $request)
    {
        $space = $this->spaceRepo->find($id);
        $this->spaceRepo->checkIsAdmin($space);
        //$this->checkPermission('user-roles-manage');
        $this->validate($request, [
            'display_name' => 'required|min:3|max:200',
            'description' => 'max:250'
        ]);
        
        $role = $this->permissionsRepo->updateRole($role_id, $request->all());
        session()->flash('success', trans('settings.role_update_success'));
        return redirect('/space/'.$role->space_id.'/roles');
    }
    
    public function showDeleteRole($id)
    { 
        //$this->checkPermission('user-roles-manage');
        $role = $this->permissionsRepo->getRoleById($id);
        $space = $role->space;
        $this->spaceRepo->checkIsAdmin($space);
        $roles = $this->permissionsRepo->getAllRolesExcept($role, ['space_id'=>$space->id]);
        $blankRole = $role->newInstance(['display_name' => trans('settings.role_delete_no_migration')]);
        $roles->prepend($blankRole);
        return view('space.roles.delete', [
            'role' => $role, 
            'roles' => $roles,
            ]);
    }

    public function deleteRole($id, Request $request)
    {
        //$this->checkPermission('user-roles-manage');
        $role = $this->permissionsRepo->getRoleById($id);
        $this->spaceRepo->checkIsAdmin($role->space);
        try {
            $role = $this->permissionsRepo->deleteRole($id, $request->get('migrate_role_id'));
        } catch (PermissionsException $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
        
        session()->flash('success', trans('settings.role_delete_success'));
        return redirect('/space/'.$role->space_id.'/roles');
    }
    
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
    
    public function showUsers(Request $request, $id)
    {
        $space = $this->spaceRepo->find($id);
        $this->spaceRepo->checkIsAdmin($space);
        $listDetails = [
            'order' => $request->get('order', 'asc'),
            'search' => $request->get('search', ''),
            'sort' => $request->get('sort', 'name'),
        ];
        $users = $this->userRepo->getAllUsersPaginatedAndSorted(20, $listDetails);
        $this->setPageTitle(trans('settings.users'));
        $users->appends($listDetails);
        $user_ids = $this->spaceRepo->getUsersId($space);
        return view('space.users.index', [
            'uids'=>$user_ids,
            'users' => $users, 'listDetails' => $listDetails]);
    }
    
    //select users
    public function saveUsers(Request $request, $id)
    {
        $space = $this->spaceRepo->find($id);
        $this->spaceRepo->checkIsAdmin($space);
        $input = $request->all();
        $this->spaceRepo->saveUsersToSpace($space, isset($input['users'])?$input['users']:[]);
        session()->flash('success', trans('space.user_add_success'));    
        return redirect('/space/'.$space->id.'/users');
    }
    
    public function createUsers(Request $request, $id)
    {
        $space = $this->spaceRepo->find($id);
        $this->spaceRepo->checkIsAdmin($space);
        $authMethod = config('auth.method');
        return view('space.users.create', [
            'authMethod' => $authMethod,
            'roles' => $space->roles
        ]);
    }
    
    //add user
    public function storeUsers(Request $request, $id)
    {
        $space = $this->spaceRepo->find($id);
        $this->spaceRepo->checkIsAdmin($space);
        $validationRules = [
            'name'             => 'required',
            'email'            => 'required|email|unique:users,email'
        ];
        
        $authMethod = config('auth.method');
        if ($authMethod === 'standard') {
            $validationRules['password'] = 'required|min:5';
            $validationRules['password-confirm'] = 'required|same:password';
        } elseif ($authMethod === 'ldap') {
            $validationRules['external_auth_id'] = 'required';
        }
        $this->validate($request, $validationRules);
        
        $user = $this->user->fill($request->all());
        
        if ($authMethod === 'standard') {
            $user->password = bcrypt($request->get('password'));
        } elseif ($authMethod === 'ldap') {
            $user->external_auth_id = $request->get('external_auth_id');
        }
        
        $user->save();
        
        if ($request->filled('roles')) {
            $roles = $request->get('roles');
            $this->userRepo->setUserRoles($user, $roles);
        }
        
        $this->userRepo->downloadAndAssignUserAvatar($user);
    
        $this->spaceRepo->saveUserToSpace($space, $user->id);
        
        session()->flash('success', trans('space.user_create_success'));
        
        return redirect('/space/'.$space->id.'/users');
    }
    
    public function editUsers(Request $request, $id, $uid)
    {
        $space = $this->spaceRepo->find($id);
        $this->spaceRepo->checkIsAdmin($space);
        $user = $this->userRepo->getById($uid);
        $authMethod = config('auth.method');
        return view('space.users.edit', [
            'user' => $user,
            'authMethod' => $authMethod,
            'roles' => $space->roles
        ]);
    }
    
    public function updateUsers(Request $request, $id, $uid)
    {
        $space = $this->spaceRepo->find($id);
        $this->spaceRepo->checkIsAdmin($space);
        $roles = $request->get('roles');
        $user = $this->userRepo->getById($uid);
        $this->userRepo->setUserRoles($user, $roles);
        session()->flash('success', trans('space.user_roles_edit_success'));
        session()->flash('select_user_id', $uid);
        return redirect('/space/'.$space->id.'/users');
    }
}
