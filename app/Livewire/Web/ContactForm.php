<?php

namespace App\Livewire\Web;

use Livewire\Component;

class ContactForm extends Component
{
    public string $name     = '';
    public string $email    = '';
    public string $phone    = '';
    public string $subject  = '';
    public string $message  = '';
    public bool   $sent     = false;

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
        $this->validate();

        try {
            Mail::raw(
                "Nome: {$this->name}\nE-mail: {$this->email}\nTelefone: {$this->phone}\n\nMensagem:\n{$this->message}",
                function ($mail) {
                    $mail->to('suporte@informaticalivre.com.br')
                        ->subject("[Contato] {$this->subject}");
                }
            );

            $this->reset(['name', 'email', 'phone', 'subject', 'message']);
            $this->sent = true;

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Erro ao enviar',
                'text'  => 'Ocorreu um erro ao enviar sua mensagem. Tente novamente.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.web.contact-form');
    }
}
