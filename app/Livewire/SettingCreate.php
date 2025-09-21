<?php

namespace App\Livewire;

use App\Livewire\Forms\SettingForm;
use App\Services\SettingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;

class SettingCreate extends Component
{
    private SettingService $service;
    public SettingForm $form;
    #[Url]
    public string $id;
    public function boot(SettingService $service): void
    {
        $this->service = $service;
    }
    public function mount():void
    {
        if (isset($this->id)) {
            $setting = $this->service->findOrThrow($this->id, [
                'account_id' => Auth::user()->account_id
            ]);
            $this->form = SettingForm::from($this->form, $setting->toArray());
        }
    }

    public function render()
    {
        return view('livewire.setting-create');
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
            session()->flash('success', 'Setting updated successfully.');
            $this->redirectRoute('settings');
            return;
        }
        $this->service->create($data);
        session()->flash('success', 'Setting created successfully.');
        $this->redirectRoute('settings');
    }
}
