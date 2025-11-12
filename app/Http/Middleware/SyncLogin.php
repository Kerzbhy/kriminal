<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserSession;
use Illuminate\Support\Facades\Auth;

class SyncLogin
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('sync_token');

        if ($token && !Auth::check()) {
            $session = UserSession::where('session_token', $token)->first();
            if ($session) {
                Auth::loginUsingId($session->user_id);
            }
        }

        return $next($request);
    }
}
