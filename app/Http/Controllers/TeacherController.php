<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\UserAccounts;
use App\Support\TableExport;
use App\Support\TeacherImageStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    private function respond(Request $request, string $route, array|string $parameters = [], string $message = '')
    {
        $url = route($route, $parameters);

        if ($request->expectsJson()) {
            return response()->json([
                'redirect' => $url,
                'message' => $message,
            ]);
        }

        return redirect($url)->with('message', $message);
    }

    public function index()
    {
        $teachers = Teacher::with('userAccount')->paginate();

        return view('teacher')->with('teachers', $teachers);
    }

    public function exportExcel()
    {
        return TableExport::excel(
            'teachers.xls',
            'Teacher List',
            $this->teacherExportHeaders(),
            $this->teacherExportRows()
        );
    }

    public function exportPdf()
    {
        return TableExport::pdf(
            'teachers.pdf',
            'Teacher List',
            $this->teacherExportHeaders(),
            $this->teacherExportRows()
        );
    }

    public function create()
    {
        return view('add_teacher');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fname' => 'required|string|min:2|max:30',
            'mname' => 'nullable|string|max:30',
            'lname' => 'required|string|min:2|max:30',
            'email' => 'required|email|max:255|unique:user_accounts,email',
            'contact_no' => ['required', 'regex:/^[0-9]{11}$/'],
            'username' => 'required|string|min:5|max:30|unique:user_accounts,username',
            'password' => 'required|string|min:8|max:255',
        ]);

        DB::transaction(function () use ($data): void {
            $user = UserAccounts::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'teacher',
                'must_change_password' => true,
            ]);

            Teacher::create([
                'user_account_id' => $user->id,
                'fname' => $data['fname'],
                'mname' => $data['mname'] ?? null,
                'lname' => $data['lname'],
                'email' => $data['email'],
                'contact_no' => $data['contact_no'],
            ]);
        });

        return $this->respond($request, 'admin.teachers.index', [], 'Teacher added successfully!');
    }

    public function show(string $id)
    {
        $teacher = Teacher::with('userAccount')->findOrFail($id);

        return view('showTeacherDetails')->with('teacher', $teacher);
    }

    public function edit(string $id)
    {
        $teacher = Teacher::findOrFail($id);

        return view('edit_teacher')->with('teacher', $teacher);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'fname' => 'required|string|min:2|max:30',
            'mname' => 'nullable|string|max:30',
            'lname' => 'required|string|min:2|max:30',
            'email' => 'required|email|max:255',
            'contact_no' => ['required', 'regex:/^[0-9]{11}$/'],
        ]);

        $teacher = Teacher::findOrFail($id);
        $teacher->update($data);

        return $this->respond($request, 'admin.teachers.show', $teacher->id, 'Teacher updated successfully!');
    }

    public function destroy(Request $request, string $id)
    {
        $imagePath = null;

        DB::transaction(function () use ($id, &$imagePath): void {
            $teacher = Teacher::with('userAccount')->findOrFail($id);
            $userAccount = $teacher->userAccount;
            $imagePath = $teacher->image_path;

            $teacher->delete();

            if ($userAccount) {
                $userAccount->delete();
            }
        });

        TeacherImageStorage::delete($imagePath);

        return $this->respond($request, 'admin.teachers.index', [], 'Teacher deleted successfully!');
    }

    private function teacherExportHeaders(): array
    {
        return ['ID', 'Full Name', 'Username', 'Email', 'Contact Number'];
    }

    private function teacherExportRows(): array
    {
        return Teacher::with('userAccount')
            ->orderBy('id')
            ->get()
            ->map(function (Teacher $teacher): array {
                return [
                    $teacher->id,
                    trim($teacher->fname . ' ' . $teacher->mname . ' ' . $teacher->lname),
                    $teacher->userAccount->username ?? 'N/A',
                    $teacher->email,
                    $teacher->contact_no,
                ];
            })
            ->all();
    }
}
