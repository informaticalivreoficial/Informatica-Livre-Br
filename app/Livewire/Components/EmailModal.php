<?php

namespace App\Livewire\Components;

use App\Mail\Admin\AdminSend;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;
use Livewire\Component;

class EmailModal extends Component
{
    public bool $show = false;

    public string $recipient = '';

    public string $recipientName = '';

    public string $assunto = '';

    public string $mensagem = '';

    #[On('open-email-modal')]
    public function open(string $recipient, string $recipientName = '')
    {
        $this->recipient = $recipient;
        $this->recipientName = $recipientName;
        $this->assunto = '';
        $this->mensagem = '';
        $this->show = true;
    }

    public function close()
    {
        $this->reset('show', 'recipient', 'recipientName', 'assunto', 'mensagem');
    }

    public function send()
    {
        $this->validate([
            'assunto' => 'required|string|max:255',
            'mensagem' => 'required|string|min:5',
        ]);

        $user = auth()->user();

        if (!$user) {
            $this->dispatch('swal', [
                'title' => 'Erro!',
                'text' => 'Você precisa estar autenticado para enviar e-mails.',
                'icon' => 'error',
            ]);
            return;
        }

        try {
            Mail::to($this->recipient)
                ->send(new AdminSend([
                    'reply_email' => $user->email,
                    'reply_name' => $user->name,
                    'destinatario_email' => $this->recipient,
                    'destinatario_nome' => $this->recipientName,
                    'assunto' => $this->assunto,
                    'mensagem' => $this->mensagem,
                ]));

            $this->close();

            $this->dispatch('swal', [
                'title' => 'Enviado!',
                'text' => 'E-mail enviado com sucesso.',
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false,
            ]);
        } catch (\Throwable $e) {
            $this->dispatch('swal', [
                'title' => 'Erro!',
                'text' => 'Não foi possível enviar o e-mail. Tente novamente.',
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.components.email-modal');
    }
}