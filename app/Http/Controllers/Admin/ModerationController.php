<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Event;
use App\Models\Group;
use App\Models\Post;
use App\Models\Product;
use App\Models\Ride;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ModerationController extends Controller
{
    public function index(): View
    {
        return view('admin.moderation', [
            'posts' => Post::with('user')->latest()->limit(20)->get(),
            'groups' => Group::with('owner')->latest()->limit(20)->get(),
            'events' => Event::with('organizer')->latest()->limit(20)->get(),
            'rides' => Ride::with('driver')->latest()->limit(20)->get(),
            'stores' => Store::with('user')->latest()->limit(20)->get(),
            'products' => Product::with('store')->latest()->limit(20)->get(),
            'courses' => Course::with('tutor')->latest()->limit(20)->get(),
        ]);
    }

    public function destroyPost(Post $post): RedirectResponse
    {
        $post->delete();

        return back()->with('status', 'post-deleted');
    }

    public function destroyGroup(Group $group): RedirectResponse
    {
        $group->delete();

        return back()->with('status', 'group-deleted');
    }

    public function destroyEvent(Event $event): RedirectResponse
    {
        $event->delete();

        return back()->with('status', 'event-deleted');
    }

    public function destroyRide(Ride $ride): RedirectResponse
    {
        $ride->delete();

        return back()->with('status', 'ride-deleted');
    }

    public function suspendStore(Store $store): RedirectResponse
    {
        $store->update(['status' => 'suspended']);

        return back()->with('status', 'store-suspended');
    }

    public function unsuspendStore(Store $store): RedirectResponse
    {
        $store->update(['status' => 'approved']);

        return back()->with('status', 'store-unsuspended');
    }

    public function toggleProduct(Product $product): RedirectResponse
    {
        $product->update(['status' => $product->isActive() ? 'inactive' : 'active']);

        return back()->with('status', 'product-toggled');
    }

    public function suspendCourse(Course $course): RedirectResponse
    {
        $course->update(['status' => 'rejected']);

        return back()->with('status', 'course-suspended');
    }

    public function unsuspendCourse(Course $course): RedirectResponse
    {
        $course->update(['status' => 'approved']);

        return back()->with('status', 'course-unsuspended');
    }
}
