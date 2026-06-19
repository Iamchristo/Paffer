<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ModerationController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\EventAttendanceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\GroupPostCommentController;
use App\Http\Controllers\GroupPostController;
use App\Http\Controllers\LearnController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RideBookingController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\Seller\ApplicationController as SellerApplicationController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\ShipmentController as SellerShipmentController;
use App\Http\Controllers\Seller\StoreController as SellerStoreController;
use App\Http\Controllers\Tutor\ApplicationController as TutorApplicationController;
use App\Http\Controllers\Tutor\CourseController as TutorCourseController;
use App\Http\Controllers\Tutor\LessonController as TutorLessonController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/install.php';

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public marketplace
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace/stores/{store:slug}', [MarketplaceController::class, 'show'])->name('marketplace.store');
Route::get('/marketplace/products/{product:slug}', [ProductController::class, 'show'])->name('marketplace.product');

// Public course catalog
Route::get('/learn', [CourseController::class, 'index'])->name('learn.index');
Route::get('/learn/{course:slug}', [CourseController::class, 'show'])->name('learn.show-catalog');

// Public groups / forums (browsing/index/show); creation requires auth.
Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
Route::get('/groups/create', [GroupController::class, 'create'])->middleware('auth')->name('groups.create');
Route::post('/groups', [GroupController::class, 'store'])->middleware('auth')->name('groups.store');
Route::get('/groups/{group:slug}', [GroupController::class, 'show'])->name('groups.show');

// Public events; creation requires auth.
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/create', [EventController::class, 'create'])->middleware('auth')->name('events.create');
Route::post('/events', [EventController::class, 'store'])->middleware('auth')->name('events.store');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');

