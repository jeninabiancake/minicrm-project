<?php

namespace App\Http\Controllers;

use App\Helpers\MailerFactory;
use App\Models\ContactStatus;
use App\Models\Document;
use App\Models\Task;
use App\Models\TaskDocument;
use App\Models\TaskStatus;
use App\Models\TaskType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{

    protected $mailer;

    public function __construct(MailerFactory $mailer)
    {
        $this->middleware('admin:index-list_tasks|create-create_task|show-view_task|edit-edit_task|destroy-delete_task|getAssignTask-assign_task|getUpdateStatus-update_task_status', ['except' => ['store', 'update', 'postAssignTask', 'postUpdateStatus']]);

        $this->mailer = $mailer;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $query = Task::where('name', 'like', "%$keyword%");
        } else {
            $query = Task::latest();
        }

        if(\request('assigned_user_id') != null) {
            $query->where('assigned_user_id', \request('assigned_user_id'));
        }

        // if not admin user show tasks if assigned to or created by that user
        if(Auth::user()->is_admin == 0) {

            $query->where(function ($query) {
                $query->where('assigned_user_id', Auth::user()->id)
                    ->orWhere('created_by_id', Auth::user()->id);
            });

        }

        $tasks = $query->paginate($perPage);

        return view('pages.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $data = $this->getFormData();

        list($users, $statuses, $task_types, $contact_statuses, $documents) = $data;

        return view('pages.tasks.create', compact('users', 'documents', 'statuses', 'task_types', 'contact_statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->do_validate($request);

        $requestData = $request->all();

        if(isset($requestData['documents'])) {

            $documents = $requestData['documents'];

            unset($requestData['documents']);

            $documents = array_filter($documents, function ($value) {
                return !empty($value);
            });
        }

        $requestData['created_by_id'] = Auth::user()->id;

        if(empty($requestData['contact_type'])) {

            $requestData['contact_id'] = null;
        }
        
        $task = Task::create($requestData);


        // insert documents
        if($task && $task->id) {

            if(isset($documents)) {

                $this->insertDocuments($documents, $task->id);
            }
        }


        // send notifications email
        if(getSetting("enable_email_notification") == 1 && isset($requestData['assigned_user_id'])) {

            $this->mailer->sendAssignTaskEmail("Task assigned to you", User::find($requestData['assigned_user_id']), $task);
        }

        return redirect('admin/tasks')->with('flash_message', 'Task added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);

        return view('pages.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $data = $this->getFormData($id);

        list($users, $statuses, $task_types, $contact_statuses, $documents, $task, $selected_documents) = $data;

        return view('pages.tasks.edit', compact('task', 'users', 'documents', 'statuses', 'task_types', 'selected_documents', 'contact_statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->do_validate($request);

        $requestData = $request->all();

        if(isset($requestData['documents'])) {

            $documents = $requestData['documents'];

            unset($requestData['documents']);

            $documents = array_filter($documents, function ($value) {
                return !empty($value);
            });
        }

        if(empty($requestData['contact_type'])) {

            $requestData['contact_id'] = null;
        }

        $requestData['modified_by_id'] = Auth::user()->id;
        
        $task = Task::findOrFail($id);

        $old_assign_user_id = $task->assigned_user_id;

        $old_task_status = $task->status;

        $task->update($requestData);


        // delete documents if exist
        TaskDocument::where('task_id', $id)->delete();

        // insert documents
        if(isset($documents)) {

            $this->insertDocuments($documents, $id);
        }


        // send notifications emails

        if(getSetting("enable_email_notification") == 1) {

            if (isset($requestData['assigned_user_id']) && $old_assign_user_id != $requestData['assigned_user_id']) {

                $this->mailer->sendAssignTaskEmail("Task assigned to you", User::find($requestData['assigned_user_id']), $task);
            }

            // if status get update then send a notification to both the super admin and the assigned user
            if($old_task_status != $requestData['status']) {

                $super_admin = User::where('is_admin', 1)->first();

                if($super_admin->id == $task->assigned_user_id) {
                    $this->mailer->sendUpdateTaskStatusEmail("Task status update", User::find($task->assigned_user_id), $task);
                } else {
                    $this->mailer->sendUpdateTaskStatusEmail("Task status update", User::find($task->assigned_user_id), $task);

                    $this->mailer->sendUpdateTaskStatusEmail("Task status update", $super_admin, $task);
                }

            }
        }

        return redirect('admin/tasks')->with('flash_message', 'Task updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $task = Task::find($id);

        Task::destroy($id);

        if(getSetting("enable_email_notification") == 1) {
            $this->mailer->sendDeleteTaskEmail("Task deleted", User::find($task->assigned_user_id), $task);
        }

        return redirect('admin/tasks')->with('flash_message', 'Task deleted!');
    }


    public function getAssignTask($id)
    {
        $task = Task::find($id);

        $users = User::where('id', '!=', $task->assigned_user_id)->get();

        return view('pages.tasks.assign', compact('users', 'task'));
    }


    public function postAssignTask(Request $request, $id)
    {
        $this->validate($request, [
            'assigned_user_id' => 'required'
        ]);

        $task = Task::find($id);

        $task->update(['assigned_user_id' => $request->assigned_user_id]);

        if(getSetting("enable_email_notification") == 1) {
            $this->mailer->sendAssignTaskEmail("Task assigned to you", User::find($request->assigned_user_id), $task);
        }

        return redirect('admin/tasks')->with('flash_message', 'Task assigned!');
    }


    public function getUpdateStatus($id)
    {
        $task = Task::find($id);

        $statuses = TaskStatus::all();

        return view('pages.tasks.update_status', compact('task', 'statuses'));
    }

    public function postUpdateStatus(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required'
        ]);

        $task = Task::find($id);

        $task->update(['status' => $request->status]);


        if(getSetting("enable_email_notification") == 1 && !empty($task->assigned_user_id)) {

            $super_admin = User::where('is_admin', 1)->first();

            if($super_admin->id == $task->assigned_user_id) {

                $this->mailer->sendUpdateTaskStatusEmail("Task status update", User::find($task->assigned_user_id), $task);
            } else {
                $this->mailer->sendUpdateTaskStatusEmail("Task status update", User::find($task->assigned_user_id), $task);

                $this->mailer->sendUpdateTaskStatusEmail("Task status update", $super_admin, $task);
            }
        }

        return redirect('admin/tasks')->with('flash_message', 'Task status updated!');
    }


    /**
     * insert documents
     *
     *
     * @param $documents
     * @param $task_id
     */
    protected function insertDocuments($documents, $task_id)
    {
        foreach ($documents as $document) {

            $taskDocument = new TaskDocument();

            $taskDocument->document_id = $document;

            $taskDocument->task_id = $task_id;

            $taskDocument->save();
        }
    }


    /**
     * do_validate
     *
     *
     * @param $request
     */
    protected function do_validate($request)
    {
        $this->validate($request, [
            'name' => 'required',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date'
        ]);
    }


    /**
     * get form data for the tasks form
     *
     *
     *
     * @param null $id
     * @return array
     */
    protected function getFormData($id = null)
    {
        $users = User::where('is_active', 1)->get();

        $statuses = TaskStatus::all();

        $task_types = TaskType::all();

        $contact_statuses = ContactStatus::all();

        if(Auth::user()->is_admin == 1) {
            $documents = Document::where('status', 1)->get();
        } else {
            $super_admin = User::where('is_admin', 1)->first();

            $documents = Document::where('status', 1)->where(function ($query) use ($super_admin) {
                $query->where('created_by_id', Auth::user()->id)
                    ->orWhere('assigned_user_id', Auth::user()->id)
                    ->orWhere('created_by_id', $super_admin->id)
                    ->orWhere('assigned_user_id', $super_admin->id);
            })->get();
        }

        if($id == null) {

            return [$users, $statuses, $task_types, $contact_statuses, $documents];
        }

        $task = Task::findOrFail($id);

        $selected_documents = $task->documents()->pluck('document_id')->toArray();

        return [$users, $statuses, $task_types, $contact_statuses, $documents, $task, $selected_documents];
    }
}