<?php

namespace App\Livewire;

use App\Models\Email;
use App\Services\EmailService;
use Livewire\Attributes\Url;
use Livewire\Component;

class EmailCreate extends Component
{
    private EmailService $service;
    #[Url]
    public string $id;
    public Email $email;
    public function boot(EmailService $service): void
    {
        $this->service = $service;
    }
    public function mount(): void
    {
        if (isset($this->id)) {
            $this->email = $this->service->findOrThrow($this->id);
        }
    }
    public function render()
    {
        return view('livewire.email-create');
    }

    public function reload(): void
    {
        if (isset($this->id)) {
            $this->email = $this->service->findOrThrow($this->id);
        }
    }

    public function resend(): void
    {
        if (isset($this->id)) {
            $email = $this->service->resend($this->id);
            session()->flash('success', 'Email resent successfully.');
            $this->redirectRoute('emails.create', ['id' => $email->id]);
            return;
        }
    }

    public function delete(): void
    {
        if (isset($this->id)) {
            $this->service->deleteById($this->id);
            session()->flash('success', 'Email deleted successfully.');
            $this->redirectRoute('emails');
            return;
        }
    }
}
