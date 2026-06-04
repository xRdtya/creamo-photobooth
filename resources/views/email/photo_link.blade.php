<!DOCTYPE html>
<html>
<head>
    <title>Hasil Foto Photobooth Kamu!</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; padding: 20px;">
    <h2>Halo!, Terima kasih telah menggunakan Photobooth kami 🎉</h2>
    <p>Foto keseruan kamu sudah berhasil diproses dan siap untuk disimpan.</p>
    <p>Silakan klik tombol di bawah ini untuk melihat dan mendownload hasil foto kamu:</p>
    
    <p style="margin-top: 30px; margin-bottom: 30px;">
        <a href="{{ $downloadLink }}" 
           style="background-color: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold;">
            📸 Lihat & Download Foto
        </a>
    </p>

    <p>Jika tombol di atas tidak berfungsi, kamu juga bisa salin link di bawah ini ke browser:</p>
    <p><a href="{{ $downloadLink }}">{{ $downloadLink }}</a></p>
    
    <br>
    <p>Salam hangat,<br><strong>Team Photobooth</strong></p>
</body>
</html>