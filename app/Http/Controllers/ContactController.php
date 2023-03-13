<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactFormResponse;
use Form;
use Mail;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    public function index(Request $request)
    {
        //dd(htmlScriptTagJsApi());
        \Asset::js('app-scripts', 'https://www.google.com/recaptcha/api.js');
        $form = Form::make();
        $form
            ->setAction(route('contact-process'))
            ->addText('name', [])
            ->addText('email_address', ['label' => 'Email'])
            ->addText('phone_number', ['label' => 'Phone'])
            ->addTextArea('comment', ['label' => 'Question/Comment'])
            ->addCustom('g-recaptcha-response', ['view' => 'forms.recaptcha', 'label' => false])
            ->addSubmit('Send')
            ->withValues(old())
        ;
        return view('contact-us', compact('form'));
    }

    public function process(ContactRequest $request)
    {
        Mail::send(new ContactFormResponse($request->except('g-recaptcha-response','_method', '_token')));

        if (Mail::failures()) {
            return redirect()->back()->with('error', 'There was an error in sending your request.');
        }

        // otherwise everything is okay ...
        return redirect()->back()->with('success', 'Thank you for contacting us. We\'ll be in touch shortly.');
    }
}
