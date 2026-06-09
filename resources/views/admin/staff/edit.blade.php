@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.staff.index') }}" class="text-decoration-none text-secondary mb-3 d-inline-block">
            <i class="bi bi-arrow-left me-1"></i> Back to Staff
        </a>
        <h2 class="fw-bold text-dark">Edit Staff</h2>
    </div>

    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label text-secondary small text-uppercase fw-bold ls-1">Full Name</label>
                            <input type="text" name="name"
                                class="form-control form-control-lg bg-light border-0 @error('name') is-invalid @enderror"
                                value="{{ old('name', $staff->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary small text-uppercase fw-bold ls-1">Email Address</label>
                            <input type="email" name="email"
                                class="form-control form-control-lg bg-light border-0 @error('email') is-invalid @enderror"
                                value="{{ old('email', $staff->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary small text-uppercase fw-bold ls-1">Role</label>
                            <select name="role"
                                class="form-select form-select-lg bg-light border-0 @error('role') is-invalid @enderror">
                                <option value="manager" {{ old('role', $staff->role) == 'manager' ? 'selected' : '' }}>Manager
                                </option>
                                <option value="cashier" {{ old('role', $staff->role) == 'cashier' ? 'selected' : '' }}>Cashier
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info border-0 bg-info bg-opacity-10 small">
                            <i class="bi bi-info-circle me-1"></i> Leave password fields empty if you don't want to change
                            it.
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-secondary small text-uppercase fw-bold ls-1">New
                                    Password</label>
                                <input type="password" name="password"
                                    class="form-control form-control-lg bg-light border-0 @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small text-uppercase fw-bold ls-1">Confirm
                                    Password</label>
                                <input type="password" name="password_confirmation"
                                    class="form-control form-control-lg bg-light border-0">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end pt-3">
                            <button type="submit" class="btn btn-dark btn-lg px-5 rounded-3">Update Staff</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection