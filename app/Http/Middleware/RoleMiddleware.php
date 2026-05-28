<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    private function normalizeRole(?string $role): string
    {
        $role = strtolower(trim((string) $role));

        return $role === 'employee' ? 'admin' : $role;
    }

    /**
     * Allow access only for the required role.
     */
    public function handle(Request $request, Closure $next, string $requiredRole): Response
    {
        $role = $this->normalizeRole((string) $request->session()->get('role', ''));
        $requiredRole = $this->normalizeRole($requiredRole);

        if ($request->session()->has('role') && $request->session()->get('role') !== $role) {
            $request->session()->put('role', $role);
        }

        if ($role !== $requiredRole) {
            if ($role === 'student') {
                return redirect()->route('studentPortalRoute')
                    ->with('msg', 'Student accounts can only access the student portal.');
            }

            if ($role === 'teacher') {
                return redirect()->route('teacherPortalRoute')
                    ->with('msg', 'Teacher accounts can only access the teacher portal.');
            }

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('message', 'Admin accounts can only access admin pages.');
            }

            return redirect()->route('loginRoute')->with('msg', 'Unauthorized access.');
        }

        return $next($request);
    }
}
