<?php

namespace App\Livewire;

use App\Services\TemplateService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TemplateIndex extends Component
{
    use WithPagination;
    private TemplateService $service;
    #[Url]
    public string $name = '';
    #[Url]
    public string $subject = '';
    public function boot(TemplateService $service): void
    {
        $this->service = $service;
    }

    public function render()
    {
        $templates = $this->service->paginate([
            'account_id' => Auth::user()->account_id,
            'name' => 'like:' . $this->name,
            'subject' => 'like:' . $this->subject,
        ]);
        return view('livewire.template-index', compact('templates'));
    }

    public function search(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->name = '';
        $this->resetPage();
    }
}
