<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Models\User;
use Closure;


class ManagerAuth extends Controller
{
	public function handle($request, Closure $next)
	{
		$user = request()->user;
		if ($user && $user->role == User::ROLE_MANAGER || $user && $user->role == User::ROLE_STOCK_MANAGER || $user && $user->role == User::ROLE_PLUMBER ) {
			return $next($request);
		}
		return api_error('You are unauthorized!', 401);
	}
}
