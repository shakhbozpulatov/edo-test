<?php

namespace App\Providers;

use App\Contracts\AttachmentServiceInterface;
use App\Contracts\DocumentServiceInterface;
use App\Contracts\QrCodeServiceInterface;
use App\Services\AttachmentService;
use App\Services\DocumentService;
use App\Services\QrCodeService;
use Illuminate\Support\ServiceProvider;

class DocumentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DocumentServiceInterface::class, DocumentService::class);
        $this->app->bind(QrCodeServiceInterface::class,  QrCodeService::class);
        $this->app->bind(AttachmentServiceInterface::class, AttachmentService::class);
    }
}
