<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Asset;
use App\Models\Portfolio;
use App\Models\Transaction;
use App\Policies\AssetPolicy;
use App\Policies\PortfolioPolicy;
use App\Policies\TransactionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Asset::class => AssetPolicy::class,
        Portfolio::class => PortfolioPolicy::class,
        Transaction::class => TransactionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
