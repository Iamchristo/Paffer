<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'users' => User::count(),
                'sellers' => User::where('is_seller', true)->where('seller_status', 'approved')->count(),
                'tutors' => User::where('is_tutor', true)->where('tutor_status', 'approved')->count(),
                'stores' => Store::where('status', 'approved')->count(),
                'courses' => Course::where('status', 'approved')->count(),
                'orders' => Order::count(),
                'pending_sellers' => User::where('seller_status', 'pending')->count(),
                'pending_tutors' => User::where('tutor_status', 'pending')->count(),
                'pending_courses' => Course::where('status', 'pending')->count(),
            ],
        ]);
    }
}
