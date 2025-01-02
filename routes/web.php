<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Account\DashboardController as AccountDashboardController;
use App\Http\Controllers\Account\OrderController as AccountOrderController;
use App\Http\Controllers\Account\AddressController as AccountAddressController;
use App\Http\Controllers\Account\WishlistController as AccountWishlistController;
use App\Http\Controllers\Account\SettingsController as AccountSettingsController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
Route::get('/search', [ShopController::class, 'search'])->name('search');

// Routes des produits
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{product}/reviews', [ProductController::class, 'addReview'])->name('products.reviews.store');
Route::get('/products/{product}/quick-view', [ProductController::class, 'quickView'])->name('products.quick-view');
Route::get('/products/filter', [ProductController::class, 'filter'])->name('products.filter');

// Routes des catégories
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

// Routes du panier
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/empty-cart', [CartController::class, 'empty'])->name('cart.empty');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Liste de souhaits
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/remove/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::post('/wishlist/{wishlist}/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.move-to-cart');

    // Commandes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/track', [OrderController::class, 'track'])->name('orders.track');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Checkout et paiement
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/payment/{order}', [CheckoutController::class, 'processPayment'])->name('checkout.process-payment');
    Route::get('/checkout/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
    
    // Routes PayPal
    Route::get('/checkout/paypal/success/{order}', [CheckoutController::class, 'paypalSuccess'])->name('checkout.paypal.success');
    Route::get('/checkout/paypal/cancel/{order}', [CheckoutController::class, 'paypalCancel'])->name('checkout.paypal.cancel');
    Route::post('/checkout/paypal/notify/{order}', [CheckoutController::class, 'paypalNotify'])->name('checkout.paypal.notify');

    // Routes du compte utilisateur
    Route::prefix('account')->name('account.')->group(function () {
        // Tableau de bord
        Route::get('/', [AccountDashboardController::class, 'index'])->name('dashboard');

        // Commandes
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [AccountOrderController::class, 'index'])->name('index');
            Route::get('/{order}', [AccountOrderController::class, 'show'])->name('show');
            Route::post('/{order}/cancel', [AccountOrderController::class, 'cancel'])->name('cancel');
            Route::get('/{order}/return', [AccountOrderController::class, 'return'])->name('return');
            Route::post('/{order}/return', [AccountOrderController::class, 'submitReturn'])->name('submit-return');
        });

        // Adresses
        Route::prefix('addresses')->name('addresses.')->group(function () {
            Route::get('/', [AccountAddressController::class, 'index'])->name('index');
            Route::post('/', [AccountAddressController::class, 'store'])->name('store');
            Route::get('/{address}/edit', [AccountAddressController::class, 'edit'])->name('edit');
            Route::put('/{address}', [AccountAddressController::class, 'update'])->name('update');
            Route::delete('/{address}', [AccountAddressController::class, 'destroy'])->name('destroy');
            Route::post('/{address}/default', [AccountAddressController::class, 'setDefault'])->name('set-default');
        });

        // Liste d'envies
        Route::prefix('wishlist')->name('wishlist.')->group(function () {
            Route::get('/', [AccountWishlistController::class, 'index'])->name('index');
            Route::post('/', [AccountWishlistController::class, 'store'])->name('store');
            Route::delete('/{item}', [AccountWishlistController::class, 'destroy'])->name('destroy');
            Route::post('/toggle', [AccountWishlistController::class, 'toggle'])->name('toggle');
        });

        // Paramètres
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [AccountSettingsController::class, 'index'])->name('index');
            Route::put('/profile', [AccountSettingsController::class, 'updateProfile'])->name('update-profile');
            Route::put('/password', [AccountSettingsController::class, 'updatePassword'])->name('update-password');
            Route::put('/preferences', [AccountSettingsController::class, 'updatePreferences'])->name('update-preferences');
            Route::delete('/avatar', [AccountSettingsController::class, 'removeAvatar'])->name('remove-avatar');
            Route::delete('/', [AccountSettingsController::class, 'destroy'])->name('delete');
        });
    });
});

// Routes admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Produits
    Route::resource('products', AdminProductController::class);
    Route::patch('/products/{product}/featured', [AdminProductController::class, 'updateFeatured'])->name('products.featured');
    Route::patch('/products/{product}/active', [AdminProductController::class, 'updateActive'])->name('products.active');

    // Catégories
    Route::resource('categories', AdminCategoryController::class);
    Route::patch('/categories/{category}/active', [AdminCategoryController::class, 'updateActive'])->name('categories.active');

    // Commandes
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/orders/{order}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.payment-status');
    Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/orders/filter', [AdminOrderController::class, 'filter'])->name('orders.filter');

    // Utilisateurs
    Route::resource('users', AdminUserController::class);
    Route::patch('/users/{user}/active', [AdminUserController::class, 'updateActive'])->name('users.active');
    Route::get('/users/{user}/orders', [AdminUserController::class, 'orders'])->name('users.orders');
    Route::get('/users/{user}/reviews', [AdminUserController::class, 'reviews'])->name('users.reviews');

    // Avis
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [AdminReviewController::class, 'show'])->name('reviews.show');
    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/reviews/filter', [AdminReviewController::class, 'filter'])->name('reviews.filter');

    // Coupons
    Route::resource('coupons', AdminCouponController::class);
    Route::patch('/coupons/{coupon}/active', [AdminCouponController::class, 'updateActive'])->name('coupons.active');
    Route::get('/coupons/generate-code', [AdminCouponController::class, 'generateCode'])->name('coupons.generate-code');
    Route::get('/coupons/{coupon}/usage', [AdminCouponController::class, 'usageHistory'])->name('coupons.usage');
});

// Routes d'erreur
Route::get('/unauthorized-admin', function () {
    return view('errors.unauthorized-admin');
})->name('errors.unauthorized-admin');

Route::get('/inactive-account', function () {
    return view('errors.inactive-account');
})->name('errors.inactive-account');

Route::get('/empty-cart', function () {
    return view('errors.empty-cart');
})->name('errors.empty-cart');

Route::get('/unavailable-products', function () {
    return view('errors.unavailable-products');
})->name('errors.unavailable-products');

// Routes d'authentification
Auth::routes(['verify' => true]);

// Newsletter
Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Fallback route
Route::fallback(function () {
    return view('errors.404');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
