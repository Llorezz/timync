<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0; padding:0; background:#f0f4f8; font-family:Inter,-apple-system,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f4f8; padding:40px 20px;">
<tr><td align="center">
<table width="520" cellpadding="0" cellspacing="0" style="max-width:520px; width:100%;">

  <tr><td align="center" style="padding-bottom:24px;">
    <span style="font-size:26px; font-weight:800; color:#0f4c81; letter-spacing:-1px;">Timync</span>
  </td></tr>

  <tr><td style="background:white; border-radius:20px; padding:40px; box-shadow:0 4px 24px rgba(0,0,0,0.07);">
    <div style="text-align:center; margin-bottom:24px; font-size:40px;">🔑</div>
    <h1 style="font-size:22px; font-weight:800; color:#0f172a; text-align:center; margin:0 0 8px;">Restablecer contraseña</h1>
    <p style="font-size:14px; color:#64748b; text-align:center; margin:0 0 28px; line-height:1.6;">Recibimos una solicitud para restablecer la contraseña de tu cuenta. Haz clic en el botón para crear una nueva.</p>

    <div style="text-align:center; margin-bottom:28px;">
      <a href="{{ $url }}" style="display:inline-block; padding:14px 36px; background:linear-gradient(135deg,#0f4c81,#1a6eb5); color:white; text-decoration:none; border-radius:10px; font-size:15px; font-weight:700;">Restablecer contraseña</a>
    </div>

    <p style="font-size:12px; color:#94a3b8; text-align:center; margin:0 0 8px;">Este enlace expirará en 60 minutos.</p>
    <p style="font-size:12px; color:#94a3b8; text-align:center; margin:0;">Si no solicitaste este cambio, ignora este email.</p>

    <hr style="border:none; border-top:1px solid #f1f5f9; margin:24px 0;">
    <p style="font-size:11px; color:#cbd5e1; text-align:center; margin:0;">Si el botón no funciona, copia este enlace:<br>
    <span style="color:#0f4c81; word-break:break-all;">{{ $url }}</span></p>
  </td></tr>

  <tr><td align="center" style="padding-top:20px;">
    <p style="font-size:12px; color:#94a3b8; margin:0;">© {{ date('Y') }} Timync · Todos los derechos reservados</p>
  </td></tr>

</table>
</td></tr>
</table>
</body>
</html>
