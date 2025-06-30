<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class ValidateResetToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Reset token is required'], 400);
        }

        // Find the password reset record
        $resetRecord = DB::table('password_reset_tokens')
            ->where('token', hash('sha256', $token))
            ->first();

        if (!$resetRecord) {
            return response()->json(['message' => 'Invalid or expired reset token'], 400);
        }

        // Check if token is expired (24 hours)
        if (now()->diffInHours($resetRecord->created_at) > 24) {
            DB::table('password_reset_tokens')->where('token', hash('sha256', $token))->delete();
            return response()->json(['message' => 'Reset token has expired'], 400);
        }

        // Find user by email
        $user = User::where('email', $resetRecord->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Attach user to request for use in controller
        $request->merge(['reset_user' => $user]);
        $request->merge(['reset_token' => $token]);

        return $next($request);
    }
}
