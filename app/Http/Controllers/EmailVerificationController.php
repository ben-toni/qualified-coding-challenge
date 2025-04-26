<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function show()
    {
        return inertia('EmailVerification/Show');
    }

    public function store(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('home');
    }

    public function update(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        session()->flash('success', __('account.verification-resent'));

        return back();
    }
}
