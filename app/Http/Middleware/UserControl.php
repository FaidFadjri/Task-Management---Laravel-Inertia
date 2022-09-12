<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserControl
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->get('user')) {
            return redirect()->to('/login');
        }
        return $next($request);
    }
}
