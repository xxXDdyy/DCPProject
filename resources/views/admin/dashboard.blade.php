@extends('layout.format')

@section('title')
    Admin Dashboard
@endsection

@section('Header')
    @parent
@endsection

@section('Content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            @if(session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            <h1 class="page-title mb-2">Admin Dashboard</h1>
            <p class="page-subtitle mb-0">
                Manage student, teacher, and degree records from one place.
            </p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <h2 class="h4 fw-bold mb-2">Students</h2>
                    <p class="text-muted mb-4">Create, review, update, and remove student records.</p>
                    <div class="mt-auto">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-primary rounded-pill px-4">
                            Open Students
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <h2 class="h4 fw-bold mb-2">Teachers</h2>
                    <p class="text-muted mb-4">Create and manage teacher records.</p>
                    <div class="mt-auto">
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-primary rounded-pill px-4">
                            Open Teachers
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <h2 class="h4 fw-bold mb-2">Degrees</h2>
                    <p class="text-muted mb-4">Maintain the degree program list used by students.</p>
                    <div class="mt-auto">
                        <a href="{{ route('admin.degrees.index') }}" class="btn btn-primary rounded-pill px-4">
                            Open Degrees
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('Footer')
    @parent
@endsection
