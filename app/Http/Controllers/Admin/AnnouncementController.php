<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminAnnouncement;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        return view('admin.announcements', [
            'announcements' => Announcement::with('admin')->latest()->paginate(15),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'audience' => ['required', 'in:all,sellers,tutors,members'],
        ]);

        $recipients = $this->audienceQuery($data['audience'])->get();

        foreach ($recipients as $recipient) {
            Mail::to($recipient)->send(new AdminAnnouncement($data['subject'], $data['body']));
        }

        Announcement::create([
            'admin_id' => $request->user()->id,
            'subject' => $data['subject'],
            'body' => $data['body'],
            'audience' => $data['audience'],
            'recipient_count' => $recipients->count(),
        ]);

        return back()->with('status', 'announcement-sent');
    }

    private function audienceQuery(string $audience)
    {
        return match ($audience) {
            'sellers' => User::where('is_seller', true)->where('seller_status', 'approved'),
            'tutors' => User::where('is_tutor', true)->where('tutor_status', 'approved'),
            'members' => User::where('is_seller', false)->where('is_tutor', false),
            default => User::query(),
        };
    }
}
