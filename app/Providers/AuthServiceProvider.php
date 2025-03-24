<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Policies\TicketPolicy;
use App\Models\CannedResponse;
use App\Policies\CannedResponsePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Ticket::class => TicketPolicy::class,
        CannedResponse::class => CannedResponsePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
} 