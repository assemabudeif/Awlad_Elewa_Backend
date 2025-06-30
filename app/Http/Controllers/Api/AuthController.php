<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone1' => ['required', 'string', 'max:20'],
            'phone2' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('mobile')->plainTextToken;

        // إرسال إشعار ترحيبي (سيتم إرساله عند تحديث FCM token)
        // $this->notificationService->sendWelcomeNotification($user);

        return response(['user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone1';
        $user = User::where($loginField, $request->login)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = $user->createToken('mobile')->plainTextToken;
        return response(['user' => $user, 'token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        try {
            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['message' => 'Password reset link sent']);
            } else {
                return response()->json(['message' => 'Unable to send reset link'], 400);
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Password reset email error: ' . $e->getMessage());

            // Check if it's a Mailtrap domain restriction error
            if (str_contains($e->getMessage(), 'Sending from domain') && str_contains($e->getMessage(), 'is not allowed')) {
                return response()->json([
                    'message' => 'Password reset link generated but email configuration needs to be updated. Please contact support.',
                    'error' => 'Email configuration issue'
                ], 500);
            }

            return response()->json([
                'message' => 'Unable to send reset link. Please try again later.',
                'error' => config('app.debug') ? $e->getMessage() : 'Email service error'
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $user = User::where('email', Auth::user()->email)->first();

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'Password reset successful',
        ], 200);
    }
}
