<?php

namespace App\Http\Middleware;

use Closure;

class CORS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        header("Access-Control-Allow-Origin: ".$_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Methods: "OPTIONS, GET, POST"');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: "Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"');
        header('Access-Control-Max-Age: "3600"');
        return $next($request);
    }
}
