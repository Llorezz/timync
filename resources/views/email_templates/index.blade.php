<x-app-layout>
    <x-slot name="header">Plantillas de Email</x-slot>

    <div class="card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <div>
                <h2 style="font-size:16px; font-weight:600; color:#0f172a;">Plantillas de email</h2>
                <p style="color:#64748b; font-size:13px; margin-top:2px;">Diseña emails profesionales para tus clientes</p>
            </div>
            <a href="{{ route('email-templates.create') }}" class="btn-primary">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                Nueva plantilla
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success" style="margin-bottom:20px;">{{ session('success') }}</div>
        @endif

        @if($templates->count() > 0)
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px;">
            @foreach($templates as $template)
            <div style="border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;">
                <!-- Preview miniatura -->
                <div style="height:120px; background:{{ $template->color_primario }}; display:flex; align-items:center; justify-content:center; padding:20px;">
                    <div style="background:white; border-radius:8px; padding:12px 20px; text-align:center; width:100%;">
                        <div style="font-size:11px; font-weight:700; color:{{ $template->color_primario }};">{{ strtoupper(Auth::user()->name) }}</div>
                        <div style="font-size:10px; color:#64748b; margin-top:4px;">{{ Str::limit($template->asunto, 30) }}</div>
                        @if($template->texto_boton)
                        <div style="background:{{ $template->color_boton }}; color:white; font-size:9px; padding:3px 8px; border-radius:4px; margin-top:6px; display:inline-block;">{{ $template->texto_boton }}</div>
                        @endif
                    </div>
                </div>
                <!-- Info -->
                <div style="padding:16px;">
                    <div style="font-size:14px; font-weight:600; color:#0f172a; margin-bottom:4px;">{{ $template->nombre }}</div>
                    <div style="font-size:12px; color:#64748b; margin-bottom:12px;">
                        @if($template->tipo)
                            @switch($template->tipo)
                                @case('recordatorio') 📅 Recordatorio @break
                                @case('no_show') 🚫 No-show @break
                                @case('hueco_libre') 📢 Hueco libre @break
                                @case('cliente_inactivo') 💤 Cliente inactivo @break
                                @default 📧 General
                            @endswitch
                        @else
                            📧 General
                        @endif
                    </div>
                    <div style="display:flex; gap:10px;">
                        <a href="{{ route('email-templates.preview', $template) }}" style="flex:1; text-align:center; padding:6px; border:1px solid #e2e8f0; border-radius:6px; font-size:12px; color:#64748b;">👁 Preview</a>
                        <a href="{{ route('email-templates.edit', $template) }}" style="flex:1; text-align:center; padding:6px; border:1px solid var(--primary); border-radius:6px; font-size:12px; color:var(--primary);">Editar</a>
                        <form action="{{ route('email-templates.destroy', $template) }}" method="POST">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('¿Eliminar?')" style="padding:6px 10px; border:1px solid #fecaca; border-radius:6px; font-size:12px; color:#dc2626; background:none; cursor:pointer;">🗑</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align:center; padding:40px; color:#94a3b8;">
            <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24" style="margin:0 auto 12px; display:block; opacity:0.4;"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
            No hay plantillas. <a href="{{ route('email-templates.create') }}" style="color:var(--primary); font-weight:500;">Crea la primera</a>
        </div>
        @endif
    </div>

</x-app-layout>
