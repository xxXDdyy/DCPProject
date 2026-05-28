<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\UserAccounts;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    private function normalizeRole(?string $role): string
    {
        $role = strtolower(trim((string) $role));

        return $role === 'employee' ? 'admin' : $role;
    }

    private function roleHomeUrl(string $role): string
    {
        $role = $this->normalizeRole($role);

        return match ($role) {
            'student' => route('studentPortalRoute'),
            'teacher' => route('teacherPortalRoute'),
            default => route('admin.dashboard'),
        };
    }

    private function redirectToRoleHome(string $role)
    {
        return redirect($this->roleHomeUrl($role));
    }

    private function ajaxRedirect(Request $request, string $url, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'redirect' => $url,
                'message' => $message,
            ]);
        }

        return redirect($url)->with('msg', $message);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function showLoginForm(Request $request)
    {
        if ($request->session()->has('user_id')) {
            $role = $this->normalizeRole((string) $request->session()->get('role'));
            $request->session()->put('role', $role);

            return $this->redirectToRoleHome($role);
        }

        if ($request->session()->has('password_change_user_id')) {
            return redirect()->route('firstPasswordFormRoute');
        }

        return view('loginPage')->with('msg', session('msg'));
    }

    public function login(Request $request){
        $username = $request->input('username');
        $password = $request->input('password');

        if (empty($username) || empty($password)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Please enter your username and password.',
                    'errors' => [
                        'username' => ['Please enter your username and password.'],
                    ],
                ], 422);
            }

            return view('loginPage')->with('msg', 'Please enter your username and password.');
        }

        $user = UserAccounts::where('username', $username)->first();
        if ($user && Hash::check($password, $user->password)) {
            if ($user->must_change_password) {
                $request->session()->put('password_change_user_id', $user->id);
                $request->session()->put('password_change_username', $user->username);

                if ($request->expectsJson()) {
                    return response()->json([
                        'redirect' => route('firstPasswordFormRoute'),
                        'message' => 'First-time login detected. Please create a new password.',
                    ]);
                }

                return redirect()->route('firstPasswordFormRoute')
                    ->with('msg', 'First-time login detected. Please create a new password.');
            }

            $request->session()->regenerate();
            $request->session()->forget(['password_change_user_id', 'password_change_username']);
            $role = $this->normalizeRole((string) $user->role);
            session(['user_id' => $user->id, 'username' => $user->username, 'role' => $role]);

            if ($request->expectsJson()) {
                return response()->json([
                    'redirect' => $this->roleHomeUrl($role),
                    'message' => 'Login successful!',
                ]);
            }

            return $this->redirectToRoleHome($role)->with('message', 'Login successful!');
        } else {
            // Authentication failed
            $msg = "Failed login attempt for username: $username";
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $msg,
                    'errors' => [
                        'username' => [$msg],
                    ],
                ], 422);
            }

            return view('loginPage')->with('msg', $msg);
        }
    }

    public function showFirstLoginPasswordForm(Request $request)
    {
        if (!$request->session()->has('password_change_user_id')) {
            return redirect()->route('loginRoute')->with('msg', 'Please login first.');
        }

        return view('first_login_password')->with([
            'msg' => session('msg'),
            'username' => $request->session()->get('password_change_username'),
        ]);
    }

    public function updateFirstLoginPassword(Request $request)
    {
        $userId = $request->session()->get('password_change_user_id');

        if (!$userId) {
            if ($request->expectsJson()) {
                return response()->json([
                    'redirect' => route('loginRoute'),
                    'message' => 'Please login first.',
                ], 401);
            }

            return redirect()->route('loginRoute')->with('msg', 'Please login first.');
        }

        $validated = $request->validate([
            'old_password' => 'required|string|max:255',
            'new_password' => 'required|string|min:8|max:255|confirmed',
        ]);

        $user = UserAccounts::find($userId);
        if (!$user) {
            $request->session()->forget(['password_change_user_id', 'password_change_username']);
            if ($request->expectsJson()) {
                return response()->json([
                    'redirect' => route('loginRoute'),
                    'message' => 'Account not found. Please login again.',
                ], 404);
            }

            return redirect()->route('loginRoute')->with('msg', 'Account not found. Please login again.');
        }

        if (!Hash::check($validated['old_password'], $user->password)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Old password is incorrect.',
                    'errors' => [
                        'old_password' => ['Old password is incorrect.'],
                    ],
                ], 422);
            }

            return back()
                ->withErrors(['old_password' => 'Old password is incorrect.'])
                ->withInput($request->except(['old_password', 'new_password', 'new_password_confirmation']));
        }

        if (Hash::check($validated['new_password'], $user->password)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'New password must be different from your current password.',
                    'errors' => [
                        'new_password' => ['New password must be different from your current password.'],
                    ],
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors(['new_password' => 'New password must be different from your current password.']);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->must_change_password = false;
        $user->save();

        $request->session()->forget(['password_change_user_id', 'password_change_username']);

        if ($request->expectsJson()) {
            return response()->json([
                'redirect' => route('loginRoute'),
                'message' => 'Password successfully changed. Please login again.',
            ]);
        }

        return redirect()->route('loginRoute')->with('msg', 'Password successfully changed. Please login again.');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'redirect' => route('loginRoute'),
                'message' => 'You have been logged out successfully.',
            ]);
        }

        return redirect()->route('loginRoute')->with('msg', 'You have been logged out successfully.');
    }
}
