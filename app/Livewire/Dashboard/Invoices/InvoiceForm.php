<?php

namespace App\Livewire\Dashboard\Invoices;

use App\Models\Company;
use App\Models\Invoice;
use App\Services\PagHiperService;
use Livewire\Component;

class InvoiceForm extends Component
{
    public ?Invoice $invoice = null;

    public string $payer_type = 'custom';

    public $company_id;

    public string $payer_name = '';
    public string $payer_document = '';
    public string $payer_email = '';
    public string $payer_phone = '';
    public string $payer_zipcode = '';
    public string $payer_street = '';
    public string $payer_number = '';
    public string $payer_complement = '';
    public string $payer_neighborhood = '';
    public string $payer_city = '';
    public string $payer_state = '';

    public string $description = '';
    public $amount = 0;
    public $due_date;
    public bool $generate_charge = true;

    public $companies = [];

    public function mount(?Invoice $invoice = null)
    {
        $this->companies = Company::orderBy('alias_name')->get();
        $this->due_date = now()->addDays(7)->toDateString();
        $this->description = 'Serviços prestados';

        if (!$invoice || !$invoice->exists) {
            return;
        }

        $this->invoice = $invoice->loadMissing('subscription.service');
        $this->description = $invoice->description
            ?: $invoice->subscription?->service?->name
            ?: 'Serviços prestados';
        $this->amount = (float) $invoice->amount;
        $this->generate_charge = false;

        if ($invoice->company_id) {
            $this->payer_type = 'company';
            $this->company_id = $invoice->company_id;

            return;
        }

        $this->payer_type = 'custom';
        $this->payer_name = $invoice->payer_name ?? '';
        $this->payer_document = $invoice->payer_document ?? '';
        $this->payer_email = $invoice->payer_email ?? '';
        $this->payer_phone = $invoice->payer_phone ?? '';
        $this->payer_zipcode = $invoice->payer_zipcode ?? '';
        $this->payer_street = $invoice->payer_street ?? '';
        $this->payer_number = $invoice->payer_number ?? '';
        $this->payer_complement = $invoice->payer_complement ?? '';
        $this->payer_neighborhood = $invoice->payer_neighborhood ?? '';
        $this->payer_city = $invoice->payer_city ?? '';
        $this->payer_state = $invoice->payer_state ?? '';
    }

    protected function rules()
    {
        return [
            'payer_type'          => ['required', 'in:company,custom'],
            'company_id'          => ['exclude_if:payer_type,custom', 'required', 'exists:companies,id'],
            'payer_name'          => ['exclude_if:payer_type,company', 'required', 'string', 'max:191'],
            'payer_document'      => ['exclude_if:payer_type,company', 'required', 'string', 'max:20', function ($attribute, $value, $fail) {
                $digits = preg_replace('/\D/', '', (string) $value);

                if (!in_array(strlen($digits), [11, 14], true)) {
                    $fail('Informe um CPF (11 dígitos) ou CNPJ (14 dígitos) válido.');
                }
            }],
            'payer_email'         => ['exclude_if:payer_type,company', 'required', 'email', 'max:191'],
            'payer_phone'         => ['exclude_if:payer_type,company', 'nullable', 'string', 'max:30'],
            'payer_zipcode'       => ['exclude_if:payer_type,company', 'nullable', 'string', 'max:10'],
            'payer_street'        => ['exclude_if:payer_type,company', 'nullable', 'string', 'max:191'],
            'payer_number'        => ['exclude_if:payer_type,company', 'nullable', 'string', 'max:20'],
            'payer_complement'    => ['exclude_if:payer_type,company', 'nullable', 'string', 'max:60'],
            'payer_neighborhood'  => ['exclude_if:payer_type,company', 'nullable', 'string', 'max:80'],
            'payer_city'          => ['exclude_if:payer_type,company', 'nullable', 'string', 'max:80'],
            'payer_state'         => ['exclude_if:payer_type,company', 'nullable', 'string', 'max:2'],
            'description'         => ['required', 'string', 'max:255'],
            'amount'              => ['required', 'numeric', 'min:0.01'],
            'due_date'            => ['required', 'date', 'after_or_equal:today'],
            'generate_charge'     => ['boolean'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'payer_type'         => 'tipo de pagador',
            'company_id'         => 'empresa',
            'payer_name'         => 'nome do pagador',
            'payer_document'     => 'CPF/CNPJ',
            'payer_email'        => 'e-mail',
            'payer_phone'        => 'telefone',
            'payer_zipcode'      => 'CEP',
            'payer_street'       => 'logradouro',
            'payer_number'       => 'número',
            'payer_complement'   => 'complemento',
            'payer_neighborhood' => 'bairro',
            'payer_city'         => 'cidade',
            'payer_state'        => 'UF',
            'description'        => 'descrição',
            'amount'             => 'valor',
            'due_date'           => 'vencimento',
        ];
    }

    public function save()
    {
        $this->amount = $this->parsePrice($this->amount);
        $this->payer_state = strtoupper(trim($this->payer_state));

        $this->validate();

        $isCustom = $this->payer_type === 'custom';

        $invoice = Invoice::create(array_merge([
            'subscription_id' => null,
            'company_id'      => $isCustom ? null : $this->company_id,
            'description'     => $this->description,
            'amount'          => $this->amount,
            'due_date'        => $this->due_date,
            'status'          => 'pending',
        ], $isCustom ? [
            'payer_name'         => $this->payer_name,
            'payer_document'     => $this->payer_document,
            'payer_email'        => $this->payer_email,
            'payer_phone'        => $this->payer_phone ?: null,
            'payer_zipcode'      => $this->payer_zipcode ?: null,
            'payer_street'       => $this->payer_street ?: null,
            'payer_number'       => $this->payer_number ?: null,
            'payer_complement'   => $this->payer_complement ?: null,
            'payer_neighborhood' => $this->payer_neighborhood ?: null,
            'payer_city'         => $this->payer_city ?: null,
            'payer_state'        => $this->payer_state ?: null,
        ] : []));

        if (!$this->generate_charge) {
            $this->dispatch('swal', [
                'icon'        => 'success',
                'title'       => 'Fatura criada',
                'text'        => 'A fatura foi salva e pode ser cobrada depois.',
                'redirectUrl' => route('invoices.show', $invoice),
            ]);

            return;
        }

        try {
            $response = app(PagHiperService::class)->createInvoice($invoice);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon'        => 'warning',
                'title'       => 'Fatura criada, mas a cobrança falhou',
                'text'        => $e->getMessage(),
                'redirectUrl' => route('invoices.show', $invoice),
            ]);

            return;
        }

        $invoice->update([
            'gateway'           => 'paghiper',
            'gateway_reference' => $response['transaction_id'] ?? null,
            'payment_url'       => $response['bank_slip']['url_slip'] ?? null,
            'pix_qrcode'        => $response['pix']['qrcode_image'] ?? null,
            'payment_method'    => 'boleto',
        ]);

        $this->dispatch('swal', [
            'icon'              => 'success',
            'title'             => 'Fatura criada',
            'text'              => 'A cobrança foi gerada com sucesso.',
            'timer'             => 2000,
            'showConfirmButton' => false,
            'redirectUrl'       => route('invoices.show', $invoice),
        ]);
    }

    private function parsePrice($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        $value = str_replace('.', '', (string) $value);
        $value = str_replace(',', '.', $value);

        return (float) $value;
    }

    public function render()
    {
        return view('livewire.dashboard.invoices.invoice-form')
            ->with('title', $this->invoice ? 'Duplicar Fatura' : 'Nova Fatura');
    }
}
