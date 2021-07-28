<?php

use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UserImpersonationController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group([
    'middleware' => 'auth'
], function () {
    Route::get('organizations', [OrganizationController::class, 'index'])
        ->name('organizations.index')
        ->middleware(['role:Stakeholder']);

    Route::get('users', [UsersController::class, 'index'])
        ->name('users.index')
        ->middleware(['role:Stakeholder|Admin']);

    Route::get('users/{user}/impersonate', [UserImpersonationController::class, 'store'])
        ->name('users.impersonate')
        ->middleware(['role:Stakeholder']);

    Route::group([
        'prefix' => 'companies',
        'as' => 'companies.'
    ], function () {
        Route::get('', [CompaniesController::class, 'index'])->name('index');
        Route::get('create', [CompaniesController::class, 'create'])->name('create')
            ->middleware(['role:Stakeholder|Admin']);
        Route::post('', [CompaniesController::class, 'store'])->name('store')
            ->middleware(['role:Stakeholder|Admin']);
        Route::get('{company}/edit', [CompaniesController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '{company}', [CompaniesController::class, 'update'])->name('update');
        Route::delete('{company}', [CompaniesController::class, 'destroy'])
            ->name('destroy')
            ->middleware(['role:Stakeholder|Admin']);
    });
});
