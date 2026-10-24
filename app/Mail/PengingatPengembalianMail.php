<?php

namespace App\Mail;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PengingatPengembalianMail extends Mailable
{
    use Queueable, SerializesModels;

    public Peminjaman $peminjaman;
    public string $pesanTeks;

    public function __construct(Peminjaman $peminjaman, string $pesanTeks)
    {
        $this->peminjaman = $peminjaman;
        $this->pesanTeks  = $pesanTeks;
    }

    public function build()
    {
        return $this->subject('Pengingat Pengembalian Buku - Perpustakaan SMKN 1 Bojongpicung')
                     ->view('emails.pengingat-pengembalian');
    }
}