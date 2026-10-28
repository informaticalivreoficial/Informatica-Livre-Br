<?php

namespace App\Livewire\Dashboard;

use App\Services\TwoFactorService;
use App\Traits\WithToastr;
use Livewire\Attributes\Title;
use Livewire\Component;

class SecuritySettings extends Component
{
    use WithToastr;

    public string $current_password = '';
    public array $recoveryCodes = [];
    public bool $showingCodes = false;

    public function regenerate(TwoFactorService $service)
    {
        $this->validate([
            'current_password' => 'required|current_password',
        ], [], [
            'current_password' => 'senha atual',
        ]);

        $this->recoveryCodes = $service->generateRecoveryCodes(auth()->user());
        $this->showingCodes = true;
        $this->current_password = '';

        $this->toastSuccess('Novos códigos de recuperação gerados.');
    }

    public function dismissCodes()
    {
        $this->reset('recoveryCodes', 'showingCodes');
    }

    #[Title('Segurança')]
    public function render(TwoFactorService $service)
    {
        return view('livewire.dashboard.security-settings', [
            'confirmedAt' => auth()->user()->two_factor_confirmed_at,
            'remainingCodes' => $service->remainingRecoveryCodes(auth()->user()),
        ])->with('title', 'Segurança');
    }
}
