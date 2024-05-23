<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => [trans('The email address is not registered.')],
            ]);
        }

        $secretKey = Str::random(16);
        $user->update(['secret_key' => $secretKey]);

        Mail::send('emails.password_reset', ['secretKey' => $secretKey], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Your Password Reset Key');
        });

        return back()->with('status', 'We have emailed your password reset key!');
    }

    /**
     * Verify the secret key and reset the password.
     */
    public function verifySecretKey(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'secret_key' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->where('secret_key', $request->secret_key)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'secret_key' => ['The provided secret key is invalid.'],
            ]);
        }

        $user->update(['password' => bcrypt('1234'), 'secret_key' => null]);

        return redirect()->route('login')->with('status', 'Your password has been reset to 1234. Please log in and change your password.');
    }
}
