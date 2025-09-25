<?php

namespace App\Livewire;

use App\Helper\StringHelper;
use App\Livewire\Forms\TemplateForm;
use App\Services\TemplateService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;

class TemplateCreate extends Component
{
    private TemplateService $service;
    public TemplateForm $form;
    #[Url]
    public string $id;
    public array $parameters = [];
    protected $listeners = ['updateParameters' => 'updateParameters'];
    public bool $isGenerateModal = false;
    public function boot(TemplateService $service): void
    {
        $this->service = $service;
    }
    public function mount(): void
    {
        if (isset($this->id)) {
            $template = $this->service->findOrThrow($this->id);
            $this->form = TemplateForm::from($this->form, $template->toArray());
            $this->parameters = $template->parameters ?? [];
        }
    }

    public function render()
    {
        return view('livewire.template-create');
    }

    public function save(): void
    {
        $this->form->validate();
        $data = array_merge(
            $this->form->toArray(),
            ['account_id' => Auth::user()->account_id]
        );
        if (isset($this->id)) {
            $this->service->updatePartial($this->id, $data);
            session()->flash('success', 'Template updated successfully.');
            // $this->redirectRoute('templates');
            return;
        }
        $this->service->create($data);
        session()->flash('success', 'Template created successfully.');
        // $this->redirectRoute('templates');
    }

    public function updateParameters(string $body): void
    {
        $this->parameters = StringHelper::extractParameters($body);
    }

    public function openModal(): void
    {
        $this->isGenerateModal = true;
    }
}
