<?php

namespace App\Livewire\Web;

use App\Mail\Atendimento;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name     = '';
    public string $email    = '';
    public string $phone    = '';
    public string $subject  = '';
    public string $message  = '';
    public bool   $sent     = false;

    // Campos honeypot (anti-spam)
    public $bairro;
    public $cidade;

    protected function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ];
    }

    protected $messages = [
        'name.required'    => 'Informe seu nome.',
        'email.required'   => 'Informe seu e-mail.',
        'email.email'      => 'Informe um e-mail válido.',
        'subject.required' => 'Informe o assunto.',
        'message.required' => 'Escreva sua mensagem.',
        'message.min'      => 'A mensagem deve ter pelo menos 10 caracteres.',
    ];

    public function send(): void
    {     
        if (!empty($this->bairro) || !empty($this->cidade)) {
            return;
        }

        $validated = $this->validate();
        
        Mail::send(new Atendimento($validated));
        $this->reset(['name', 'email', 'phone', 'subject', 'message']);
        $this->sent = true;        
    }

    public function render()
    {
        return view('livewire.web.contact-form');
    }
}
