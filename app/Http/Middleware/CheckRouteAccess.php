<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRouteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle(Request $request, Closure $next): Response
    {
        $protectedRoutes = [
        
        'admin',
        'diaries.index',
        'diaries.create',
        'diaries.show',
        'diaries.edit',
        'diaries.update',
        'diaries.store',
        'diaries.destroy',
        'documentations.index',
        'documentations.create',
        'documentations.store',
        'documentations.show',
        'documentations.edit',
        'documentations.update',
        'documentations.destroy',
        'approval_request.index',
        'approval_request.create',
        'approval_request.store',
        'approval_request.show',
        'approval_request.edit',
        'approval_request.update',
        'approval_request.destroy',
        'users.index',
        'users.create',
        'users.store',
        'users.show',
        'users.edit',
        'users.update',
        'users.destroy',
        'profile.index',
        'profile.update',
        
        ];
        $currentRouteName = $request->route()->getName();
        
        $isProtectedRoute = in_array($currentRouteName, $protectedRoutes);
        // dd($currentRouteName, $isProtectedRoute, Auth::check());

        if ($isProtectedRoute && !Auth::check()) {
            return redirect()->route('not-authorize');
        }

        if (Auth::check()) {
            $user = Auth::user();
            $allowedRoles = [];
            // dd($currentRouteName, $user->role);
            if ($currentRouteName === 'admin' || (in_array($currentRouteName,[
                'profile.index',
                'profile.update',
                'diaries.index',
                'diaries.create',
                'diaries.show',
                'diaries.edit',
                'diaries.store',
                'diaries.destroy',
                'diaries.update',
                'documentations.index',
                'documentations.create',
                'documentations.store',
                'documentations.show',
                'documentations.edit',
                'documentations.update',
                'documentations.destroy',
            ]))) {
                $allowedRoles = [1, 2, 3];
            } elseif (in_array($currentRouteName, [                    
                    'approval_request.index',
                    'approval_request.create',
                    'approval_request.store',
                    'approval_request.show',
                    'approval_request.edit',
                    'approval_request.update',
                    'approval_request.destroy',
                ])) {
                $allowedRoles = [1, 2];
            } elseif (in_array($currentRouteName,[
                    'users.index',
                    'users.create',
                    'users.store',
                    'users.show',
                    'users.edit',
                    'users.update',
                    'users.destroy',
            ])) {
                $allowedRoles = [1];
            }
            // Check if the user's role is authorized to access the route
            if (!in_array($user->role, $allowedRoles)) {
                return redirect()->route('not-authorize');
            }
        }

        return $next($request);
    }
}