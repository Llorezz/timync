<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva contraseña · Timync</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; min-height: 100vh; background: #f0f4f8; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: white; border-radius: 24px; padding: 48px 44px; width: 100%; max-width: 420px; box-shadow: 0 8px 40px rgba(15,76,129,0.12); }
        .logo { text-align: center; margin-bottom: 32px; }
        .logo-text { font-size: 30px; font-weight: 800; color: #0f4c81; letter-spacing: -1px; }
        .logo-text span { color: #1a6eb5; }
        .logo-dot { display: inline-block; width: 8px; height: 8px; background: #60c4ff; border-radius: 50%; margin-left: 2px; vertical-align: super; }
        .icon { text-align: center; font-size: 40px; margin-bottom: 16px; }
        h2 { font-size: 22px; font-weight: 700; color: #0f172a; text-align: center; margin-bottom: 6px; }
        .subtitle { font-size: 14px; color: #94a3b8; text-align: center; margin-bottom: 28px; }
        label { font-size: 13px; font-weight: 600; color: #475569; display: block; margin-bottom: 6px; }
        input[type=email], input[type=password] { width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 14px; outline: none; background: #f8fafc; transition: all 0.2s; }
        input:focus { border-color: #0f4c81; background: white; box-shadow: 0 0 0 3px rgba(15,76,129,0.08); }
        .form-group { margin-bottom: 18px; }
        .btn-primary { width: 100%; padding: 13px; background: linear-gradient(135deg, #0f4c81, #1a6eb5); color: white; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; transition: opacity 0.2s; }
        .btn-primary:hover { opacity: 0.9; }
        .error { background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #dc2626; margin-bottom: 20px; }
        .back-link { text-align: center; font-size: 14px; color: #64748b; margin-top: 20px; }
        .back-link a { color: #0f4c81; font-weight: 600; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <div class="logo-text">Tim<span>y</span>nc<span class="logo-dot"></span></div>
        </div>

        <div class="icon">🔑</div>
        <h2>Nueva contraseña</h2>
        <p class="subtitle">Introduce tu nueva contraseña para recuperar el acceso.</p>

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $request->email) }}" required placeholder="tu@email.com">
            </div>
            <div class="form-group">
                <label>Nueva contraseña</label>
                <input type="password" name="password" required placeholder="Mínimo 8 caracteres">
            </div>
            <div class="form-group">
                <label>Confirmar contraseña</label>
                <input type="password" name="password_confirmation" required placeholder="Repite la contraseña">
            </div>
            <button type="submit" class="btn-primary">Restablecer contraseña</button>
        </form>

        <p class="back-link"><a href="{{ route('login') }}">← Volver al inicio de sesión</a></p>
    </div>
</body>
</html>
