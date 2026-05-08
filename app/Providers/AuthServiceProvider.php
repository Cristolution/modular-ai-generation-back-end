<?php

namespace App\Providers;

use App\Models\Template;
use App\Policies\TemplatePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Template::class => TemplatePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}