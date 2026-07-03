<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if ($request->user()->hasVerifiedEmail()) {
            if ($request->user()->hasAnyRole(['admin', 'super-admin'])) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('artista.mis-perfiles');
        }

        return view('auth.verify-email');
    }
}
