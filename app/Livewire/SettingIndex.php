<?php

namespace App\Livewire;

use App\Services\SettingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SettingIndex extends Component
{
    use WithPagination;
    private SettingService $service;
    #[Url]
    public string $name = '';
    #[Url]
    public string $host = '';
    #[Url]
    public string $username = '';

    public function boot(SettingService $service): void
    {
        $this->service = $service;
    }

    public function render()
    {
        $settings = $this->service->paginate([
            'account_id' => Auth::user()->account_id,
            'name' => 'like:' . $this->name,
            'host' => 'like:' . $this->host,
            'username' => 'like:' . $this->username,
        ]);
        return view('livewire.setting-index', compact('settings'));
    }

    public function search(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->name = '';
        $this->host = '';
        $this->username = '';
        $this->resetPage();
    }
}
