<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::where('user_id', Auth::id())->latest()->get();
        return view('email_templates.index', compact('templates'));
    }

    public function create()
    {
        return view('email_templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'         => 'required|string|max:255',
            'asunto'         => 'required|string|max:255',
            'cuerpo'         => 'required|string',
            'color_primario' => 'required|string|max:7',
            'color_boton'    => 'required|string|max:7',
            'texto_boton'    => 'nullable|string|max:100',
            'url_boton'      => 'nullable|url',
            'tipo'           => 'nullable|string',
        ]);

        EmailTemplate::create([
            'user_id'        => Auth::id(),
            'nombre'         => $request->nombre,
            'tipo'           => $request->tipo,
            'asunto'         => $request->asunto,
            'color_primario' => $request->color_primario,
            'color_boton'    => $request->color_boton,
            'texto_boton'    => $request->texto_boton,
            'url_boton'      => $request->url_boton,
            'cuerpo'         => $request->cuerpo,
        ]);

        return redirect()->route('email-templates.index')
                         ->with('success', 'Plantilla creada correctamente.');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        abort_if($emailTemplate->user_id !== Auth::id(), 403);
        return view('email_templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        abort_if($emailTemplate->user_id !== Auth::id(), 403);

        $request->validate([
            'nombre'         => 'required|string|max:255',
            'asunto'         => 'required|string|max:255',
            'cuerpo'         => 'required|string',
            'color_primario' => 'required|string|max:7',
            'color_boton'    => 'required|string|max:7',
            'texto_boton'    => 'nullable|string|max:100',
            'url_boton'      => 'nullable|url',
            'tipo'           => 'nullable|string',
        ]);

        $emailTemplate->update($request->all());

        return redirect()->route('email-templates.index')
                         ->with('success', 'Plantilla actualizada.');
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        abort_if($emailTemplate->user_id !== Auth::id(), 403);
        $emailTemplate->delete();
        return redirect()->route('email-templates.index')
                         ->with('success', 'Plantilla eliminada.');
    }

    public function preview(EmailTemplate $emailTemplate)
    {
        abort_if($emailTemplate->user_id !== Auth::id(), 403);
        $variables = [
            'nombre'   => 'Juan Pérez',
            'fecha'    => '15/03/2026',
            'hora'     => '10:00',
            'empleado' => 'María García',
            'servicio' => 'Corte de cabello',
        ];
        $cuerpo = $emailTemplate->renderizar($variables);
        return view('email_templates.preview', compact('emailTemplate', 'cuerpo'));
    }
}