// Public ride board; creation requires auth.
Route::get('/rides', [RideController::class, 'index'])->name('rides.index');
Route::get('/rides/create', [RideController::class, 'create'])->middleware('auth')->name('rides.create');
Route::post('/rides', [RideController::class, 'store'])->middleware('auth')->name('rides.store');
Route::get('/rides/{ride}', [RideController::class, 'show'])->name('rides.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/info', [ProfileController::class, 'updateInfo'])->name('profile.update-info');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Network / Social
    Route::get('/feed', [NetworkController::class, 'index'])->name('network.feed');
    Route::get('/people', [PeopleController::class, 'index'])->name('people.index');
    Route::get('/people/{user}', [PeopleController::class, 'show'])->name('people.show');
    Route::post('/people/{user}/follow', [ConnectionController::class, 'store'])->name('connections.store');
    Route::delete('/people/{user}/follow', [ConnectionController::class, 'destroy'])->name('connections.destroy');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');

    Route::post('/groups/{group:slug}/join', [GroupMemberController::class, 'store'])->name('groups.join');
    Route::delete('/groups/{group:slug}/leave', [GroupMemberController::class, 'destroy'])->name('groups.leave');
    Route::post('/groups/{group:slug}/posts', [GroupPostController::class, 'store'])->name('group-posts.store');
    Route::post('/group-posts/{groupPost}/comments', [GroupPostCommentController::class, 'store'])->name('group-posts.comments.store');

    Route::post('/events/{event:slug}/rsvp', [EventAttendanceController::class, 'store'])->name('events.rsvp.store');
    Route::delete('/events/{event:slug}/rsvp', [EventAttendanceController::class, 'destroy'])->name('events.rsvp.destroy');

    Route::post('/rides/{ride}/bookings', [RideBookingController::class, 'store'])->name('rides.bookings.store');
    Route::delete('/rides/{ride}/bookings', [RideBookingController::class, 'destroy'])->name('rides.bookings.destroy');

    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/like', [PostLikeController::class, 'store'])->name('posts.like');
    Route::post('/posts/{post}/comments', [PostCommentController::class, 'store'])->name('posts.comments.store');

    // Cart & checkout
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');

    Route::post('/marketplace/stores/{store:slug}/reviews', [ReviewController::class, 'storeForStore'])->name('reviews.store-store');
    Route::post('/learn/{course:slug}/reviews', [ReviewController::class, 'storeForCourse'])->name('reviews.store-course');

    // Learning
    Route::get('/my-courses', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::post('/learn/{course:slug}/enroll', [EnrollmentController::class, 'store'])->name('enrollments.store');
    Route::get('/learn/{course:slug}/play/{lesson?}', [LearnController::class, 'show'])->name('learn.show');

    // Advertising
    Route::get('/ads', [AdController::class, 'index'])->name('ads.index');
    Route::get('/ads/create', [AdController::class, 'create'])->name('ads.create');
    Route::post('/ads', [AdController::class, 'store'])->name('ads.store');
    Route::delete('/ads/{ad}', [AdController::class, 'destroy'])->name('ads.destroy');

    // Become a seller / tutor
    Route::get('/seller/apply', [SellerApplicationController::class, 'create'])->name('seller.apply');
    Route::post('/seller/apply', [SellerApplicationController::class, 'store'])->name('seller.apply.store');
    Route::get('/tutor/apply', [TutorApplicationController::class, 'create'])->name('tutor.apply');
    Route::post('/tutor/apply', [TutorApplicationController::class, 'store'])->name('tutor.apply.store');

    // Seller area
    Route::prefix('seller')->name('seller.')->group(function () {
        Route::get('/dashboard', [SellerStoreController::class, 'dashboard'])->name('dashboard');

        Route::middleware('seller')->group(function () {
            Route::get('/store', [SellerStoreController::class, 'edit'])->name('store.edit');
            Route::patch('/store', [SellerStoreController::class, 'update'])->name('store.update');
            Route::resource('products', SellerProductController::class)->except(['show']);
            Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
            Route::patch('/orders/{order}', [SellerOrderController::class, 'update'])->name('orders.update');
            Route::put('/orders/{order}/shipment', [SellerShipmentController::class, 'update'])->name('orders.shipment.update');
        });
    });

    // Tutor area
    Route::prefix('tutor')->name('tutor.')->group(function () {
        Route::resource('courses', TutorCourseController::class)->except(['show']);
        Route::post('/courses/{course}/submit', [TutorCourseController::class, 'submit'])->name('courses.submit');
        Route::post('/courses/{course}/lessons', [TutorLessonController::class, 'store'])->name('lessons.store');
        Route::patch('/courses/{course}/lessons/{lesson}', [TutorLessonController::class, 'update'])->name('lessons.update');
        Route::delete('/courses/{course}/lessons/{lesson}', [TutorLessonController::class, 'destroy'])->name('lessons.destroy');
    });

    // Admin area
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/verification', [VerificationController::class, 'index'])->name('verification.index');
        Route::post('/verification/sellers/{user}/approve', [VerificationController::class, 'approveSeller'])->name('verification.sellers.approve');
        Route::post('/verification/sellers/{user}/reject', [VerificationController::class, 'rejectSeller'])->name('verification.sellers.reject');
        Route::post('/verification/tutors/{user}/approve', [VerificationController::class, 'approveTutor'])->name('verification.tutors.approve');
        Route::post('/verification/tutors/{user}/reject', [VerificationController::class, 'rejectTutor'])->name('verification.tutors.reject');
        Route::post('/verification/courses/{course}/approve', [VerificationController::class, 'approveCourse'])->name('verification.courses.approve');
        Route::post('/verification/courses/{course}/reject', [VerificationController::class, 'rejectCourse'])->name('verification.courses.reject');
        Route::post('/verification/ads/{ad}/approve', [VerificationController::class, 'approveAd'])->name('verification.ads.approve');
        Route::post('/verification/ads/{ad}/reject', [VerificationController::class, 'rejectAd'])->name('verification.ads.reject');

        Route::get('/settings', [AdminSettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');

        Route::get('/announcements', [AdminAnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements', [AdminAnnouncementController::class, 'store'])->name('announcements.store');

        Route::get('/moderation', [ModerationController::class, 'index'])->name('moderation.index');
        Route::delete('/moderation/posts/{post}', [ModerationController::class, 'destroyPost'])->name('moderation.posts.destroy');
        Route::delete('/moderation/groups/{group}', [ModerationController::class, 'destroyGroup'])->name('moderation.groups.destroy');
        Route::delete('/moderation/events/{event}', [ModerationController::class, 'destroyEvent'])->name('moderation.events.destroy');
        Route::delete('/moderation/rides/{ride}', [ModerationController::class, 'destroyRide'])->name('moderation.rides.destroy');
        Route::post('/moderation/stores/{store}/suspend', [ModerationController::class, 'suspendStore'])->name('moderation.stores.suspend');
        Route::post('/moderation/stores/{store}/unsuspend', [ModerationController::class, 'unsuspendStore'])->name('moderation.stores.unsuspend');
        Route::post('/moderation/products/{product}/toggle', [ModerationController::class, 'toggleProduct'])->name('moderation.products.toggle');
        Route::post('/moderation/courses/{course}/suspend', [ModerationController::class, 'suspendCourse'])->name('moderation.courses.suspend');
        Route::post('/moderation/courses/{course}/unsuspend', [ModerationController::class, 'unsuspendCourse'])->name('moderation.courses.unsuspend');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');
        Route::post('/users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
        Route::post('/users/{user}/unsuspend', [AdminUserController::class, 'unsuspend'])->name('users.unsuspend');
        Route::post('/users/{user}/seller-status', [AdminUserController::class, 'updateSellerStatus'])->name('users.seller-status');
        Route::post('/users/{user}/tutor-status', [AdminUserController::class, 'updateTutorStatus'])->name('users.tutor-status');
    });
});

require __DIR__.'/auth.php';
