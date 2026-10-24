<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; background:#f3f4f6; padding:24px;">
    <div style="max-width:480px; margin:0 auto; background:#ffffff; border-radius:12px; overflow:hidden;">
        <div style="background:linear-gradient(to right,#2563eb,#4f46e5); padding:20px 24px; color:white;">
            <h2 style="margin:0; font-size:18px;">📚 Perpustakaan SMKN 1 Bojongpicung</h2>
        </div>
        <div style="padding:24px;">
            <p style="font-size:14px; color:#374151;">Halo, <b>{{ $peminjaman->user->name }}</b>,</p>
            <p style="font-size:14px; color:#374151; line-height:1.6;">
                {{ $pesanTeks }}.
            </p>
            <div style="background:#fef3c7; border:1px solid #fcd34d; border-radius:8px; padding:12px 16px; margin:16px 0;">
                <p style="margin:0; font-size:13px; color:#92400e;">
                    <b>Kode Peminjaman:</b> {{ $peminjaman->kode_peminjaman }}<br>
                    <b>Judul Buku:</b> {{ $peminjaman->buku->judul }}<br>
                    <b>Batas Kembali:</b> {{ $peminjaman->tgl_kembali->format('d M Y') }}
                </p>
            </div>
            <p style="font-size:12px; color:#9ca3af; margin-top:24px;">
                Email ini dikirim otomatis oleh sistem, mohon tidak membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>