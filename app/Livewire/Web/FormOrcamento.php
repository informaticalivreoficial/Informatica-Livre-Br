<?php

namespace App\Livewire\Web;

use App\Models\Orcamento;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class FormOrcamento extends Component
{
    public Orcamento $orcamento;
 
    // Dados pessoais
    public string $nome      = '';
    public string $email     = '';
    public string $telefone  = '';
    public string $cpf       = '';
 
    // Dados empresa
    public string $empresa        = '';
    public string $email_empresa  = '';
    public string $cnpj           = '';
 
    // Endereço
    public string $cep         = '';
    public string $rua         = '';
    public string $bairro      = '';
    public string $numero      = '';
    public string $complemento = '';
    public string $cidade      = '';
    public string $uf          = '';
 
    // Contatos empresa
    public string $telefone_fixo = '';
    public string $celular       = '';
    public string $whatsapp      = '';
 
    // Adicionais
    public string $notas_adicionais = '';
 
    // Estado
    public bool   $enviado      = false;
    public bool   $buscandoCep  = false;
    public string $erro         = '';
 
    protected function rules(): array
    {
        return [
            'nome'             => 'required|min:3',
            'email'            => 'required|email',
            'telefone'         => 'required',
            'cpf'              => 'required',
        ];
    }
 
    protected $messages = [
        'nome.required'     => 'O nome é obrigatório.',
        'nome.min'          => 'O nome deve ter pelo menos 3 caracteres.',
        'email.required'    => 'O e-mail é obrigatório.',
        'email.email'       => 'Informe um e-mail válido.',
        'telefone.required' => 'O telefone é obrigatório.',
        'cpf.required'      => 'O CPF é obrigatório.',
    ];
 
    public function mount(Orcamento $orcamento): void
    {
        $this->orcamento = $orcamento;
        $this->nome      = $orcamento->name  ?? '';
        $this->email     = $orcamento->email ?? '';
        $this->telefone  = $orcamento->telefone ?? '';
    }
 
    public function buscarCep(): void
    {
        $cep = preg_replace('/\D/', '', $this->cep);
 
        if (strlen($cep) !== 8) {
            return;
        }
 
        $this->buscandoCep = true;
 
        try {
            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
 
            if ($response->successful() && !isset($response['erro'])) {
                $data            = $response->json();
                $this->rua       = $data['logradouro'] ?? '';
                $this->bairro    = $data['bairro']     ?? '';
                $this->cidade    = $data['localidade'] ?? '';
                $this->uf        = $data['uf']         ?? '';
            }
        } catch (\Exception $e) {
            // silently fail
        }
 
        $this->buscandoCep = false;
    }
 
    public function enviar(): void
    {
        $this->erro = '';
        $this->validate();
 
        try {
            $this->orcamento->update([
                'name'             => $this->nome,
                'email'            => $this->email,
                'telefone'         => $this->telefone,
                'cpf'              => $this->cpf,
                'empresa'          => $this->empresa,
                'email_empresa'    => $this->email_empresa,
                'cnpj'             => $this->cnpj,
                'cep'              => $this->cep,
                'rua'              => $this->rua,
                'bairro'           => $this->bairro,
                'numero'           => $this->numero,
                'complemento'      => $this->complemento,
                'cidade'           => $this->cidade,
                'uf'               => $this->uf,
                'telefone_fixo'    => $this->telefone_fixo,
                'celular'          => $this->celular,
                'whatsapp'         => $this->whatsapp,
                'notas_adicionais' => $this->notas_adicionais,
                'status'           => 'respondido',
            ]);
 
            $this->enviado = true;
 
        } catch (\Exception $e) {
            $this->erro = 'Ocorreu um erro ao enviar. Tente novamente.';
        }
    }
    
    public function render()
    {
        return view('livewire.web.form-orcamento');
    }
}
