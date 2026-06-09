@extends('layouts.app')

@section('content')
<div class="container h-100 d-flex flex-column justify-content-center align-items-center">
    <div class="card border-0 shadow-lg rounded-4 p-5 text-center" style="width: 400px; background-color: #F8F6F2;">
        <div class="mb-4">
            <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width:100px; height:100px; font-size: 3rem;">
                <i class="bi bi-person-fill"></i>
            </div>
        </div>
        
        <h3 class="fw-bold mb-1">{{ Auth::user()->name }}</h3>
        <p class="text-muted mb-4">{{ Auth::user()->email }}</p>
        
        <div class="d-grid gap-2">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-dark w-100 py-2 rounded-pill fw-bold">Logout</button>
            </form>
            <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger w-100 py-2 rounded-pill fw-bold border-2">Delete Account</button>
            </form>
            <a href="{{ route('cashier.dashboard') }}" class="btn btn-outline-secondary w-100 py-2 rounded-pill fw-bold">Back to POS</a>
        </div>
    </div>
</div>
@endsection
