<?php

namespace App\Livewire;

use App\Services\TemplateService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TemplateIndex extends Component
{
    private TemplateService $service;
    public function boot(TemplateService $service): void
    {
        $this->service = $service;
    }
    public function render()
    {
        $templates = $this->service->paginate([
            'account_id' => Auth::user()->account_id
        ]);
        return view('livewire.template-index', compact('templates'));
    }
}
