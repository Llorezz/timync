<x-app-layout>
    <x-slot name="header">Preview: {{ $emailTemplate->nombre }}</x-slot>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <div style="font-size:13px; color:#64748b;">Vista previa con datos de ejemplo</div>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('email-templates.edit', $emailTemplate) }}" class="btn-primary">Editar plantilla</a>
            <a href="{{ route('email-templates.index') }}" style="padding:8px 18px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; color:#64748b; font-weight:500;">Volver</a>
        </div>
    </div>

    <div style="border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; background:#f0f4f8;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f4f8; padding:40px 20px;">
            <tr><td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:white; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08);">
                    <tr>
                        <td style="background:{{ $emailTemplate->color_primario }}; padding:32px; text-align:center;">
                            <div style="color:white; font-size:24px; font-weight:700; letter-spacing:-0.5px;">Timync</div>
                            <div style="color:rgba(255,255,255,0.7); font-size:13px; margin-top:4px;">{{ $emailTemplate->asunto }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:40px 48px;">
                            <div style="color:#334155; font-size:15px; line-height:1.7;">
                                {!! nl2br(e($cuerpo)) !!}
                            </div>
                            @if($emailTemplate->texto_boton)
                            <div style="text-align:center; margin-top:32px;">
                                <a href="{{ $emailTemplate->url_boton ?? '#' }}"
                                   style="background:{{ $emailTemplate->color_boton }}; color:white; padding:14px 32px; border-radius:8px; text-decoration:none; font-weight:600; font-size:15px; display:inline-block;">
                                    {{ $emailTemplate->texto_boton }}
                                </a>
                            </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f8fafc; padding:24px 48px; border-top:1px solid #e2e8f0; text-align:center;">
                            <div style="color:#94a3b8; font-size:12px;">Enviado por Timync · Sistema de gestión de citas</div>
                        </td>
                    </tr>
                </table>
            </td></tr>
        </table>
    </div>

</x-app-layout>
