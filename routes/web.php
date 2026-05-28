<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\CalculateController;
use App\Http\Controllers\PSUController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DegreeController;
use App\Http\Controllers\Administrator\DashboardPagesController;
use App\Http\Controllers\UserController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware(['auth.session', 'groupMiddleware'])->group(function () {
    Route::get('/user_profile', [PagesController::class, 'userProfile']);
    Route::get('/user_posts', [PagesController::class, 'userPosts']);
    Route::get('/student_courses', [PagesController::class, 'studentCourses']);
});

Route::redirect('/', '/login');
Route::get('/login', [UserController::class, 'showLoginForm'])->name("loginRoute");
Route::post('/login', [UserController::class, 'login'])->name("loginSubmitRoute");
Route::get('/password/change-first-login', [UserController::class, 'showFirstLoginPasswordForm'])->name("firstPasswordFormRoute");
Route::post('/password/change-first-login', [UserController::class, 'updateFirstLoginPassword'])->name("firstPasswordUpdateRoute");
Route::post('/logout', [UserController::class, 'logout'])->name("logoutRoute");
Route::get('/student-portal', [PagesController::class, 'studentPortal'])
    ->middleware(['auth.session', 'role:student'])
    ->name('studentPortalRoute');
Route::post('/student-portal/image', [PagesController::class, 'updateStudentImage'])
    ->middleware(['auth.session', 'role:student'])
    ->name('studentImageUpdateRoute');
Route::get('/teacher-portal', [PagesController::class, 'teacherPortal'])
    ->middleware(['auth.session', 'role:teacher'])
    ->name('teacherPortalRoute');
Route::post('/teacher-portal/image', [PagesController::class, 'updateTeacherImage'])
    ->middleware(['auth.session', 'role:teacher'])
    ->name('teacherImageUpdateRoute');

Route::get('/maintenance', [PagesController::class, 'maintenance']);

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth.session', 'role:admin'])
    ->group(function () {
        Route::get('/', [DashboardPagesController::class, 'index'])->name('dashboard');
        Route::get('/students/export/excel', [StudentController::class, 'exportExcel'])->name('students.export.excel');
        Route::get('/students/export/pdf', [StudentController::class, 'exportPdf'])->name('students.export.pdf');
        Route::get('/teachers/export/excel', [TeacherController::class, 'exportExcel'])->name('teachers.export.excel');
        Route::get('/teachers/export/pdf', [TeacherController::class, 'exportPdf'])->name('teachers.export.pdf');
        Route::resource('/students', StudentController::class);
        Route::resource('/teachers', TeacherController::class);
        Route::resource('/degrees', DegreeController::class);
    });

Route::get('/students/{path?}', function (?string $path = null) {
    return redirect('/admin/students' . ($path !== null ? '/' . $path : ''));
})->where('path', '.*');

Route::get('/degree/{path?}', function (?string $path = null) {
    return redirect('/admin/degrees' . ($path !== null ? '/' . $path : ''));
})->where('path', '.*');

// Route::get('/aboutPage', [StudentController::class, 'displayAboutPage']);
Route::resource('/studentsPage', StudentController::class)->middleware(['auth.session', 'role:admin']);
// Route::get('/homePage', [StudentController::class, 'displayHomePage']);

Route::resource('/student', StudentController::class)->middleware(['auth.session', 'role:admin']);

// Route::get('/profile', [StudentController::class, 'displayProfile']);
// Route::get('/dashboard', [StudentController::class, 'displayDashboard']);
// Route::get('/aboutUs', [StudentController::class, 'displayAboutUs']);

// Route::get('/greetings', [StudentController::class, 'greet']);

// Route::get('/student/{name}/{course}', [PSUController::class, 'student'])->name("studentRoute");

// Route::get('/welcome', [PSUController::class, 'welcome'])->name("welcomeRoute");
Route::get('/mission', [PSUController::class, 'mission'])->name("missionRoute");
// Route::get('/vision', [PSUController::class, 'vision'])->name("visionRoute");
// Route::get('/EOMSPolicy', [PSUController::class, 'EOMSPolicy'])->name("EOMSPolicyRoute");

// Route::resource('/students', StudentController::class);

// Route::get('/add', [CalculateController::class, 'add']);
// Route::get('/subtract', [CalculateController::class, 'subtract']);
// Route::get('/divide', [CalculateController::class, 'divide']);
// Route::get('/multiply', [CalculateController::class, 'multiply']);
// Route::get('/modulo', [CalculateController::class, 'modulo']);

Route::redirect('/about', '/maintenance');


// //Task 1: Creating Named Routes
// Route::get('/home', function () {
//     return "I am Dyxx.Welcome to the Home Page!";
// })->name("home.page");

// //Task 2: Using Named Routes
// Route::get('/redirect-home', function () {
//     return redirect()->route("home.page");
// })->name("redirectRoute");

// //Task 3: Required Route Parameter
// Route::get('/greet/{name}', function ($name) {
//     return "Hello " .$name. "!";
// })->name("greetRoute");

// //Task 4: Optional Route Parameter
// Route::get('/student/{name?}', function ($name="Dyxx") {
//     return "Hello," .$name. "!";
// })->name("studentRoute");

// //Task 5: Route Group with Prefix
// Route::prefix('administrator')->group(
//     function(){
//         Route::get('/dashboard', function () {
//             return "Dashboard";
//         })->name("dashboardRoute");

//         Route::get('/profile', function () {
//             return "Profile";
//         })->name("profileRoute");

//         Route::get('/settings', function () {
//             return "Settings Page";
//         })->name("settingsRoute");
//     }
// );

// //Task 6: Redirect on Route Group
// Route::get('/redirect_dashboard', function () {
//     return redirect()->route("dashboardRoute");
// })->name("redirectAdminDashboard");

// Route::prefix('student')->group(
//     function(){
//         Route::get('/profile', function () {
//             return "This is Student Profile Page";
//         })->name("studentProfileRoute");

// Route::get('/dashboard', function () {
//             return "This is Student Dashboard Page";
//         })->name("studentDashboardRoute");

// Route::get('/friendlist', function () {
//             return "This is Student Friend List Page";
//         })->name("studentFriendListRoute");
//     }
// );

// Route::get('/users/{id}', function ($id) {
//     return "User ID: " .$id;
// })->where('id','[0-9]+')->name("userRoute");

// Route::get('/greet/{name?}/message/{msg?}', function ($name="Guest", $msg="Hello World") {
//     return "Welcome " .$name. " to Laravel App Development " .$msg;
// })->name("greetRoute");

// Route::get('/login', function () {
//     return "Enter your username and password";
// })->name("loginRoute");

// Route::get('/home', function () {
//     return "Welcome to Home Page";
// })->name("homeRoute");

// Route::get('/logout', function () {
//     return redirect()->route("loginRoute");
// })->name("logoutRoute");
