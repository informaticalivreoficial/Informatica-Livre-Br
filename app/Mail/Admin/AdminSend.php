<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminSend extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->replyTo($this->data['reply_email'], $this->data['reply_name']);
        $this->to($this->data['destinatario_email'], $this->data['reply_name']);
        $this->from($this->data['reply_email'], $this->data['reply_name']);
        $this->subject($this->data['assunto']);
        if(!empty($this->data['copiapara'])){
            $this->cc($this->data['copiapara']);
        }        
        if(!empty($this->data['anexo'])){
            foreach($this->data['anexo'] as $anexo){
                $this->attach($anexo->getRealPath(), array(
                    'as'   => $anexo->getClientOriginalName(), 
                    'mime' => $anexo->getMimeType()));
            }            
        }        
        $this->markdown('emails.send-admin', [
            'mensagem' => $this->data['mensagem']
        ]);
        return $this;
        //    dd($this);
        // $copiaEmail = (!empty($this->data['copiapara']) ? ''.->cc($this->data['copiapara']).'' : '');
        // $destinatarioNome = (!empty($this->data['destinatario_nome']) ? $this->data['destinatario_nome'] : null);

        // return $this->replyTo($this->data['reply_email'], $this->data['reply_name'])
        //     ->to($this->data['destinatario_email'], $this->data['reply_name'])
        //     ->from($this->data['reply_email'], $this->data['reply_name'])
        //     $copiaEmail            
        //     ->subject($this->data['assunto'])
        //     // ->attach($this->data['anexo']->getRealPath(), array(
        //     //     'as'   => 'file-.' . $this->data['anexo']->getClientOriginalExtension(), 
        //     //     'mime' => $this->data['anexo']->getMimeType())
        //     // )
        //     ->markdown('emails.sendadmin', [
        //         'mensagem' => $this->data['mensagem']
        //     ]);
    }
}
