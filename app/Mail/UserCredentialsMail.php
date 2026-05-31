<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User   $user,
        public readonly string $temporaryPassword,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'MDR - As suas credenciais de acesso',
        );
    }

    public function content(): Content
    {
        $role = match ($this->user->role->value ?? (string) $this->user->role) {
            'gestor'      => 'Gestor',
            'funcionario' => 'Funcionário',
            'admin'       => 'Administrador',
            default       => 'Utilizador',
        };

        return new Content(
            markdown: 'emails.user-credentials',
            with: [
                'userName'          => $this->user->name,
                'userEmail'         => $this->user->email,
                'temporaryPassword' => $this->temporaryPassword,
                'loginUrl'          => rtrim(config('app.url'), '/') . '/acessoRestrito',
                'role'              => $role,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
