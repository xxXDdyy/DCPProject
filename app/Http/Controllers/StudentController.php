<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Degree;
use App\Models\Student;
use App\Models\UserAccounts;
use App\Support\StudentImageStorage;
use App\Support\TableExport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class StudentController extends Controller
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

    // public function displayHomePage() {
    //     return view('homePage');
    // }

    // public function displayAboutPage() {
    //     return view('aboutPage');
    // }

    // public function displayAboutUs() {
    //     return view('aboutUs');
    // }

    // public function displayDashboard() {
    //     return view('dashboard');
    // }

    // public function displayProfile() {
    //     return view('profile');
    // }

    // public function greet() {
    //     $name = "John Dela Cruz";
    //     $address = "San Carlos City";
    //     // return view('greetings',['name'=>$name]);
    //     return view('greetings',compact('name','address'));
    // }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with(['degree', 'userAccount'])->paginate();
        return view('student')->with('students',$students);

        // $students = array(
        //     array("student_name"=>"Dyxx","age"=>"23","course"=>"BSIT"),
        //     array("student_name"=>"Neriss","age"=>"20","course"=>"BSBA"),
        //     array("student_name"=>"Dennis","age"=>"19","course"=>"BTLED"),
        // );
        
        // return view("studentsPage")->with("students",$students);

        // $grade = 101;

        // $students = array(
        //     array("name"=>"Dyxx","address"=>"San Carlos City","sex"=>"Male"),
        //     array("name"=>"Neriss","address"=>"Urbiztondo","sex"=>"Female"),
        //     array("name"=>"Dennis","address"=>"Calasiao","sex"=>"Male"),
        // );
        // return view("student")->with("students",$students)->with("grade",$grade);
    
        // $course = "BSIT";

        // $student = [
        //    "name" => "Dyxx Peralta",
        //    "address" => "San Carlos City",
        //    "sex" => "Male"
        // ];
        // return view("student")->with($student)->with("course",$course);
    }

    public function exportExcel()
    {
        return TableExport::excel(
            'students.xls',
            'Student List',
            $this->studentExportHeaders(),
            $this->studentExportRows()
        );
    }

    public function exportPdf()
    {
        return TableExport::pdf(
            'students.pdf',
            'Student List',
            $this->studentExportHeaders(),
            $this->studentExportRows()
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $degrees = Degree::orderBy('degree')->get();
        return view('add_student')->with('degrees', $degrees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'fname' => 'required|string|min:2|max:30',
            'mname' => 'nullable|string|max:30',
            'lname' => 'required|string|min:2|max:30',
            'email' => 'required|email|max:30|unique:user_accounts,email',
            'contact_no' => ['required', 'regex:/^[0-9]{11}$/'],
            'degree_id' => 'required|exists:degrees,degree_id',
            'username' => 'required|string|min:5|max:30|unique:user_accounts,username',
            'password' => 'required|string|min:8|max:255',
        ]);

        DB::transaction(function () use ($data): void {
            $user = UserAccounts::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'student',
                'must_change_password' => true,
            ]);

            $studentData = [
                'fname' => $data['fname'],
                'mname' => $data['mname'] ?? null,
                'lname' => $data['lname'],
                'contact_no' => $data['contact_no'],
                'degree_id' => $data['degree_id'],
                'user_account_id' => $user->id,
            ];

            if (Schema::hasColumn('students', 'email')) {
                $studentData['email'] = $data['email'];
            }

            Student::create($studentData);
        });

        $msg = "Student is added successfully!";
        Log::info($msg);
        Log::notice($msg);
        Log::warning($msg);
        Log::error($msg);
        Log::alert($msg);
        Log::critical($msg);
        Log::emergency($msg);

        return $this->respond($request, 'admin.students.index', [], 'Student added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::with(['degree', 'userAccount'])->findOrFail($id);
        return view('showStudentDetails')->with('student',$student);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::with('userAccount')->findOrFail($id);
        $degrees = Degree::orderBy('degree')->get();

        return view('edit_student')
            ->with('student', $student)
            ->with('degrees', $degrees);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::with('userAccount')->findOrFail($id);

        $data = $request->validate([
            'fname' => 'required|string|min:2|max:255',
            'mname' => 'nullable|string|min:2|max:255',
            'lname' => 'required|string|min:2|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('user_accounts', 'email')->ignore($student->user_account_id),
            ],
            'contact_no' => ['required', 'regex:/^[0-9]{11}$/'],
            'degree_id' => 'required|exists:degrees,degree_id',
        ]);

        DB::transaction(function () use ($student, $data): void {
            $studentData = [
                'fname' => $data['fname'],
                'mname' => $data['mname'] ?? null,
                'lname' => $data['lname'],
                'contact_no' => $data['contact_no'],
                'degree_id' => $data['degree_id'],
            ];

            if (Schema::hasColumn('students', 'email')) {
                $studentData['email'] = $data['email'];
            }

            $student->update($studentData);

            if ($student->userAccount) {
                $student->userAccount->update([
                    'email' => $data['email'],
                ]);
            }
        });

        $msg = "Student is updated successfully!";
        Log::info($msg);
        Log::notice($msg);
        Log::warning($msg);
        Log::error($msg);
        Log::alert($msg);
        Log::critical($msg);
        Log::emergency($msg);

        return $this->respond($request, 'admin.students.show', $student->id, 'Student updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $imagePath = null;

        DB::transaction(function () use ($id, &$imagePath): void {
            $student = Student::with('userAccount')->findOrFail($id);
            $userAccount = $student->userAccount;
            $imagePath = $student->image_path;

            $student->delete();

            if ($userAccount) {
                $userAccount->delete();
            }
        });

        StudentImageStorage::delete($imagePath);

        $msg = "Student is deleted successfully!";
        Log::info($msg);
        Log::notice($msg);
        Log::warning($msg);
        Log::error($msg);
        Log::alert($msg);
        Log::critical($msg);
        Log::emergency($msg);

        return $this->respond($request, 'admin.students.index', [], 'Student deleted successfully!');
    }

    private function studentExportHeaders(): array
    {
        return ['ID', 'Full Name', 'Username', 'Email', 'Contact Number', 'Degree'];
    }

    private function studentExportRows(): array
    {
        return Student::with(['degree', 'userAccount'])
            ->orderBy('id')
            ->get()
            ->map(function (Student $student): array {
                return [
                    $student->id,
                    trim($student->fname . ' ' . $student->mname . ' ' . $student->lname),
                    $student->userAccount->username ?? 'N/A',
                    $student->email ?? 'N/A',
                    $student->contact_no,
                    $student->degree->degree ?? 'N/A',
                ];
            })
            ->all();
    }
}
