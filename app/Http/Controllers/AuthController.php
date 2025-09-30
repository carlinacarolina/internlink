<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Student;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $step = session('register.step', 1);
        $data = session('register.data', []);
        $extra = session('register.extra', []);

        if ($request->isMethod('post')) {
            if ($step === 1) {
                $validated = $request->validate([
                    'name' => 'required|string',
                    'email' => 'required|email',
                    'password' => 'required|min:8',
                    'phone' => 'required|numeric',
                    'role' => 'required|in:student,supervisor',
                    'school_code' => 'required|string',
                ]);

                $school = School::whereRaw('LOWER(code) = ?', [strtolower(trim($validated['school_code']))])->first();
                if (!$school) {
                    return back()->withErrors(['school_code' => 'Invalid school code.'])->withInput();
                }

                $emailExists = User::where('email', $validated['email'])
                    ->where('school_id', $school->id)
                    ->exists();

                if ($emailExists) {
                    return back()->withErrors(['email' => 'Email already registered for this school.'])->withInput();
                }

                $validated['school_id'] = $school->id;
                $validated['school_code'] = $school->code;
                $validated['school_name'] = $school->name;

                session([
                    'register.step' => 2,
                    'register.data' => $validated,
                ]);

                return redirect()->route('signup');
            }

            $data = session('register.data');
            if (!$data) {
                return redirect()->route('signup');
            }

            if ($request->has('back')) {
                if ($data['role'] === 'student') {
                    $extraInput = $request->only(['student_number', 'national_sn', 'major', 'batch', 'photo']);
                } else {
                    $extraInput = $request->only(['supervisor_number', 'department', 'photo']);

                }
                session(['register.step' => 1, 'register.data' => $data, 'register.extra' => $extraInput]);
                return redirect()->route('signup');
            }

            if ($data['role'] === 'student') {
                $validated = $request->validate([
                    'student_number' => 'required|numeric',
                    'national_sn' => 'required|numeric',
                    'major' => 'required|string',
                    'batch' => 'required|date_format:Y',
                    'photo' => 'nullable|url',
                ]);
            } else {
                $validated = $request->validate([
                    'supervisor_number' => 'required|string|max:64|regex:/^[A-Za-z0-9_-]+$/',
                    'department' => 'required|string',
                    'photo' => 'required|url',

                ]);
            }

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'phone' => $data['phone'],
                'role' => $data['role'],
                'school_id' => $data['school_id'],
            ]);

            if ($data['role'] === 'student') {
                Student::create([
                    'user_id' => $user->id,
                    'school_id' => $data['school_id'],
                    'student_number' => $validated['student_number'],
                    'national_sn' => $validated['national_sn'],
                    'major' => $validated['major'],
                    'batch' => $validated['batch'],
                    'photo' => $validated['photo'] ?? null,
                ]);
            } else {
                Supervisor::create([
                    'user_id' => $user->id,
                    'school_id' => $data['school_id'],
                    'supervisor_number' => $validated['supervisor_number'],
                    'department' => $validated['department'],
                    'photo' => $validated['photo'],
                ]);
            }

            session()->forget('register');
            session([
                'user_id' => $user->id,
                'role' => $user->role,
                'school_id' => $user->school_id,
                'school_code' => $data['school_code'] ?? null,
            ]);

            return redirect('/');
        }

        return view('auth.register', [
            'step' => $step,
            'data' => $data,
            'extra' => $extra,
            'schoolName' => $data['school_name'] ?? null,
        ]);
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            $user = User::where('email', $credentials['email'])->first();

            if ($user && Hash::check($credentials['password'], $user->password)) {
                $schoolId = null;
                $schoolCodeSession = null;

                if ($user->role !== 'developer') {
                    $school = $user->school;

                    if (!$school) {
                        return back()->withErrors([
                            'email' => 'Account not linked to a school. Contact your administrator.',
                        ])->onlyInput('email');
                    }

                    $schoolId = $school->id;
                    $schoolCodeSession = $school->code;
                }

                session([
                    'user_id' => $user->id,
                    'role' => $user->role,
                    'school_id' => $schoolId,
                    'school_code' => $schoolCodeSession,
                ]);
                $request->session()->regenerate();
                return redirect('/');
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        return view('auth.login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('status', 'Anda telah keluar.');
    }
}
