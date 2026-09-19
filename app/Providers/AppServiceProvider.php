<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\BookingApproval;
use App\Observers\BookingApprovalObserver;
use App\Observers\BookingObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Booking::observe(BookingObserver::class);
        BookingApproval::observe(BookingApprovalObserver::class);
    }
}
