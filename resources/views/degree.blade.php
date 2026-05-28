@extends('layout.format')

@section('title')
    Admin - Degrees
@endsection

@section('Header')
    @parent
@endsection

@section('Content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="page-title mb-2">Degree Programs</h1>
            <p class="page-subtitle mb-0">
                This page displays the official list of degree programs.
            </p>
        </div>

        <div>
            <a href="{{ route('admin.degrees.create') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                Add Degree
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
                            <th>Degree</th>
                            <th class="text-center" colspan="3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($degrees as $degree)
                            <tr>
                                <td>{{ $degree->degree_id }}</td>
                                <td>{{ $degree->degree }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.degrees.show', $degree->degree_id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        View
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.degrees.edit', $degree->degree_id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        Edit
                                    </a>
                                </td>
                                <td class="text-center">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger rounded-pill px-3 js-ajax-delete"
                                        data-url="{{ route('admin.degrees.destroy', $degree->degree_id) }}"
                                        data-method="DELETE"
                                        data-redirect="{{ route('admin.degrees.index') }}"
                                        data-confirm="Are you sure you want to delete this degree?"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    There are no degrees in the data resource.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $degrees->links() }}
    </div>
@endsection

@section('Footer')
    @parent
@endsection
