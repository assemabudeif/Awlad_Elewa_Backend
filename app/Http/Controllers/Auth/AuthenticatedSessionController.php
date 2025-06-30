<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): Response|RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Check if this is an API request or web request
        if ($request->expectsJson() || $request->is('api/*')) {
            // For mobile API: return user info and token
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $token = $user->createToken('mobile')->plainTextToken;
            return response([
                'user' => $user,
                'token' => $token,
            ], 200);
        }

        // For web login: redirect to admin dashboard
        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'تم تسجيل الخروج بنجاح');
    }

    public function create()
    {
        return view('auth.login');
    }
}
