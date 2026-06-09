<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ManagerStaffController extends Controller
{
    public function index()
    {
        $staff = User::whereIn('role', ['manager', 'cashier'])->get();
        return view('manager.staff.index', compact('staff'));
    }
}
