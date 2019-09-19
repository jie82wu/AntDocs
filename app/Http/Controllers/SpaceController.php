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
        $this->middleware(function ($request, $next) use($space_id) {
            if ($space_id) {
                $space = $this->spaceRepo->find($space_id);
                $this->space = $space;
                view()->share('space', $space);                
                    Cache::forever(cacheKey(), $space);                    
            } else {
                Cache::forget(cacheKey());
            }
            return $next($request);
        });
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
        $books = $this->spaceRepo->getPrivateBooks(false);
        //$books = $this->entityRepo->getAllPaginated('book', 18, 'name', 'asc');
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
        //$this->checkPermission('space-create-all');
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'string|max:1000',
            'image' => $this->imageRepo->getImageValidationRules(),
        ]);

        $input = $request->all();
        $space = $this->spaceRepo->createFromInput($input);
        $this->updateActions($space, $request);
        $this->spaceRepo->copyDefaultRoles($space);
        
        //add creator to invited users
        $this->spaceRepo->saveUserToSpace($space, user()->id, ['status'=>1, 'is_admin'=>1]);
        
        Activity::add($space, 'space_create', $space->id);

        return redirect($space->getUrl());
    }
    
    /**
     * show share space by id
     */
    public function showSpace(Request $request, $id)
    {
        $space = $this->spaceRepo->find($id);
        $view = setting()->getUser($this->currentUser, 'books_view_type', config('app.views.books'));
        return view('space.show',[
            'spaceSel'=>true,
            'view'=>$view,
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
            if ($book)
                $books->push($book);
        }
        return view('space.show-my-space',[
            'books'=>$books,
            'view'=>$view,
            'spaceSel'=>true,
        ]);
    }

    public function showSpaceBook(Request $request, $id, $oid)
    {
        $space = $this->spaceRepo->find($id);
        $book = $this->entityRepo->getById('book', $oid, true, true);
        isCreator($book) || $this->checkOwnablePermission('book-view', $book);
        
        $bookChildren = $book->getChildren();
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
        $chapter = $this->entityRepo->getById('chapter', $oid, true, true);
        isCreator($chapter->book) || $this->checkOwnablePermission('chapter-view', $chapter);
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
        $page = $this->entityRepo->getById('page', $oid, 1,1);
        isCreator($page->book) || $this->checkOwnablePermission('page-view', $page);
    
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
        isCreator($book) || $this->checkOwnablePermission('book-view', $book);

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
        //$user_ids = $this->spaceRepo->getUsersId($space);
        $admin_ids = $this->spaceRepo->getAdminsId($space);
        $book_ids = $this->spaceRepo->getBooksId($space);
        //space管理者
        $this->spaceRepo->checkIsAdmin($space);
        $this->setPageTitle(trans('space.books_edit_named', ['spaceName'=>$space->name]));
        return view('space.edit', [
            'current' => $space,
            //'uids' => $user_ids,
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
        
        $this->spaceRepo->checkIsAdmin($space);

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
        $this->spaceRepo->checkIsAdmin($space);
        $this->setPageTitle(trans('space.space_delete_named', ['spaceName'=>$space->name]));
        return view('space.delete', [
            'current' => $space]);
    }

    /**
     * Remove the space.
     * @return Response
     */
    public function destroy($id)
    {
        $space = $this->spaceRepo->find($id);
        $this->spaceRepo->checkIsAdmin($space);
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
        $this->spaceRepo->checkIsAdmin($space);
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
        $this->spaceRepo->checkIsAdmin($space);
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
        $this->spaceRepo->checkIsAdmin($space);
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
        $this->validate($request, [
            'display_name' => 'required|min:3|max:200',
            'description' => 'max:250'
        ]);
        
        $role = $this->permissionsRepo->updateRole($role_id, $request->all());
        session()->flash('success', trans('settings.role_update_success'));
        return redirect('/space/'.$role->space_id.'/roles');
    }
    
    public function showDeleteRole($id, $role_id)
    { 
        $role = $this->permissionsRepo->getRoleById($role_id);
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

    public function deleteRole(Request $request, $id, $role_id)
    {
        $role = $this->permissionsRepo->getRoleById($role_id);
        $this->spaceRepo->checkIsAdmin($role->space);
        try {
            $role = $this->permissionsRepo->deleteRole($role_id, $request->get('migrate_role_id'));
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
        //$users = $this->userRepo->getSpaceUsersPaginated(20, $id);
        $users = $this->spaceRepo->getInvitedUsers($space);
        $this->setPageTitle(trans('settings.users'));
        return view('space.users.index', [
            'users' => $users
        ]);
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
        $email = $request->get('email');
        $space = $this->spaceRepo->find($id);
        $user = $space->users()->where('email',$email)->first();
        $is_in = $is_exist = true;
        if (!$user) {
            $user = $this->userRepo->getByEmail($email);
            $is_in = false;
        }    
        
        if (!$user)
            $is_exist = false;
        
        $this->spaceRepo->checkIsAdmin($space);
        $authMethod = config('auth.method');
        return view('space.users.create', [
            'authMethod' => $authMethod,
            'roles' => $space->roles,
            'model' => $user,
            'is_in' => $is_in,
            'is_exist' => $is_exist,
        ]);
    }
    
    //add user
    public function storeUsers(Request $request, $id)
    {
        $space = $this->spaceRepo->find($id);
        $this->spaceRepo->checkIsAdmin($space);
        $email = $request->get('email');
        $user = $this->userRepo->getByEmail($email);
        
        if ($user) {
            $status = 0;
            session()->flash('success', trans('space.invite_message_is_send_out'));
        } else {
            $validationRules = ['name' => 'required', 'email' => 'required|email|unique:users,email'];
    
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
    
            $user->space_id = $id;
            $user->save();
    
            $this->userRepo->downloadAndAssignUserAvatar($user);
            session()->flash('success', trans('space.user_add_success'));
            $status = 1;
        }
    
        //send invite message
        $this->spaceRepo->saveUserToSpace($space, $user->id, ['status'=>$status]);
    
        //assign roles
        if ($request->filled('roles')) {
            $roles = $request->get('roles');
            $this->userRepo->setUserRoles($user, $roles);
        }
        
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
    
    //移除空间用户
    public function showRemoveUser(Request $request, $id, $uid)
    {
        $space = $this->spaceRepo->find($id);
        $user = $this->userRepo->getById($uid);
        return view('space.users.delete', [
            'space' => $space,
            'user' => $user,
        ]);
    }
    public function removeUser(Request $request, $id, $uid)
    {
        $space = $this->spaceRepo->find($id);
        $this->spaceRepo->removeUser($space, $uid);
        session()->flash('success', trans('space.user_remove_success'));
        return redirect('/space/'.$space->id.'/users');
    }
}
