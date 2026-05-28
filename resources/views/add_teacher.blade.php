@extends('layout.format')

@section('title')
    Admin - Add Teacher
@endsection

@section('Header')
    @parent
@endsection

@section('Content')
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9 col-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header text-white py-4 px-4 border-0"
                     style="background: linear-gradient(135deg, #1e3a8a, #2563eb, #0ea5e9);">
                    <h2 class="mb-1 fw-bold">Add New Teacher</h2>
                    <p class="mb-0 opacity-75">
                        Fill in the teacher information and login credentials below to create a new record.
                    </p>
                </div>

                <div class="card-body p-4 p-md-5">
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="js-ajax-fields">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="fname" class="form-label fw-semibold">First Name</label>
                                <input
                                    type="text"
                                    name="fname"
                                    id="fname"
                                    class="form-control rounded-3 @error('fname') is-invalid @enderror"
                                    placeholder="Enter first name"
                                    value="{{ old('fname') }}"
                                >
                                @error('fname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="mname" class="form-label fw-semibold">Middle Name</label>
                                <input
                                    type="text"
                                    name="mname"
                                    id="mname"
                                    class="form-control rounded-3 @error('mname') is-invalid @enderror"
                                    placeholder="Enter middle name"
                                    value="{{ old('mname') }}"
                                >
                                @error('mname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="lname" class="form-label fw-semibold">Last Name</label>
                                <input
                                    type="text"
                                    name="lname"
                                    id="lname"
                                    class="form-control rounded-3 @error('lname') is-invalid @enderror"
                                    placeholder="Enter last name"
                                    value="{{ old('lname') }}"
                                >
                                @error('lname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="form-control rounded-3 @error('email') is-invalid @enderror"
                                    placeholder="Enter email address"
                                    value="{{ old('email') }}"
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="contact_no" class="form-label fw-semibold">Contact Number</label>
                                <input
                                    type="text"
                                    name="contact_no"
                                    id="contact_no"
                                    class="form-control rounded-3 @error('contact_no') is-invalid @enderror"
                                    placeholder="Enter contact number"
                                    value="{{ old('contact_no') }}"
                                >
                                @error('contact_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="username" class="form-label fw-semibold">Username</label>
                                <input
                                    type="text"
                                    name="username"
                                    id="username"
                                    class="form-control rounded-3 @error('username') is-invalid @enderror"
                                    placeholder="Enter username"
                                    value="{{ old('username') }}"
                                >
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Temporary Password</label>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control rounded-3 @error('password') is-invalid @enderror"
                                    placeholder="Enter temporary password"
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <p class="text-muted small mb-0 mt-3">
                            The teacher will be required to enter this temporary password and create a new password on first login.
                        </p>

                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end mt-4">
                            <a href="{{ route('admin.teachers.index') }}" class="btn btn-light border rounded-pill px-4">
                                Cancel
                            </a>
                            <button
                                type="button"
                                class="btn btn-primary rounded-pill px-4 shadow-sm js-ajax-save"
                                data-url="{{ route('admin.teachers.store') }}"
                                data-method="POST"
                                data-redirect="{{ route('admin.teachers.index') }}"
                            >
                                Save Teacher
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('Footer')
    @parent
@endsection
