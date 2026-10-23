<?php

namespace App\Livewire\Dashboard\Users;

use App\Helpers\Renato;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $userId;

    public $foto;
    public $fotoUrl;

    public $name, $birthday, $gender, $naturalness, $civil_status, $code, $avatar;

    public $cpf, $rg, $rg_expedition;

    public $zipcode = '', $street, $neighborhood, $city, $state, $complement, $number;

    public $phone, $cell_phone, $whatsapp, $email, $additional_email, $telegram;

    public $facebook, $instagram, $linkedin;

    public array $roles = ['employee'];

    public $password;
    public $password_confirmation;

    public function mount($userId = null)
    {
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $this->fill([
                    'userId' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->avatar,
                    'birthday' => $user->birthday,
                    'gender' => $user->gender,
                    'naturalness' => $user->naturalness,
                    'civil_status' => $user->civil_status,
                    'rg' => $user->rg,
                    'rg_expedition' => $user->rg_expedition,
                    'cpf' => $user->cpf,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'cell_phone' => $user->cell_phone,
                    'whatsapp' => $user->whatsapp,
                    'additional_email' => $user->additional_email,
                    'telegram' => $user->telegram,
                    'number' => $user->number,
                    'zipcode' => $user->zipcode,
                    'street' => $user->street,
                    'neighborhood' => $user->neighborhood,
                    'city' => $user->city,
                    'state' => $user->state,
                    'complement' => $user->complement,
                    'facebook' => $user->facebook,
                    'instagram' => $user->instagram,
                    'linkedin' => $user->linkedin,
                    'roles' => $user->getRoleNames()->toArray(),
                ]);
            }
        }
    }

    public function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'birthday' => ['required', 'date_format:d/m/Y', 'before:today'],
            'cpf' => [
                'required',
                'string',
                'min:11',
                'max:14',
                Rule::unique('users', 'cpf')->ignore($this->userId),
                function ($attribute, $value, $fail) {
                    if (!Renato::validaCPF($value)) {
                        $fail('O CPF informado é inválido.');
                    }
                },
            ],
            'cell_phone' => ['required', 'string', 'min:10', 'max:15'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->userId)],
            'zipcode' => ['required', 'string', 'max:10'],
            'foto' => ['nullable', 'image', 'max:1024'],
        ];

        if (!$this->userId) {
            $rules['password'] = ['required', 'confirmed', 'min:6'];
        }

        return $rules;
    }

    public function render()
    {
        return view('livewire.dashboard.users.form');
    }

    public function save()
    {
        $this->userId ? $this->update() : $this->create();
    }

    public function create()
    {
        $validated = $this->validate();

        $caminhoFoto = null;
        if ($this->foto) {
            $caminhoFoto = $this->foto->store('client', config('filesystems.default'));
        }

        $user = User::create([
            'name' => $validated['name'],
            'password' => Hash::make($this->password),
            'avatar' => $caminhoFoto,
            'birthday' => $validated['birthday'],
            'gender' => $this->gender,
            'naturalness' => $this->naturalness,
            'civil_status' => $this->civil_status,
            'rg' => $this->rg,
            'rg_expedition' => $this->rg_expedition,
            'cpf' => $validated['cpf'],
            'email' => $validated['email'],
            'phone' => $this->phone,
            'cell_phone' => $validated['cell_phone'],
            'whatsapp' => $this->whatsapp,
            'additional_email' => $this->additional_email,
            'telegram' => $this->telegram,
            'number' => $this->number,
            'zipcode' => $validated['zipcode'],
            'street' => $this->street,
            'neighborhood' => $this->neighborhood,
            'city' => $this->city,
            'state' => $this->state,
            'complement' => $this->complement,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'linkedin' => $this->linkedin,
        ]);

        $user->syncRoles($this->roles);

        $this->dispatch('swal', [
            'title' => 'Sucesso!',
            'text' => 'Cliente cadastrado com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
            'redirectUrl' => route('users.edit', $user->id),
        ]);
    }

    public function update()
    {
        $user = User::findOrFail($this->userId);

        $validated = $this->validate();

        if ($this->foto) {
            if ($this->avatar && Storage::disk()->exists($this->avatar)) {
                Storage::disk()->delete($this->avatar);
            }

            $user->update(['avatar' => $this->foto->store('client', config('filesystems.default'))]);
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'linkedin' => $this->linkedin,
            'birthday' => $validated['birthday'],
            'gender' => $this->gender,
            'naturalness' => $this->naturalness,
            'civil_status' => $this->civil_status,
            'rg' => $this->rg,
            'rg_expedition' => $this->rg_expedition,
            'cpf' => $validated['cpf'],
            'phone' => $this->phone,
            'cell_phone' => $validated['cell_phone'],
            'additional_email' => $this->additional_email,
            'whatsapp' => $this->whatsapp,
            'number' => $this->number,
            'zipcode' => $validated['zipcode'],
            'street' => $this->street,
            'neighborhood' => $this->neighborhood,
            'city' => $this->city,
            'state' => $this->state,
            'complement' => $this->complement,
            'telegram' => $this->telegram,
        ]);

        $user->syncRoles($this->roles);

        $this->reset('foto', 'fotoUrl');

        $this->dispatch('swal', [
            'title' => 'Sucesso!',
            'text' => 'Cliente atualizado com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function updatedZipcode(string $value)
    {
        $this->zipcode = preg_replace('/[^0-9]/', '', $value);

        if (strlen($this->zipcode) === 8) {
            $response = Http::get("https://viacep.com.br/ws/{$this->zipcode}/json/")->json();

            if (!isset($response['erro'])) {
                $this->street = $response['logradouro'] ?? '';
                $this->neighborhood = $response['bairro'] ?? '';
                $this->state = $response['uf'] ?? '';
                $this->city = $response['localidade'] ?? '';
                $this->complement = $response['complemento'] ?? '';
            } else {
                $this->addError('zipcode', 'CEP não encontrado.');
            }
        }
    }

    public function updatedFoto()
    {
        $this->validateOnly('foto');
        $this->fotoUrl = $this->foto->temporaryUrl();
    }
}