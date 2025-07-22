@component('mail::message')
# 📢 Pemberitahuan Resmi dari Sekolah

**Perihal:**  
### {{ $surat->judul }}

Yth. Bapak/Ibu Orang Tua/Wali Murid, SDI Al Muhajirin

Semoga Bapak/Ibu senantiasa dalam keadaan sehat dan mendapatkan keberkahan dalam setiap aktivitasnya.

Melalui surat elektronik ini, kami menyampaikan bahwa sekolah telah menerbitkan **surat edaran resmi** yang memuat informasi penting terkait kegiatan dan kebijakan sekolah.

---

@component('mail::panel')
📝 **Rincian Surat Edaran:**  
Judul: **{{ $surat->judul }}**  
Tanggal: {{ \Carbon\Carbon::now()->format('d M Y') }}
@endcomponent

---

Bapak/Ibu dapat membaca dan mengunduh surat edaran tersebut melalui tautan berikut:

@php
    // $fileUrl = route('download.surat', basename($surat->file));
    $fileUrl = url('/api/surat-edaran/download/' . basename($surat->file));
@endphp

@component('mail::button', ['url' => $fileUrl, 'color' => 'success'])
📄 Unduh Surat Edaran
@endcomponent

Kami mohon kepada Bapak/Ibu untuk membaca serta memahami isi surat tersebut dengan saksama. 

Apabila terdapat hal-hal yang perlu ditanyakan atau diklarifikasi, Bapak/Ibu dapat menghubungi wali kelas atau pihak sekolah.

Atas perhatian dan kerja sama Bapak/Ibu, kami ucapkan terima kasih.

Hormat kami,  
**Kepala Sekolah SDI Al Muhajirin**  
**Khojanah, S.Pd.**

@endcomponent
