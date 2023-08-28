<?php

namespace App\Http\Controllers;

use App\Helpers\MailerFactory;
use App\Models\MailboxFolder;
use App\Models\Mailbox;
use App\Models\MailboxAttachment;
use App\Models\MailboxFlags;
use App\Models\MailboxReceiver;
use App\Models\MailboxTmpReceiver;
use App\Models\MailboxUserFolder;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MailboxController extends Controller
{
    protected $mailer;

    protected $folders = array();

    public function __construct(MailerFactory $mailer)
    {
        $this->mailer = $mailer;

        $this->getFolders();
    }

    /**
     * index
     *
     * list all messages
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $folder = "")
    {
       
    }

 
    /**
     * create
     *
     * show compose form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        
    }


    /**
     * store
     *
     * store and send the message
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
         
    }


    /**
     * show email
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        
    }


    /**
     * toggle important
     *
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleImportant(Request $request)
    {
        
    }


    /**
     * trash email
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function trash(Request $request)
    {
        
    }


    /**
     * getReply
     *
     * show reply form
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getReply($id)
    {
        
    }


    /**
     * postReply
     *
     *
     * send the reply
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postReply(Request $request, $id)
    {
        
    }

    /**
     * getForward
     *
     * show forward form
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getForward($id)
    {
       
    }
     

     /**
     * postForward
     *
     * forward the message
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postForward(Request $request, $id)
    {
        
    }


    /**
     * send
     *
     * used to send a Draft message
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send($id)
    {
        
    }

    /**
     * get Folders
     */
    private function getFolders(): void
    {
        $this->folders = MailboxFolder::all();
    }

       /**
     * index
     *
     * @return \Illuminate\View\View
     */
    public function indexFunction(Request $request, $folder = "")
    {
        $keyword = $request->get('search');
        $perPage = 15;
        $folders = $this->folders;
        if(empty($folder)) {
            $folder = "Inbox";
        }
        $messages = $this->getData($keyword, $perPage, $folder);
        $unreadMessages = count(getUnreadMessages());
        return view('pages.mailbox.index', compact('folders', 'messages', 'unreadMessages'));
    }
    /**
     * getData
     *
     *
     * @param $keyword
     * @param $perPage
     * @param $foldername
     * @return array
     */
    private function getData($keyword, $perPage, $foldername)
    {
        $folder = MailboxFolder::where('title', $foldername)->first();
        if($foldername == "Inbox") {
            $query = Mailbox::join('mailbox_receiver', 'mailbox_receiver.mailbox_id', '=', 'mailbox.id')
                    ->join('mailbox_user_folder', 'mailbox_user_folder.user_id', '=', 'mailbox_receiver.receiver_id')
                    ->join('mailbox_flags', 'mailbox_flags.user_id', '=', 'mailbox_user_folder.user_id')
                    ->where('mailbox_receiver.receiver_id', Auth::user()->id)
                    ->where('mailbox_user_folder.folder_id', $folder->id)
                    ->where('sender_id', '!=', Auth::user()->id)
//                    ->where('parent_id', 0)
                    ->whereRaw('mailbox.id=mailbox_receiver.mailbox_id')
                    ->whereRaw('mailbox.id=mailbox_flags.mailbox_id')
                    ->whereRaw('mailbox.id=mailbox_user_folder.mailbox_id')
                    ->select(["*", "mailbox.id as id", "mailbox_flags.id as mailbox_flag_id", "mailbox_user_folder.id as mailbox_folder_id"]);
        } else if ($foldername == "Sent" || $foldername == "Drafts") {
            $query = Mailbox::join('mailbox_user_folder', 'mailbox_user_folder.mailbox_id', '=', 'mailbox.id')
                ->join('mailbox_flags', 'mailbox_flags.user_id', '=', 'mailbox_user_folder.user_id')
                ->where('mailbox_user_folder.folder_id', $folder->id)
                ->where('mailbox_user_folder.user_id', Auth::user()->id)
//                ->where('parent_id', 0)
                ->whereRaw('mailbox.id=mailbox_flags.mailbox_id')
                ->whereRaw('mailbox.id=mailbox_user_folder.mailbox_id')
                ->select(["*", "mailbox.id as id", "mailbox_flags.id as mailbox_flag_id", "mailbox_user_folder.id as mailbox_folder_id"]);
        } else {
            $query = Mailbox::join('mailbox_user_folder', 'mailbox_user_folder.mailbox_id', '=', 'mailbox.id')
                ->join('mailbox_flags', 'mailbox_flags.user_id', '=', 'mailbox_user_folder.user_id')
                ->leftJoin('mailbox_receiver', 'mailbox_receiver.mailbox_id', '=', 'mailbox.id')
                ->where(function ($query) {
                    $query->where('mailbox_user_folder.user_id', Auth::user()->id)
                          ->orWhere('mailbox_receiver.receiver_id', Auth::user()->id);
                })
                ->where('mailbox_user_folder.folder_id', $folder->id)
//                ->where('parent_id', 0)
                ->whereRaw('mailbox.id=mailbox_flags.mailbox_id')
                ->whereRaw('mailbox.id=mailbox_user_folder.mailbox_id')
                ->whereRaw('mailbox_user_folder.user_id!=mailbox_receiver.receiver_id')
                ->select(["*", "mailbox.id as id", "mailbox_flags.id as mailbox_flag_id", "mailbox_user_folder.id as mailbox_folder_id"]);
        }
        if (!empty($keyword)) {
            $query->where('subject', 'like', "%$keyword%");
        }
        $query->orderBy('mailbox.id', 'DESC');
        $messages = $query->paginate($perPage);
        return $messages;
    }
}

