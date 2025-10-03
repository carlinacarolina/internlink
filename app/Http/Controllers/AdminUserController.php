<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    private const SEARCH_COLUMNS = ['name', 'email', 'phone'];
    private const BASE_SELECT = ['id', 'name', 'email', 'phone', 'email_verified_at', 'role', 'created_at', 'updated_at'];
    private const DISPLAY_COLUMNS = ['id', 'name', 'email', 'phone', 'email_verified_at'];

    public function index(Request $request)
    {
        $role = session('role');
        if (!in_array($role, ['admin', 'developer'], true)) {
            abort(403);
        }

        $schoolId = $this->currentSchoolId();
        $sessionSchoolId = session('school_id');

        $query = User::query()->select(self::BASE_SELECT);

        if ($role === 'admin') {
            if (!$sessionSchoolId || ($schoolId && (int) $schoolId !== (int) $sessionSchoolId)) {
                abort(403);
            }

            $query->where('id', session('user_id'))
                ->where('role', 'admin')
                ->where('school_id', $sessionSchoolId);
        } else {
            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }
            $query->where('role', 'admin');
        }

        $filters = [];

        $name = trim($request->query('name', ''));
        if ($name !== '') {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($name) . '%']);
            $filters[] = [
                'param' => 'name',
                'label' => 'Name: ' . $name,
            ];
        }

        $email = trim($request->query('email', ''));
        if ($email !== '') {
            $query->whereRaw('LOWER(email) LIKE ?', ['%' . strtolower($email) . '%']);
            $filters[] = [
                'param' => 'email',
                'label' => 'Email: ' . $email,
            ];
        }

        $phone = trim($request->query('phone', ''));
        if ($phone !== '') {
            $query->whereRaw("LOWER(COALESCE(phone, '')) LIKE ?", ['%' . strtolower($phone) . '%']);
            $filters[] = [
                'param' => 'phone',
                'label' => 'Phone: ' . $phone,
            ];
        }

        $emailVerified = $request->query('email_verified');
        if (in_array($emailVerified, ['true', 'false'], true)) {
            if ($emailVerified === 'true') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
            $filters[] = [
                'param' => 'email_verified',
                'label' => 'Is Email Verified?: ' . ucfirst($emailVerified),
            ];
        }

        $verifiedDate = $request->query('email_verified_at');
        if ($verifiedDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $verifiedDate)) {
            $query->whereDate('email_verified_at', $verifiedDate);
            $filters[] = [
                'param' => 'email_verified_at',
                'label' => 'Email Verified At: ' . $verifiedDate,
            ];
        }

        if ($q = trim($request->query('q', ''))) {
            $qLower = strtolower($q);
            $query->where(function ($sub) use ($qLower) {
                foreach (self::SEARCH_COLUMNS as $col) {
                    $sub->orWhereRaw('LOWER(COALESCE(' . $col . ", '')) LIKE ?", ['%' . $qLower . '%']);
                }
            });
        }

        $admins = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('admin.index', [
            'admins' => $admins,
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        if (session('role') !== 'developer') {
            abort(403);
        }
        abort_if(!$this->currentSchoolId(), 404, 'School context missing.');
        return view('admin.create');
    }

    public function store(Request $request)
    {
        if (session('role') !== 'developer') {
            abort(403);
        }
        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 400, 'School context required.');
        $data = $request->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'phone' => 'nullable|string',
            'password' => 'required|string',
        ]);
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => 'admin',
            'school_id' => $schoolId,
        ]);
        return redirect($this->schoolRoute('admins'))->with('status', 'Admin created.');
    }

    public function show($school, $id)
    {
        $role = session('role');
        $schoolId = $this->currentSchoolId();
        $query = User::query()
            ->select(self::DISPLAY_COLUMNS)
            ->where('role', 'admin')
            ->where('id', $id);

        if ($role === 'admin') {
            if ((int) $id !== (int) session('user_id')) {
                abort(403);
            }
            $query->where('school_id', session('school_id'));
        } elseif ($role === 'developer') {
            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }
        } else {
            abort(403);
        }
        $admin = $query->firstOrFail();
        return view('admin.show', compact('admin'));
    }

    public function edit($school, $id)
    {
        $role = session('role');
        $schoolId = $this->currentSchoolId();

        $query = User::query()->where('role', 'admin')->where('id', $id);

        if ($role === 'admin') {
            if ((int) $id !== (int) session('user_id')) {
                abort(403);
            }
            $query->where('school_id', session('school_id'));
        } elseif ($role === 'developer') {
            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }
        } else {
            abort(403);
        }
        $admin = $query->firstOrFail();
        return view('admin.edit', compact('admin'));
    }

    public function update(Request $request, $school, $id)
    {
        $role = session('role');
        $schoolId = $this->currentSchoolId();

        $query = User::query()->where('role', 'admin')->where('id', $id);

        if ($role === 'admin') {
            if ((int) $id !== (int) session('user_id')) {
                abort(403);
            }
            $query->where('school_id', session('school_id'));
        } elseif ($role === 'developer') {
            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }
        } else {
            abort(403);
        }

        $admin = $query->firstOrFail();
        if ($request->has('role')) {
            return back()->withErrors(['role' => 'Role modification is not allowed.'])->withInput();
        }

        $targetSchoolId = $schoolId ?? $admin->school_id;
        $data = $request->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->ignore($admin->id)
                    ->where(fn ($q) => $q->where('school_id', $targetSchoolId)),
            ],
            'phone' => 'nullable|string',
            'password' => 'nullable|string',
        ]);
        $admin->name = $data['name'];
        $admin->email = $data['email'];
        $admin->phone = $data['phone'] ?? null;
        if (!empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }
        $admin->save();
        return redirect($this->schoolRoute('admins'))->with('status', 'Admin updated.');
    }

    public function destroy($school, $id)
    {
        $role = session('role');
        $schoolId = $this->currentSchoolId();
        $sessionSchoolId = session('school_id');

        $query = User::query()->where('role', 'admin')->where('id', $id);
        $scopeSchoolId = null;

        if ($role === 'admin') {
            if ((int) $id !== (int) session('user_id')) {
                abort(403);
            }
            $scopeSchoolId = $sessionSchoolId;
            $query->where('school_id', $sessionSchoolId);
        } elseif ($role === 'developer') {
            $scopeSchoolId = $schoolId;
            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }
        } else {
            abort(403);
        }

        $admin = $query->firstOrFail();
        $effectiveSchoolId = $scopeSchoolId ?? $admin->school_id;

        $adminCount = User::where('role', 'admin')
            ->when($effectiveSchoolId, fn ($q) => $q->where('school_id', $effectiveSchoolId))
            ->count();

        if ($adminCount <= 1) {
            return back()->withErrors(['error' => 'Cannot delete the last admin account.']);
        }

        $admin->delete();

        if ($role === 'admin') {
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/login');
        }

        return redirect($this->schoolRoute('admins'))->with('status', 'Admin deleted.');
    }
}
