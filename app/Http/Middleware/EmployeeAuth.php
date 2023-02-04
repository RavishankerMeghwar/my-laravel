<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Models\User;
use Closure;


class EmployeeAuth extends Controller
{
	public function handle($request, Closure $next)
	{
		$user = request()->user;
		if ($user && $user->role == User::ROLE_EMPLOYEE) {
			return $next($request);
		}
		return api_error('You are unauthorized!', 401);
	}
}
