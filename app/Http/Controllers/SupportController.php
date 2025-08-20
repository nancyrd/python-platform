<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Mail; // when ready to email
// use App\Mail\SupportRequestMail;

class SupportController extends Controller
{
    public function submit(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required','string','max:120'],
            'email'   => ['required','email','max:150'],
            'subject' => ['required','string','max:150'],
            'message' => ['required','string','max:5000'],
        ]);

        // Example: log it (replace with Mail::to(...)->send(new SupportRequestMail($data));)
        Log::info('Support request', $data);

        return back()->with('status', 'Thanks! Your message has been sent. We’ll get back to you soon.');
    }
}
