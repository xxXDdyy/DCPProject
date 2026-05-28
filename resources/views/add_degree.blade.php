@extends('layout.format')

@section('title')
    Admin - Add Degree
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
                    <h2 class="mb-1 fw-bold">Add New Degree</h2>
                    <p class="mb-0 opacity-75">
                        Fill in the degree information below to create a new record.
                    </p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="js-ajax-fields">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="degree" class="form-label fw-semibold">Degree Name</label>
                                <input
                                    type="text"
                                    name="degree"
                                    id="degree"
                                    class="form-control rounded-3 @error('degree') is-invalid @enderror"
                                    placeholder="Enter degree name"
                                    value="{{ old('degree') }}"
                                >
                                @error('degree')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end mt-4">
                            <a href="{{ route('admin.degrees.index') }}" class="btn btn-light border rounded-pill px-4">
                                Cancel
                            </a>
                            <button
                                type="button"
                                class="btn btn-primary rounded-pill px-4 shadow-sm js-ajax-save"
                                data-url="{{ route('admin.degrees.store') }}"
                                data-method="POST"
                                data-redirect="{{ route('admin.degrees.index') }}"
                            >
                                Save Degree
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
