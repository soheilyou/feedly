<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InternalServiceAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $authorizationHeader = $request->header("Authorization");
        try {
            $token = explode(" ", $authorizationHeader)[1];
            if ($token != config("internal_service.token")) {
                $this->reject();
            }
        } catch (Exception $exception) {
            $this->reject();
        }
        return $next($request);
    }
}
