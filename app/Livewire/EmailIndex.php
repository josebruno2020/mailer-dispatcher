<?php

namespace App\Livewire;

use App\Enums\EmailStatusEnum;
use App\Services\EmailService;
use App\Services\SettingService;
use App\Services\TemplateService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class EmailIndex extends Component
{
    use WithPagination;
    private EmailService $service;
    private SettingService $settingService;
    private TemplateService $templateService;
    public array $settings = [];
    public array $templates = [];
    public array $statuses = [];
    #[Url]
    public string $setting_id;
    public string $template_id;
    #[Url]
    public string $status;
    #[Url]
    public string $email_id;
    public function boot(
        EmailService $service,
        SettingService $settingService,
        TemplateService $templateService
    ): void {
        $this->service = $service;
        $this->settingService = $settingService;
        $this->templateService = $templateService;
    }
    public function mount(): void
    {
        $accountFilter = ['account_id' => Auth::user()->account_id];

        $this->settings = $this->settingService->getAll($accountFilter)
            ->pluck('name', 'id')->toArray();
        $this->templates = $this->templateService->getAll($accountFilter)
            ->pluck('name', 'id')->toArray();
        $allEnumStatus = EmailStatusEnum::cases();
        $this->statuses = ['' => 'All'];
        foreach ($allEnumStatus as $status) {
            $this->statuses[$status->value] = $status->name;
        }
        $this->settings = array_merge(['' => 'All'], $this->settings);
        $this->templates = array_merge(['' => 'All'], $this->templates);
    }
    public function render()
    {
        $filters = [
            'account_id' => Auth::user()->account_id,
        ];
        if (isset($this->status) && $this->status !== '') {
            $filters['status'] = $this->status;
        }
        if (isset($this->setting_id) && $this->setting_id !== '') {
            $filters['setting_id'] = $this->setting_id;
        }
        if (isset($this->template_id) && $this->template_id !== '') {
            $filters['template_id'] = $this->template_id;
        }
        if (isset($this->email_id) && $this->email_id !== '') {
            $filters['id'] = $this->email_id;
        }
        $emails = $this->service->paginate($filters);
        return view('livewire.email-index', compact('emails'));
    }

    public function search(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->status = '';
        $this->setting_id = '';
        $this->template_id = '';
        $this->email_id = '';
        $this->resetPage();
    }
}
