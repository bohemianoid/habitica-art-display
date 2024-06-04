<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAppIsReady
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (User::all()->isEmpty()) {
            return redirect('register');
        }

        if ($request->user()) {
            $required = collect([
                $request->user()->habitica_user_id,
                $request->user()->habitica_api_token,
                $request->user()->openai_api_key,
            ]);

            if ($required->count() != $required->filter()->count()) {
                return redirect('profile');
            }
        }

        return $next($request);
    }
}
