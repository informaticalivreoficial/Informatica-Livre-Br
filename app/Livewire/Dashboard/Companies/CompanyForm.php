<?php

namespace App\Livewire\Dashboard\Companies;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CompanyForm extends Component
{
    use WithFileUploads;

    public ?Company $company = null;

    public $logo;
    public ?string $logoPath = null;

    

    public ?string $social_name = null;
    public ?string $alias_name = null;
    public ?string $document_company = null;
    public ?string $document_company_secondary = null;
    public ?string $information = null;
    public ?string $status = null;
    //Contact
    public $phone, $cell_phone, $whatsapp, $email, $additional_email, $telegram;
    //Address
    public $zipcode = '', $street, $neighborhood, $city, $state, $complement, $number;

    protected function rules()
    {
        $companyId = $this->company->id ?? null;

        return [
            'zipcode' => 'required|min:8|max:10',
            'email' => ['required', 'email', Rule::unique('companies', 'email')->ignore($companyId)],
            'cell_phone' => 'required|string|min:15',
            'logo' => 'nullable|image|max:1024',
        ];
    }

    public function render()
    {
        $title = $this->company->exists ? 'Editar Empresa' : 'Cadastrar Empresa';
        return view('livewire.dashboard.companies.company-form')->with('title', $title);
    }

    public function mount(Company $company)
    {
        $this->company = $company;

        if ($company->exists) {
            $this->logoPath = $company->logo; // 👈 essencial
            $this->fillFromCompany($company);
        }
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->logo) {

            // 🗑 Apaga logo antigo (se existir)
            if ($this->company->exists && $this->logoPath) {
                Storage::disk('public')->delete($this->logoPath);
            }

            // 📦 Salva novo logo
            $this->logoPath = $this->logo->store('company', 'public');
        }

        $data = [
            'logo' => $this->logoPath,
            'social_name' => $this->social_name,
            'alias_name' => $this->alias_name,
            'zipcode' => $this->zipcode,
            'street' => $this->street,
            'neighborhood' => $this->neighborhood,
            'city' => $this->city,
            'state' => $this->state,
            'complement' => $this->complement,
            'number' => $this->number,
            'email' => $validated['email'],
            'additional_email' => $this->additional_email,
            'document_company' => $this->document_company,
            'document_company_secondary' => $this->document_company_secondary,
            'information' => $this->information,
            'status' => $this->status ?? 0,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'telegram' => $this->telegram,
            'cell_phone' => $this->cell_phone,
        ];

        if ($this->company->exists) {
            $this->company->update($data);
            $this->dispatch('empresa-atualizada');
        } else {
            $this->company = Company::create($data);
            $this->dispatch('empresa-cadastrada');

            return redirect()->route('companies.edit', $this->company);
        }
    }

    public function updatedZipcode(string $value)
    {
        $cep = preg_replace('/[^0-9]/', '', $value);

        if (strlen($cep) === 8) {
            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/")->json();

            if (!isset($response['erro'])) {
                $this->street = $response['logradouro'] ?? '';
                $this->neighborhood = $response['bairro'] ?? '';
                $this->state = $response['uf'] ?? '';
                $this->city = $response['localidade'] ?? '';
                //$this->configData['complement'] = $response['complemento'] ?? '';
            } else {
                $this->addError('zipcode', 'CEP não encontrado.'); 
            }
        }
    }

    protected function fillFromCompany(Company $company): void
    {
        $this->social_name = $company->social_name;
        $this->alias_name = $company->alias_name;
        $this->zipcode = $company->zipcode;
        $this->street = $company->street;
        $this->neighborhood = $company->neighborhood;
        $this->city = $company->city;
        $this->state = $company->state;
        $this->complement = $company->complement;
        $this->number = $company->number;
        $this->email = $company->email;
        $this->additional_email = $company->additional_email;
        $this->document_company = $company->document_company;
        $this->document_company_secondary = $company->document_company_secondary;
        $this->information = $company->information;
        $this->status = (string) ($company->status ?? 0);
        $this->phone = $company->phone;
        $this->cell_phone = $company->cell_phone;
        $this->whatsapp = $company->whatsapp;
        $this->telegram = $company->telegram;
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
