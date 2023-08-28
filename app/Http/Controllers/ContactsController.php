<?php

namespace App\Http\Controllers;

use App\Helpers\MailerFactory;
use App\Models\Contact;
use App\Models\ContactDocument;
use App\Models\ContactEmail;
use App\Models\ContactPhone;
use App\Models\ContactStatus;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactsController extends Controller
{

    protected $mailer;

    public function __construct(MailerFactory $mailer)
    {
        $this->middleware('admin:index-list_contacts|create-create_contact|show-view_contact|edit-edit_contact|destroy-delete_contact|getAssignContact-assign_contact', ['except' => ['store', 'update', 'postAssignContact']]);

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
            $query = Contact::where('first_name', 'like', "%$keyword%")->orWhere('middle_name', 'like', "%$keyword%")->orWhere('last_name', 'like', "%$keyword%");
        } else {
            $query = Contact::latest();
        }

        if(\request('status_name') != null) {
            $query->where('status', '=', ContactStatus::where('name', \request('status_name'))->first()->id);
        }

        if(\request('assigned_user_id') != null) {
            $query->where('assigned_user_id', \request('assigned_user_id'));
        }

        // if not admin user show contacts if assigned to or created by that user
        if(Auth::user()->is_admin == 0) {

            $query->where(function ($query) {
                $query->where('assigned_user_id', Auth::user()->id)
                    ->orWhere('created_by_id', Auth::user()->id);
            });

        }

        $contacts = $query->paginate($perPage);

        return view('pages.contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $data = $this->getFormData();

        list($statuses, $users, $documents) = $data;

        return view('pages.contacts.create', compact('statuses', 'users', 'documents'));
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

        $emails = $requestData['emails'];

        $phones = $request['phones'];

        unset($requestData['emails'], $requestData['phones']);

        if(isset($requestData['documents'])) {

            $documents = $requestData['documents'];

            unset($requestData['documents']);

            $documents = array_filter($documents, function ($value) {
                return !empty($value);
            });
        }

        $requestData['created_by_id'] = Auth::user()->id;

        $contact = Contact::create($requestData);

        $emails = array_filter($emails, function ($value) {
           return !empty($value);
        });

        $phones = array_filter($phones, function ($value) {
            return !empty($value);
        });

        // insert emails & phones
        if($contact && $contact->id) {

            $this->insertEmails($emails, $contact->id);

            $this->insertPhones($phones, $contact->id);

            if(isset($documents)) {

                $this->insertDocuments($documents, $contact->id);
            }
        }

        // send notifications email
        if(getSetting("enable_email_notification") == 1 && isset($requestData['assigned_user_id'])) {

            $this->mailer->sendAssignContactEmail("Contact assigned to you", User::find($requestData['assigned_user_id']), $contact);
        }

        return redirect('admin/contacts')->with('flash_message', 'Contact added!');
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
        $contact = Contact::findOrFail($id);

        return view('pages.contacts.show', compact('contact'));
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

        list($statuses, $users, $documents, $contact, $selected_documents) = $data;

        return view('pages.contacts.edit', compact('contact', 'statuses', 'users', 'documents', 'selected_documents'));
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

        $emails = $requestData['emails'];

        $phones = $request['phones'];

        unset($requestData['emails'], $requestData['phones']);

        $emails = array_filter($emails, function ($value) {
            return !empty($value);
        });

        $phones = array_filter($phones, function ($value) {
            return !empty($value);
        });

        if(isset($requestData['documents'])) {

            $documents = $requestData['documents'];

            unset($requestData['documents']);

            $documents = array_filter($documents, function ($value) {
                return !empty($value);
            });
        }

        $requestData['modified_by_id'] = Auth::user()->id;

        $contact = Contact::findOrFail($id);

        $old_assign_user_id = $contact->assigned_user_id;

        $old_contact_status = $contact->status;

        $contact->update($requestData);

        // delete emails if exist
        ContactEmail::where('contact_id', $id)->delete();

        if($emails) {

            // insert
            $this->insertEmails($emails, $id);
        }

        // delete phones if exist
        ContactPhone::where('contact_id', $id)->delete();

        if($phones) {

            // insert
            $this->insertPhones($phones, $id);
        }

        // delete documents if exist
        ContactDocument::where('contact_id', $id)->delete();

        if(isset($documents)) {

            // insert
            $this->insertDocuments($documents, $id);
        }

        // send notifications email
        if(getSetting("enable_email_notification") == 1) {

            if (isset($requestData['assigned_user_id']) && $old_assign_user_id != $requestData['assigned_user_id']) {

                $this->mailer->sendAssignContactEmail("Contact assigned to you", User::find($requestData['assigned_user_id']), $contact);
            }

            // send two emails about the contact update one for the assigned user and one for the super admin

            if($old_contact_status != $requestData['status']) {

                $super_admin = User::where('is_admin', 1)->first();

                if($super_admin->id == $contact->assigned_user_id) {
                    $this->mailer->sendUpdateContactEmail("Contact status update", User::find($contact->assigned_user_id), $contact);
                } else {
                    $this->mailer->sendUpdateContactEmail("Contact status update", User::find($contact->assigned_user_id), $contact);

                    $this->mailer->sendUpdateContactEmail("Contact status update", $super_admin, $contact);
                }
            }
        }

        return redirect('admin/contacts')->with('flash_message', 'Contact updated!');
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
        $contact = Contact::find($id);

        Contact::destroy($id);
        
        if(getSetting("enable_email_notification") == 1) {
             $this->mailer->sendDeleteContactEmail("Contact deleted", User::find($contact->assigned_user_id), $contact);
        }

        return redirect('admin/contacts')->with('flash_message', 'Contact deleted!');
    }


    public function getAssignContact($id)
    {
        $contact = Contact::find($id);

        $users = User::where('id', '!=', $contact->assigned_user_id)->get();

        return view('pages.contacts.assign', compact('users', 'contact'));
    }


    public function postAssignContact(Request $request, $id)
    {
        $this->validate($request, [
            'assigned_user_id' => 'required'
        ]);

        $contact = Contact::find($id);

        $contact->update(['assigned_user_id' => $request->assigned_user_id]);

        if(getSetting("enable_email_notification") == 1) {
             $this->mailer->sendAssignContactEmail("Contact assigned to you", User::find($request->assigned_user_id), $contact);
        }

        return redirect('admin/contacts')->with('flash_message', 'Contact assigned!');
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
            'first_name' => 'required',
            'last_name' => 'required'
        ]);
    }


    /**
     * insert emails
     *
     *
     * @param $emails
     * @param $contact_id
     */
    protected function insertEmails($emails, $contact_id)
    {
        foreach ($emails as $email) {

            $contactEmail = new ContactEmail();

            $contactEmail->email = $email;

            $contactEmail->contact_id = $contact_id;

            $contactEmail->save();
        }
    }


    /**
     * insert phones
     *
     *
     * @param $phones
     * @param $contact_id
     */
    protected function insertPhones($phones, $contact_id)
    {
        foreach ($phones as $phone) {

            $contactPhone = new ContactPhone();

            $contactPhone->phone = $phone;

            $contactPhone->contact_id = $contact_id;

            $contactPhone->save();
        }
    }


    /**
     * insert documents
     *
     *
     * @param $documents
     * @param $contact_id
     */
    protected function insertDocuments($documents, $contact_id)
    {
        foreach ($documents as $document) {

            $contactDocument = new ContactDocument();

            $contactDocument->document_id = $document;

            $contactDocument->contact_id = $contact_id;

            $contactDocument->save();
        }
    }

    /**
     * get form data for the contacts form
     *
     *
     *
     * @param null $id
     * @return array
     */
    protected function getFormData($id = null)
    {
        $statuses = ContactStatus::all();

        $users = User::where('is_active', 1)->get();

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

            return [$statuses, $users, $documents];
        }

        $contact = Contact::findOrFail($id);

        $selected_documents = $contact->documents()->pluck('document_id')->toArray();

        return [$statuses, $users, $documents, $contact, $selected_documents];
    }

    /**
     * get Contacts By Status
     *
     *
     * @param Request $request
     * @return array
     */
    public function getContactsByStatus(Request $request)
    {
        if(!$request->status)
            return [];


        $contacts = Contact::where('contact_status.name', $request->status)
            ->join('contact_status', 'contact_status.id', '=', 'contact.status');

        if(Auth::user()->is_admin == 1) {

            return $contacts->get();
        }

        return $contacts->where(function ($query) {
            $query->where('assigned_user_id', Auth::user()->id)
                  ->orWhere('created_by_id', Auth::user()->id);
        })->get();
    }

}