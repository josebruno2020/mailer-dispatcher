<?php

namespace App\Livewire;

use App\Services\SettingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SettingIndex extends Component
{
    use WithPagination;
    private SettingService $service;

    public function boot(SettingService $service): void
    {
        $this->service = $service;
    }

    public function render()
    {
        $settings = $this->service->paginate([
            'account_id' => Auth::user()->account_id
        ]);
        return view('livewire.setting-index', compact('settings'));
    }
}
