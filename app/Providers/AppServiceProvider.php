<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Gate;
use App\Models\Permission;

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
        // Define dynamic permission gates using Gate::before to support dynamic loading and testing
        Gate::before(function ($user, $ability) {
            $permissions = ['manage_users', 'view_logs', 'manage_master_data', 'manage_moora'];
            if (in_array($ability, $permissions)) {
                try {
                    return $user->role && $user->role->permissions->contains('name', $ability);
                } catch (\Exception $e) {
                    return false;
                }
            }
        });
    }
}
