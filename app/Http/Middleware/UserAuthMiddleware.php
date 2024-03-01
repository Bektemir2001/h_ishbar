<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::where('id', $request->header('user_id'))->first();
        if($user)
        {
            config(['app.user' => $request->header('user_id')]);
            return $next($request);
        }
        return \response(['message' => 'unauthorized'])->setStatusCode(401);
    }
}
