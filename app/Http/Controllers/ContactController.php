<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Email;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mailgun\Exception;
use Mailgun\Mailgun;

class ContactController extends Controller
{
    protected $allEmails = [];
    public function createContact(){
        $currentUser = User::find(request('currentUser')['id']);
        try{
            $contact = Contact::create([
                'name' => request('name'),
                'email' => request('email'),
                'created_by' => request('currentUser')['id']
            ]);
            $currentUser->contacts()->attach($contact);
            return $contact;
        }catch (\Exception $e){
            abort(422, "Could not create contact. try again");
        }
    }

    public function getContacts(){
        return User::find(request('userId'))->contacts;
    }

    public function sendMail(Request $request)
    {
        $emails = $request['email'];
        foreach ($emails as $email) {
            array_push($this->allEmails, $email['email']);
        }
        $to = implode(";", $this->allEmails);
        $mg = Mailgun::create(getenv('MAIL_GUN_APIKEY'));

        try {
            $mg->messages()->send('sandboxa3272ee9fa4445df9d751dc5680faa2a.mailgun.org', [
                'from' => getenv('MAIL_FROM_ADDRESS'),
                'to' => $to,
                'subject' => $request['subject'],
                'attachment' => array(
                    array(
                        'filePath' => base_path().'/'.$request['attachment'],
                        'filename' => $request['attachment']
                    )
                ),
                'text' => $request['body'],
            ]);
        } catch (Exception $e) {
            return 'couldn\'t send email' . $e->getMessage();
        }
        $saveEmail = Email::create([
            'from' => getenv('MAIL_FROM_ADDRESS'),
            'to' => $to,
            'subject' => $request['subject'],
            'body' => $request['body'],
            'attachments' => $request['attachment'],
            'status' => 'sent'
        ]);
        $currentUser = User::find(request('user'));
        $currentUser->emails()->attach($saveEmail);

        return 'Email sent successfully';
    }

    public function showAllEmails(){
        return view('mails', ['mails' => User::find(Auth::user()->id)->emails]);
    }
}
