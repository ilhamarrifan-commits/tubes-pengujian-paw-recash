@extends('layouts.app')

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center h-100">
    <div class="card border-0 rounded-4 shadow-sm p-5 text-center" style="width: 400px; background-color: #EAE5D9;">
         <div class="mb-4">
             <div class="bg-dark rounded-circle text-white d-flex align-items-center justify-content-center mx-auto" style="width:100px;height:100px;">
                <i class="bi bi-person-fill display-3"></i>
             </div>
         </div>
         
         <h3 class="fw-bold m-0">{{ Auth::user()->name }}</h3>
         <p class="text-muted mb-4">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Manager' }}</p>
         
         <div class="d-grid gap-2">
             <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-dark w-100 py-2 rounded-pill fw-bold">Logout</button>
             </form>
             
             <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger w-100 py-2 rounded-pill fw-bold border-2">Delete Account</button>
             </form>
         </div>
    </div>
</div>
@endsection
