<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ReCaptcha\ReCaptcha;
use Redirect;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{

    public function contact(){
        return view('contact.contact');
    }

    public function send_contact(Request $request){

        $request->flash();

        $this->validate($request, [
            'email' => 'required|email',
            'title' => 'required|string',
            'message' => 'required|string'
        ]);

        $recaptcha = new ReCaptcha(env('GOOGLE_RECAPTCHA_SECRET_KEY'));
        $response = $recaptcha->verify($request->get('g-recaptcha-response'), $_SERVER['REMOTE_ADDR']);

        if ($response->isSuccess()) {
                
            $data = [
                'email'=>$request->email,
                'title'=>$request->title,
                'message'=>$request->message
            ];
            
            Mail::to(env('ALTERNATIVE_MAIL_CONTACT_ADDRESS'))->send(new SendMail($data));
            
            $request->flush();

            return back()->with('success','Thank you for contacting us');
                
        }else{
            return back()->withErrors(['Please Complete The ReCaptcha']);
        }

    }

}
