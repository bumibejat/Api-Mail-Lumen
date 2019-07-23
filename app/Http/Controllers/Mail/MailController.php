<?php


namespace App\Http\Controllers\Mail;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function testMail ()
    {
        Mail::raw(
            "Nama : Test SMTP \n".
            "Judul : test \n".
            "Author : test \n".
            "Kategori : test \n"
            , function ($message) {
            $message->to('emosekolah@gmail.com');
            $message->from('dentisphere.noreply@hangtuah.ac.id', "Dentisphere");
            $message->subject("Data Form Abstraction Dentisphere");
        });
    }

    public function sendMail (Request $request)
    {
        try {
            Mail::raw(($request->has('message')) ? $request->message:'default', function ($message) use ($request) {
                $message->to(($request->has('to')) ? $request->to:'emosekolah@gmail.com');
                $message->from(($request->has('from')) ? $request->from:env('MAIL_USERNAME')
                    , ($request->has('fromName')) ? $request->fromName:'Website Title');
                $message->subject(($request->has('subject')) ? $request->subject:'Default Subject');
                if ($request->hasFile('attach')) {
                    $message->attach($request->file('attach'), [
                        'as' => ($request->has('title')) ? $request->title:'Abstraction',
                        'mime' => ($request->has('mime')) ? $request->mime:'application/pdf'
                    ]);
                }
            });
        } catch (\Exception $e) {
            $data['status'] = 'error';
            $data['message'] = $e->getMessage();
        }

        $data['status'] = 'success';
        $data['message'] = 'Sending Email Success';

        return response($data);
    }
}