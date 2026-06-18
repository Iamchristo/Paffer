<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LearnController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Seller\ApplicationController as SellerApplicationController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
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
    });
});

require __DIR__.'/auth.php';
