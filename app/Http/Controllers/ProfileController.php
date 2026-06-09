<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    // For Manager/Admin to view their profile
    public function show()
    {
        return view('manager.profile');
    }

    // Common method to delete account
    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        Auth::logout();

        // Delete the user
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Your account has been deleted.');
    }
}
