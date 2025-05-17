<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AdminStaffController extends Controller
{
    public function index()
    {
        $staffs = Staff::all();
        return view('admin.staff.index', compact('staffs'));
    }

}
