<x-mail::message>
# Bem-vindo(a) ao sistema MDR

Prezado(a) **{{ $userName }}**,

A sua conta de **{{ $role }}** foi criada com sucesso no sistema **MDR - Mecanismo de Diálogo e Reclamações** da BIOFUND.

---

## As suas credenciais de acesso

| | |
|---|---|
| **Email** | {{ $userEmail }} |
| **Senha temporária** | `{{ $temporaryPassword }}` |

---

<x-mail::button :url="$loginUrl" color="success">
Aceder ao Sistema MDR
</x-mail::button>

> **Importante:** Por motivos de segurança, altere a sua senha após o primeiro login.

---

Se tiver alguma dúvida ou dificuldade de acesso, contacte o administrador do sistema.

Com os melhores cumprimentos,
**Equipa MDR - BIOFUND**
</x-mail::message>
