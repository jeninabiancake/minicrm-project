<?php

use App\Models\Setting;
use App\Models\Mailbox;
use App\Models\MailboxFolder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * upload file
 *
 *
 * @param $request
 * @param $name
 * @param string $destination
 * @return string
 */
function uploadFile($request, $name, $destination = '')
{
    $image = $request->file($name);

    $name = time().'.'.$image->getClientOriginalExtension();

    if($destination == '') {
        $destination = public_path('/uploads');
    }

    $image->move($destination, $name);

    return $name;
}


/**
 * add setting key and value
 *
 *
 * @param $key
 * @param $value
 * @return Setting|bool
 */
function addSetting($key, $value)
{
    if(Setting::where('setting_key', $key)->first() != null)
        return false;

    $setting = new Setting();

    $setting->setting_key = $key;

    $setting->setting_value = $value;

    $setting->save();

    return $setting;
}

/**
 * get setting value by key
 *
 * @param $key
 * @return mixed
 */
function getSetting($key)
{
    return ($setting = Setting::where('setting_key', $key)->first()) != null ? $setting->setting_value:null;
}

/**
 * check directory exists and try to create it
 *
 *
 * @param $directory
 */
function checkDirectory($directory)
{
    try {
        if (!file_exists(public_path('uploads/' . $directory))) {
            mkdir(public_path('uploads/' . $directory));

            chmod(public_path('uploads/' . $directory), 0777);
        }
    } catch (\Exception $e) {
        die($e->getMessage());
    }
}

/**
 * check if user has permission
 *
 *
 * @param $permission
 * @return bool
 */
function user_can($permission)
{
    // Check if the user is an admin
    if (Auth::user()->is_admin == 1) {
        return true;
    }

    // Use Laravel's Gate system to check the user's permission
    return Gate::allows($permission);
}

/**
 * get Unread Messages
 *
 *
 * @return mixed
 */
// function getUnreadMessages()
// {
//     $folder = \App\Models\MailboxFolder::where('title', "Inbox")->first();

//     $messages = \App\Models\Mailbox::join('mailbox_receiver', 'mailbox_receiver.mailbox_id', '=', 'mailbox.id')
//         ->join('mailbox_user_folder', 'mailbox_user_folder.user_id', '=', 'mailbox_receiver.receiver_id')
//         ->join('mailbox_flags', 'mailbox_flags.user_id', '=', 'mailbox_user_folder.user_id')
//         ->where('mailbox_receiver.receiver_id', \Auth::user()->id)
// //                          ->where('parent_id', 0)
//         ->where('mailbox_flags.is_unread', 1)
//         ->where('mailbox_user_folder.folder_id', $folder->id)
//         ->where('sender_id', '!=', \Auth::user()->id)
//         ->whereRaw('mailbox.id=mailbox_receiver.mailbox_id')
//         ->whereRaw('mailbox.id=mailbox_flags.mailbox_id')
//         ->whereRaw('mailbox.id=mailbox_user_folder.mailbox_id')
//         ->select(["*", "mailbox.id as id"])
//         ->get();

//     return $messages;
// }

function getUnreadMessages()
{
    // Get the folder with the title "Inbox"
    $folder = MailboxFolder::where('title', 'Inbox')->firstOrFail();

    // Get the unread messages
    $messages = Mailbox::join('mailbox_receiver', 'mailbox_receiver.mailbox_id', '=', 'mailbox.id')
        ->join('mailbox_user_folder', 'mailbox_user_folder.user_id', '=', 'mailbox_receiver.receiver_id')
        ->join('mailbox_flags', 'mailbox_flags.user_id', '=', 'mailbox_user_folder.user_id')
        ->where('mailbox_receiver.receiver_id', Auth::id())
        ->where('mailbox_flags.is_unread', 1)
        ->where('mailbox_user_folder.folder_id', $folder->id)
        ->where('sender_id', '!=', Auth::id())
        ->select('mailbox.id') // Replace with the columns you want to select
        ->get();

    return $messages;
}

/**
 * getContacts
 *
 *
 * @param null $status
 * @return \Illuminate\Database\Eloquent\Collection|static[]
 */
function getContacts($status = null)
{
    if(!$status)
        return \App\Models\Contact::all();

    return \App\Models\Contact::join('contact_status', 'contact_status.id', '=', 'contact.status')
        ->where('contact_status.name', $status)
        ->get();

}


/**
 * Get active users who are not admins.
 *
 * @return \Illuminate\Database\Eloquent\Collection
 */
function getUsers()
{
    return User::where('is_admin', false)
        ->where('is_active', true)
        ->get();
}