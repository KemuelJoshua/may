<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'message' => 'required|string'
        ]);

        try {
            $details = [
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => $request->message
            ];

            Mail::to($request->email)->send(new SendMail($details));

            return response()->json(['message' => 'Your message was sent!', 'status' => 'success']);
        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'sending message failed'], 500);
        }
    }
}
