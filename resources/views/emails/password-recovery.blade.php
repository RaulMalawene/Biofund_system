<x-mail::message>
# Recuperação de Senha - Sistema MDR

Prezado(a) **{{ $userName }}**,

Recebemos um pedido de recuperação de senha para a sua conta de **{{ $role }}** no sistema **MDR - Mecanismo de Diálogo e Reclamações** da BIOFUND.

Foi gerada uma nova senha temporária para o seu acesso.

---

## A sua nova senha de acesso

| | |
|---|---|
| **Email** | {{ $userEmail }} |
| **Nova senha temporária** | `{{ $temporaryPassword }}` |

---

<x-mail::button :url="$loginUrl" color="success">
Aceder ao Sistema MDR
</x-mail::button>

> **Importante:** Por motivos de segurança, altere esta senha temporária após efectuar login.

---

Se não solicitou esta recuperação de senha, contacte imediatamente o administrador do sistema.

Com os melhores cumprimentos,
**Equipa MDR - BIOFUND**
</x-mail::message>
