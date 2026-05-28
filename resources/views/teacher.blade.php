@extends('layout.format')

@section('title')
    Admin - Teachers
@endsection

@section('Header')
    @parent
@endsection

@section('Content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="page-title mb-2">Teacher Records</h1>
            @if(session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            <p class="page-subtitle mb-0">
                This page displays the official list of teachers with their contact information.
            </p>
        </div>

        <div class="d-flex flex-column flex-sm-row gap-2">
            <a href="{{ route('admin.teachers.export.excel') }}" data-ajax="false" class="btn btn-outline-success px-4 py-2 rounded-pill">
                Download Excel
            </a>
            <a href="{{ route('admin.teachers.export.pdf') }}" data-ajax="false" class="btn btn-outline-danger px-4 py-2 rounded-pill">
                Download PDF
            </a>
            <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                Add Teacher
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
                            <th>Username</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th class="text-center" colspan="3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr>
                                <td>{{ $teacher->id }}</td>
                                <td>{{ $teacher->fname }} {{ $teacher->mname }} {{ $teacher->lname }}</td>
                                <td>{{ $teacher->userAccount->username ?? 'N/A' }}</td>
                                <td>{{ $teacher->email }}</td>
                                <td>{{ $teacher->contact_no }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.teachers.show', $teacher->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        View
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        Edit
                                    </a>
                                </td>
                                <td class="text-center">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger rounded-pill px-3 js-ajax-delete"
                                        data-url="{{ route('admin.teachers.destroy', $teacher->id) }}"
                                        data-method="DELETE"
                                        data-redirect="{{ route('admin.teachers.index') }}"
                                        data-confirm="Are you sure you want to delete this teacher?"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    There are no teachers in the data resource.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $teachers->links() }}
    </div>
@endsection

@section('Footer')
    @parent
@endsection
