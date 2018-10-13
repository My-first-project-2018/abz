<?php declare( strict_types = 1 );

namespace App\Http\Middleware;

use Closure;

/**
 * Class Cors
 *
 * @package App\Http\Middleware
 */
class Cors
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
        return $next($request)->header('Access-Control-Allow-Origin', '*')
                              ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

    }
}
