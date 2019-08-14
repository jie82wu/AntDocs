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
use BookStack\Orz\Message;
use BookStack\Entities\Repos\PageRepo;

class MessageController extends Controller
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
    public function index(Request $request)
    {
        $message = Message::where(['to'=>user()->id])->where(['status'=>0])->get();
        $this->setPageTitle(trans('common.message'));
        return view('message.index', [
            'messages'=>$message,
        ]);
    }
    
    public function handleMessage(Request $request, $id, $status)
    {
        $message = Message::find($id);
        switch ($message->type) {
            case 'space_invite':
                if (!in_array($status, [1,2,3]))
                    return redirect('/message');
                $message->status = 1;
                $message->save();
                $spaceIdAndUserId = explode('|', $message->rel_id);
                DB::update('update space_user set status = ? where space_id = ? and user_id=?', [$status, $spaceIdAndUserId[0], $spaceIdAndUserId[1]]);
                Activity::add($message, 'message_handle', $message->id);
                break;
        }
    
        return redirect('/message');
    }

}
