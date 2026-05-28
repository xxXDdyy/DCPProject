@extends('layout.format')

@section('title')
    Admin - Teacher Details
@endsection

@section('Header')
    @parent
@endsection

@section('Content')
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header text-white py-4 px-4 border-0"
                     style="background: linear-gradient(135deg, #1e3a8a, #2563eb, #0ea5e9);">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h2 class="mb-1 fw-bold">Teacher Details</h2>
                            <p class="mb-0 opacity-75">
                                View the complete information of the selected teacher record.
                            </p>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-light rounded-pill px-4">
                                Edit
                            </a>
                            <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-light rounded-pill px-4">
                                Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 bg-light h-100">
                                <small class="text-muted d-block mb-1">Teacher ID</small>
                                <div class="fw-semibold fs-5">{{ $teacher->id }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 bg-light h-100">
                                <small class="text-muted d-block mb-1">Email Address</small>
                                <div class="fw-semibold fs-5">{{ $teacher->email }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 bg-light h-100">
                                <small class="text-muted d-block mb-1">Username</small>
                                <div class="fw-semibold fs-5">{{ $teacher->userAccount->username ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded-4 p-3 h-100">
                                <small class="text-muted d-block mb-1">First Name</small>
                                <div class="fw-semibold">{{ $teacher->fname }}</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded-4 p-3 h-100">
                                <small class="text-muted d-block mb-1">Middle Name</small>
                                <div class="fw-semibold">{{ $teacher->mname }}</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded-4 p-3 h-100">
                                <small class="text-muted d-block mb-1">Last Name</small>
                                <div class="fw-semibold">{{ $teacher->lname }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 h-100">
                                <small class="text-muted d-block mb-1">Contact Number</small>
                                <div class="fw-semibold">{{ $teacher->contact_no }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 h-100">
                                <small class="text-muted d-block mb-1">Full Name</small>
                                <div class="fw-semibold">
                                    {{ $teacher->fname }} {{ $teacher->mname }} {{ $teacher->lname }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex flex-column flex-sm-row gap-2 justify-content-end">
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-light border rounded-pill px-4">
                            Back to List
                        </a>
                        <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            Edit Teacher
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
