<?php

namespace App\Providers;

use App\Contracts\FileInterface;
use App\Models\Document;
use App\Policies\DocumentPolicy;
use App\Services\FileRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FileInterface::class, FileRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(Document::class, DocumentPolicy::class);
    }
}
