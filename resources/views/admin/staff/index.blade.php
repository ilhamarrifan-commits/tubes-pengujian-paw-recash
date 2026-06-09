@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">Staff Management</h2>
            <p class="text-secondary">Manage your team members and their roles</p>
        </div>
        <a href="{{ route('admin.staff.create') }}" class="btn btn-dark px-4 py-2 rounded-3">
            <i class="bi bi-plus-lg me-2"></i>Add New Staff
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0">Name</th>
                            <th class="py-3 border-0">Email</th>
                            <th class="py-3 border-0">Role</th>
                            <th class="py-3 border-0 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff as $user)
                            <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.staff.edit', $user->id) }}'">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div class="fw-medium text-dark">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td class="text-secondary">{{ $user->email }}</td>
                                <td>
                                    <span
                                        class="badge {{ $user->role === 'manager' ? 'bg-primary' : 'bg-info' }} bg-opacity-10 text-{{ $user->role === 'manager' ? 'primary' : 'info' }} px-3 py-2 rounded-pill fw-medium">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4" onclick="event.stopPropagation();">
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-light rounded-circle" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                            <li>
                                                <a class="dropdown-item py-2" href="{{ route('admin.staff.edit', $user->id) }}">
                                                    <i class="bi bi-pencil me-2 text-warning"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.staff.destroy', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger"
                                                        onclick="return confirm('Are you sure you want to delete this user?');">
                                                        <i class="bi bi-trash me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-secondary">
                                    <i class="bi bi-people display-6 d-block mb-3"></i>
                                    No staff members found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection