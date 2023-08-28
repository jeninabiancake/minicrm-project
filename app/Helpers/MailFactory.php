<?php

namespace App\Helpers;


use Illuminate\Contracts\Mail\Mailer;

class MailerFactory
{
    protected $mailer;
    protected $fromAddress = '';
    protected $fromName = 'Mini CRM';


    /**
     * MailerFactory constructor.
     *
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;

        $this->fromAddress = getSetting("crm_email");
    }


    /**
     * sendActivateBannedEmail
     *
     *
     * @param $subject
     * @param $user
     */
    public function sendActivateBannedEmail($subject, $user)
    {
        try {
        $this->mailer->send("emails.activate_banned", ['user' => $user, 'subject' => $subject], function($message) use ($subject, $user) {

            $message->from($this->fromAddress, $this->fromName)
                ->to($user->email)->subject($subject);

        });
        } catch (\Exception $ex) {
            die("Mailer Factory error: " . $ex->getMessage());
        }
    }

    /**
     * sendUpdateRoleEmail
     *
     *
     * @param $subject
     * @param $user
     */
    public function sendUpdateRoleEmail($subject, $user)
    {
        try {
        $this->mailer->send("emails.update_role", ['user' => $user, 'subject' => $subject], function($message) use ($subject, $user) {

            $message->from($this->fromAddress, $this->fromName)
                ->to($user->email)->subject($subject);

        });

        } catch (\Exception $ex) {
            die("Mailer Factory error: " . $ex->getMessage());
        }
    }

    public function sendAssignDocumentEmail($subject, $user, $document)
    {
        try {
            $this->mailer->send("emails.assign_document", ['user' => $user, 'document' => $document, 'subject' => $subject], function($message) use ($subject, $user) {

                $message->from($this->fromAddress, $this->fromName)
                    ->to($user->email)->subject($subject);

            });
        } catch (\Exception $ex) {
            die("Mailer Factory error: " . $ex->getMessage());
        }
    }

         /**
     * send Assign Contact Email
     *
     *
     * @param $subject
     * @param $user
     * @param $contact
     */
    public function sendAssignContactEmail($subject, $user, $contact)
    {
        try {
            $this->mailer->send("emails.assign_contact", ['user' => $user, 'contact' => $contact, 'subject' => $subject], function($message) use ($subject, $user) {

                $message->from($this->fromAddress, $this->fromName)
                    ->to($user->email)->subject($subject);

            });
        } catch (\Exception $ex) {
            die("Mailer Factory error: " . $ex->getMessage());
        }
    }


    /**
     * send Update Contact Email
     *
     *
     * @param $subject
     * @param $user
     * @param $contact
     */
    public function sendUpdateContactEmail($subject, $user, $contact)
    {
        try {
            $this->mailer->send("emails.update_contact", ['user' => $user, 'contact' => $contact, 'subject' => $subject], function($message) use ($subject, $user) {

                $message->from($this->fromAddress, $this->fromName)
                    ->to($user->email)->subject($subject);

            });
        } catch (\Exception $ex) {
            die("Mailer Factory error: " . $ex->getMessage());
        }
    }


    /**
     * send Delete Contact Email
     *
     *
     * @param $subject
     * @param $user
     * @param $contact
     */
    public function sendDeleteContactEmail($subject, $user, $contact)
    {
        try {
            $this->mailer->send("emails.delete_contact", ['user' => $user, 'contact' => $contact, 'subject' => $subject], function($message) use ($subject, $user) {

                $message->from($this->fromAddress, $this->fromName)
                    ->to($user->email)->subject($subject);

            });
        } catch (\Exception $ex) {
            die("Mailer Factory error: " . $ex->getMessage());
        }
    }

    /**
     * send Assign Task Email
     *
     *
     * @param $subject
     * @param $user
     * @param $task
     */
    public function sendAssignTaskEmail($subject, $user, $task)
    {
        try {
            $this->mailer->send("emails.assign_task", ['user' => $user, 'task' => $task, 'subject' => $subject], function($message) use ($subject, $user) {

                $message->from($this->fromAddress, $this->fromName)
                    ->to($user->email)->subject($subject);

            });
        } catch (\Exception $ex) {
            die("Mailer Factory error: " . $ex->getMessage());
        }
    }


    /**
     * send Update Task Status Email
     *
     *
     * @param $subject
     * @param $user
     * @param $contact
     */
    public function sendUpdateTaskStatusEmail($subject, $user, $task)
    {
        try {
            $this->mailer->send("emails.update_task_status", ['user' => $user, 'task' => $task, 'subject' => $subject], function($message) use ($subject, $user) {

                $message->from($this->fromAddress, $this->fromName)
                    ->to($user->email)->subject($subject);

            });
        } catch (\Exception $ex) {
            die("Mailer Factory error: " . $ex->getMessage());
        }
    }


    /**
     * send Delete Task Email
     *
     *
     * @param $subject
     * @param $user
     * @param $task
     */
    public function sendDeleteTaskEmail($subject, $user, $task)
    {
        try {
            $this->mailer->send("emails.delete_task", ['user' => $user, 'task' => $task, 'subject' => $subject], function($message) use ($subject, $user) {

                $message->from($this->fromAddress, $this->fromName)
                    ->to($user->email)->subject($subject);

            });
        } catch (\Exception $ex) {
            die("Mailer Factory error: " . $ex->getMessage());
        }
    }
}