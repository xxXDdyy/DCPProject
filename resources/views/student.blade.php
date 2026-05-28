@extends('layout.format')

@section('title')
    Admin - Students
@endsection

@section('Header')
    @parent
@endsection

@section('Content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="page-title mb-2">Student Records</h1>
            @if(session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            <p class="page-subtitle mb-0">
                This page displays the official list of students together with contact information
                and their assigned degree program.
            </p>
        </div>

        <div class="d-flex flex-column flex-sm-row gap-2">
            <a href="{{ route('admin.students.export.excel') }}" data-ajax="false" class="btn btn-outline-success px-4 py-2 rounded-pill">
                Download Excel
            </a>
            <a href="{{ route('admin.students.export.pdf') }}" data-ajax="false" class="btn btn-outline-danger px-4 py-2 rounded-pill">
                Download PDF
            </a>
            <a href="{{ route('admin.students.create') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                Add Student
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Degree</th>
                            <th class="text-center" colspan="3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $student['id'] }}</td>
                                <td>{{ $student['fname'] }} {{ $student['mname'] }} {{ $student['lname'] }}</td>
                                <td>{{ $student['email'] }}</td>
                                <td>{{ $student['contact_no'] }}</td>
                                <td>{{ $student->degree->degree ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.students.show', $student['id']) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        View
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.students.edit', $student['id']) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        Edit
                                    </a>
                                </td>
                                <td class="text-center">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger rounded-pill px-3 js-ajax-delete"
                                        data-url="{{ route('admin.students.destroy', $student['id']) }}"
                                        data-method="DELETE"
                                        data-redirect="{{ route('admin.students.index') }}"
                                        data-confirm="Are you sure you want to delete this student?"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    There are no students in the data resource.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $students->links() }}
    </div>
@endsection

@section('Footer')
    @parent
@endsection
