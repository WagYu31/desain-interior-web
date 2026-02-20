<x-mail::message>
# Pesan Kontak Baru Diterima

Anda telah menerima pesan baru melalui formulir kontak di website Anda.

---

### Detail Pengirim:
- **Nama:** {{ $contactMessage->name }}
- **Email:** <a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a>

---

**Subjek:** {{ $contactMessage->subject }}

### Isi Pesan:
{!! nl2br(e($contactMessage->message)) !!}

<br>
<br>

Terima kasih,<br>
Sistem Website {{ config('app.name') }}
</x-mail::message>