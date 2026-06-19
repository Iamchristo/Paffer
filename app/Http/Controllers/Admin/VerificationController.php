<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function index(): View
    {
        return view('admin.verification', [
            'pendingSellers' => User::where('seller_status', 'pending')->with('store')->get(),
            'pendingTutors' => User::where('tutor_status', 'pending')->get(),
            'pendingCourses' => Course::where('status', 'pending')->with('tutor')->get(),
            'pendingAds' => Ad::where('status', 'pending')->with('advertiser')->get(),
        ]);
    }

    public function approveSeller(User $user): RedirectResponse
    {
        $user->update(['seller_status' => 'approved']);
        $user->store()->update(['status' => 'approved']);

        return back()->with('status', 'seller-approved');
    }

    public function rejectSeller(User $user): RedirectResponse
    {
        $user->update(['seller_status' => 'rejected', 'is_seller' => false]);
        $user->store()->update(['status' => 'rejected']);

        return back()->with('status', 'seller-rejected');
    }

    public function approveTutor(User $user): RedirectResponse
    {
        $user->update(['tutor_status' => 'approved']);

        return back()->with('status', 'tutor-approved');
    }

    public function rejectTutor(User $user): RedirectResponse
    {
        $user->update(['tutor_status' => 'rejected', 'is_tutor' => false]);

        return back()->with('status', 'tutor-rejected');
    }

    public function approveCourse(Course $course): RedirectResponse
    {
        $course->update(['status' => 'approved']);

        return back()->with('status', 'course-approved');
    }

    public function rejectCourse(Course $course): RedirectResponse
    {
        $course->update(['status' => 'rejected']);

        return back()->with('status', 'course-rejected');
    }

    public function approveAd(Ad $ad): RedirectResponse
    {
        $ad->update(['status' => 'approved']);

        return back()->with('status', 'ad-approved');
    }

    public function rejectAd(Ad $ad): RedirectResponse
    {
        $ad->update(['status' => 'rejected']);

        return back()->with('status', 'ad-rejected');
    }
}
