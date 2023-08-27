<?php

namespace App\Http\Controllers;

use App\Helpers\MailerFactory;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentsController extends Controller
{

    protected $mailer;

    public function __construct(MailerFactory $mailer)
    {
        $this->middleware('admin:index-list_documents|create-create_document|show-view_document|edit-edit_document|destroy-delete_document', ['except' => ['store', 'update']]);

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
            $query = Document::where('name', 'like', "%$keyword%");
        } else {
            $query = Document::latest();
        }

        // if not is admin user
        if(Auth::user()->is_admin == 0) {

            $query->where('assigned_user_id', Auth::user()->id)
                  ->orWhere('created_by_id', Auth::user()->id);
        }

        $documents = $query->paginate($perPage);

        return view('pages.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $document_types = DocumentType::all();

        $users = User::all();

        return view('pages.documents.create', compact('document_types', 'users'));
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
        
        $requestData = $request->except(['_token']);

        checkDirectory("documents");

        $requestData['file'] = uploadFile($request, 'file', public_path('uploads/documents'));

        $requestData['created_by_id'] = Auth::user()->id;

        $document = Document::create($requestData);

        if(isset($requestData['assigned_user_id']) && getSetting("enable_email_notification") == 1) {

            $this->mailer->sendAssignDocumentEmail("Document assigned to you", User::find($requestData['assigned_user_id']), $document);
        }

        return redirect('admin/documents')->with('flash_message', 'Document added!');
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
        $document = Document::findOrFail($id);

        return view('pages.documents.show', compact('document'));
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
        $document = Document::findOrFail($id);

        $document_types = DocumentType::all();

        $users = User::all();

        return view('pages.documents.edit', compact('document', 'document_types', 'users'));
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
        $this->do_validate($request, 0);

        $requestData = $request->except(['_token']);

        if ($request->hasFile('file')) {
            checkDirectory("documents");
            $requestData['file'] = uploadFile($request, 'file', public_path('uploads/documents'));
        }

        $requestData['modified_by_id'] = Auth::user()->id;
        
        $document = Document::findOrFail($id);

        $old_assign_user_id = $document->assigned_user_id;

        $document->update($requestData);

        if(isset($requestData['assigned_user_id']) && $old_assign_user_id != $requestData['assigned_user_id'] && getSetting("enable_email_notification") == 1) {

            $this->mailer->sendAssignDocumentEmail("Document assigned to you", User::find($requestData['assigned_user_id']), $document);
        }

        return redirect('admin/documents')->with('flash_message', 'Document updated!');
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
        Document::destroy($id);

        return redirect('admin/documents')->with('flash_message', 'Document deleted!');
    }


    public function getAssignDocument($id)
    {
        $document = Document::find($id);

        $users = User::where('id', '!=', $document->assigned_user_id)->get();

        return view('pages.documents.assign', compact('users', 'document'));
    }


    public function postAssignDocument(Request $request, $id)
    {
        $this->validate($request, [
            'assigned_user_id' => 'required'
        ]);

        $document = Document::find($id);

        $document->update(['assigned_user_id' => $request->assigned_user_id]);

        if(getSetting("enable_email_notification") == 1) {
             $this->mailer->sendAssignDocumentEmail("Document assigned to you", User::find($request->assigned_user_id), $document);
        }

        return redirect('admin/documents')->with('flash_message', 'Document assigned!');
    }


    protected function do_validate($request, $is_create = 1)
    {
        $mimes = 'mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,xls,xlsx,odt,dot,html,htm,rtf,ods,xlt,csv,bmp,odp,pptx,ppsx,ppt,potm';

        $this->validate($request, [
            'name' => 'required',
            'file' => ($is_create == 0? $mimes:"required|" . $mimes)
        ]);
    }
}