<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Support\StudentImageStorage;
use App\Support\TeacherImageStorage;

class PagesController extends Controller
{
    public function about() {
        return view('maintenance_notice');
    }

    public function userProfile() {
        $user = User::find(1);
        echo $user->name . "-" . $user->profile->bio;
    }

    public function userPosts() {
        $user = User::find(1);
        foreach ($user->posts as $post) {
            echo "$user->name: - $post->title - $post->content <br>";
        }
    }

    public function studentCourses() {
        $student = Student::find(1);
        foreach ($student->courses as $course) {
            echo "$student->fname is taking $course->course_name <br>";
        }
    }

    public function maintenance() {
        return view('maintenance_notice');
    }

    public function studentPortal(Request $request) {
        $student = Student::with(['degree', 'userAccount'])
            ->where('user_account_id', $request->session()->get('user_id'))
            ->first();

        return view('student_portal')->with([
            'username' => $request->session()->get('username', 'Student'),
            'student' => $student,
        ]);
    }

    public function updateStudentImage(Request $request)
    {
        $student = Student::where('user_account_id', $request->session()->get('user_id'))->firstOrFail();

        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        $oldImagePath = $student->image_path;
        $student->image_path = StudentImageStorage::store($request->file('image'));
        $student->save();
        StudentImageStorage::delete($oldImagePath);

        if ($request->expectsJson()) {
            return response()->json([
                'redirect' => route('studentPortalRoute'),
                'message' => 'Profile image updated successfully!',
            ]);
        }

        return redirect()->route('studentPortalRoute')->with('message', 'Profile image updated successfully!');
    }

    public function teacherPortal(Request $request) {
        $teacher = Teacher::with('userAccount')
            ->where('user_account_id', $request->session()->get('user_id'))
            ->first();

        return view('teacher_portal')->with([
            'username' => $request->session()->get('username', 'Teacher'),
            'teacher' => $teacher,
        ]);
    }

    public function updateTeacherImage(Request $request)
    {
        $teacher = Teacher::where('user_account_id', $request->session()->get('user_id'))->firstOrFail();

        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        $oldImagePath = $teacher->image_path;
        $teacher->image_path = TeacherImageStorage::store($request->file('image'));
        $teacher->save();
        TeacherImageStorage::delete($oldImagePath);

        if ($request->expectsJson()) {
            return response()->json([
                'redirect' => route('teacherPortalRoute'),
                'message' => 'Profile image updated successfully!',
            ]);
        }

        return redirect()->route('teacherPortalRoute')->with('message', 'Profile image updated successfully!');
    }
}
