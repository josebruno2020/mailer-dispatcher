<?php

use App\Livewire\EmailCreate;
use App\Livewire\EmailIndex;
use App\Livewire\SettingCreate;
use App\Livewire\SettingIndex;
use App\Livewire\TemplateCreate;
use App\Livewire\TemplateIndex;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::post('logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('settings', SettingIndex::class)->name('settings');    //
    Route::get('settings/create', SettingCreate::class)->name('settings.create');    //
    Route::get('templates', TemplateIndex::class)->name('templates');    //
    Route::get('templates/create', TemplateCreate::class)->name('templates.create');    //
    Route::get('emails', EmailIndex::class)->name('emails');    //
    Route::get('emails/create', EmailCreate::class)->name('emails.create');    //
});

require __DIR__.'/auth.php';
