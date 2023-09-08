<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use Symfony\Component\HttpFoundation\Response;

class Administrator
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle(Request $request, Closure $next, $permission = null)
    {
        if (Auth::check() && Auth::user()->is_admin == 1) {
            return $next($request);
        } else {
            if ($permission != null && ($permission_parts = explode("|", $permission))) {
                $action_permission_array = array_filter($permission_parts, function ($value) use ($request) {
                    return ($parts = explode("-", $value)) && $parts[0] == $request->route()->getActionMethod();
                });

                if ($action_permission_array && count($action_permission_array)) {
                    $action_permission = array_pop($action_permission_array);
                    $parts = explode("-", $action_permission);

                    if (isset($parts[1]) && Auth::user()->can($parts[1])) {
                        return $next($request);
                    }
                }
            }
        }

        return redirect('/admin/forbidden');
    }
}
