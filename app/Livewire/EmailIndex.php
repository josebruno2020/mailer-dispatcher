<?php

namespace App\Livewire;

use App\Services\EmailService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EmailIndex extends Component
{
    private EmailService $service;
    public function boot(EmailService $service): void
    {
        $this->service = $service;
    }
    public function render()
    {
        $emails = $this->service->paginate([
            'account_id' => Auth::user()->account_id
        ]);
        return view('livewire.email-index', compact('emails'));
    }
}
