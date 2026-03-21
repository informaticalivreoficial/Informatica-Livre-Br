<?php

namespace App\Livewire\Dashboard\Safe;

use App\Models\Safe;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\Component;

class SafeForm extends Component
{
    use WithFileUploads;

    public ?Safe $safe = null;

    public $safeId = null;

    public $logo;
    public ?string $logoPath = null;

    public string $title = '';

    public ?string $email = null;
    public ?string $login = null;
    public ?string $link = null;
    public ?string $password = null;
    public ?string $token = null;
    public ?string $content = null;
    public bool $status = true;

    public function rules()
    {
        return [
            'title' => 'required|string|min:3',
            'email' => 'nullable|email',
            'login' => 'nullable|string',
            'link' => 'nullable|string',
            'password' => 'nullable|string|min:4',
            'token' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'boolean',
        ];
    }

    public function mount()
    { 
        if ($this->safe) {
            $this->safeId   = $this->safe->id;
            $this->logoPath = $this->safe->logo;

            $this->fillFromCompany($this->safe);
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->logo) {

            // 🗑 Apaga logo antigo (se existir)
            if ($this->safeId && $this->logoPath) {
                Storage::disk('public')->delete($this->logoPath);
            }

            // 📦 Salva novo logo
            $this->logoPath = $this->logo->store('safe', 'public');
        }

        $data = [
            'title'   => $this->title,
            'logo'    => $this->logoPath,
            'email'   => $this->email,
            'login'   => $this->login,
            'link'    => $this->link,
            'token'   => $this->token,
            'status'  => $this->status,
            'content' => $this->content,
        ];

        if ($this->password) {
            $data['password'] = $this->password;
        }

        if ($this->safeId ) {
            $this->safe->update($data);
            $text = 'Acesso atualizado com sucesso.';
        } else {
            $this->safe = Safe::create($data);
            $this->dispatch('empresa-cadastrada');
            $text = 'Acesso criado com sucesso.';
        }
        
        $this->dispatch('swal', [
            'title' => 'Sucesso!',
            'text' => $text,
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function render()
    {
        $title = $this->safe?->exists ? 'Editar Acesso - ' . $this->safe->title : 'Cadastrar Acesso';
        return view('livewire.dashboard.safe.safe-form')->with('page', $title);
    }

    protected function fillFromCompany(Safe $safe): void
    {
        $this->title   = $safe->title;
        $this->email   = $safe->email;
        $this->login   = $safe->login;
        $this->link    = $safe->link;
        $this->token   = $safe->token;
        $this->content = $safe->content;
        $this->status  = (bool) $safe->status;

        // 🔐 nunca preencher senha
        $this->password = null;
    }

    public function getLogoUrlProperty()
    {
        if ($this->logo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            return $this->logo->temporaryUrl();
        }

        if ($this->logoPath && Storage::disk('public')->exists($this->logoPath)) {
            return Storage::disk('public')->url($this->logoPath);
        }

        return asset('theme/images/image.jpg');
    }
}
