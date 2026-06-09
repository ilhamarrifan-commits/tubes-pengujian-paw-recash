@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold m-0">Staff Management</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-secondary border-0">Name</th>
                            <th class="px-4 py-3 text-secondary border-0">Email</th>
                            <th class="px-4 py-3 text-secondary border-0">Role</th>
                            <th class="px-4 py-3 text-secondary border-0">Joined Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff as $user)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center"
                                            style="width:40px;height:40px;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <span class="fw-bold">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-secondary">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    @if ($user->role === 'manager')
                                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">Manager</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Cashier</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-secondary">{{ $user->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-secondary">
                                    <i class="bi bi-people fs-1 d-block mb-3"></i>
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
