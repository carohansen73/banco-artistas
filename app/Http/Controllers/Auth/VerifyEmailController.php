<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectSegunRol($request->user());
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $this->redirectSegunRol($request->user());
    }

    private function redirectSegunRol($user): RedirectResponse
    {
        if ($user->hasAnyRole(['admin', 'super-admin'])) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->artistas()->exists()) {
            return redirect()->route('artista.mis-perfiles');
        }

        return redirect()->route('artista.create');
    }
}
