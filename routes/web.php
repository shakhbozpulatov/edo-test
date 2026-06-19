<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Document\AttachmentController;
use App\Http\Controllers\Document\DocumentController;
use App\Http\Controllers\Document\OrganizationController;
use App\Http\Controllers\Document\TemplateController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/signed', [DocumentController::class, 'signed'])->name('documents.signed');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::post('/documents/{document}/sign', [DocumentController::class, 'sign'])->name('documents.sign');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/documents/{document}/download', [DocumentController::class, 'downloadMain'])->name('documents.download-main');
    Route::post('/documents/{document}/qr-position', [DocumentController::class, 'updateQrPosition'])->name('documents.qr-position');

    Route::post('/documents/{document}/attachments', [AttachmentController::class, 'store'])->name('attachments.store');
    Route::get('/documents/{document}/attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
    Route::delete('/documents/{document}/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

    Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
    Route::post('/templates/{template}/select', [TemplateController::class, 'select'])->name('templates.select');

    Route::get('/organizations/tree', [OrganizationController::class, 'tree'])->name('organizations.tree');
    Route::get('/organizations/regions', [OrganizationController::class, 'regions'])->name('organizations.regions');
    Route::get('/organizations/regions/{region}/districts', [OrganizationController::class, 'districts'])->name('organizations.districts');
});
