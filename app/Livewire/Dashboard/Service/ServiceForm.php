<?php

namespace App\Livewire\Dashboard\Service;

use App\Models\Service;
use App\Models\ServiceCategorie;
use Livewire\Component;

class ServiceForm extends Component
{
    public ?Service $service = null;

    public string $name = '';
    public ?int $category_id = null;
    public ?string $description = null;
    public float $price = 0;
    public string $billing_type = 'one_time';
    public ?string $interval = null;
    public bool $is_public = false;
    public int $status = 1;

    public function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'category_id' => 'nullable|exists:service_categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_type' => 'required|in:one_time,recurring',
            'interval' => 'nullable|required_if:billing_type,recurring',
            'is_public' => 'boolean',
            'status' => 'boolean',
        ];
    }

    public function mount()
    {
        if ($this->service) {
            $this->fill([
                'name'         => $this->service->name,
                'category_id'  => $this->service->category_id,
                'description'  => $this->service->description,
                'price'        => $this->service->price,
                'billing_type' => $this->service->billing_type,
                'interval'     => $this->service->interval,
                'is_public'    => $this->service->is_public,
                'status'       => (int) $this->service->status,
            ]);
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'user_id'      => auth()->id(), // ✅ obrigatório
            'name'         => $this->name,
            'category_id'  => $this->category_id,
            'description'  => $this->description,
            'price'        => $this->price,
            'billing_type' => $this->billing_type,
            'interval'     => $this->billing_type === 'recurring'
                                ? $this->interval
                                : null,
            'is_public'    => $this->is_public,
            'status'       => (bool) $this->status,
        ];

        Service::updateOrCreate(
            ['id' => $this->service?->id],
            $data
        );

        $this->dispatch('swal', [
            'title' => 'Sucesso',
            'text' => 'Serviço salvo com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);

        return redirect()->route('services.index');
    }

    public function render()
    {
        $title = $this->service?->exists ? 'Editar Serviço - ' . $this->service->name : 'Cadastrar Serviço';
        return view('livewire.dashboard.service.service-form', [
            'categories' => ServiceCategorie::all(),
        ])->with('title', $title);
    }
}
