<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contato;
use Illuminate\Http\Request;

class ContatoController extends Controller
{
    public function index()
    {
        $contatos = Contato::with('post')
                          ->latest()
                          ->paginate(20);
        
        $stats = [
            'total' => Contato::count(),
            'novos' => Contato::where('status', 'novo')->count(),
            'lidos' => Contato::where('status', 'lido')->count(),
            'respondidos' => Contato::where('status', 'respondido')->count(),
        ];
        
        return view('admin.contatos.index', compact('contatos', 'stats'));
    }

    public function show(Contato $contato)
    {
        if ($contato->status == 'novo') {
            $contato->update(['status' => 'lido']);
        }
        return view('admin.contatos.show', compact('contato'));
    }

    public function marcarLido(Contato $contato)
    {
        $contato->update(['status' => 'lido']);
        return back()->with('success', 'Contato marcado como lido!');
    }

    public function marcarRespondido(Contato $contato)
    {
        $contato->update(['status' => 'respondido']);
        return back()->with('success', 'Contato marcado como respondido!');
    }
    
    public function destroy(Contato $contato)
    {
        $contato->delete();
        return redirect()->route('admin.contatos.index')
                         ->with('success', 'Contato excluído com sucesso!');
    }
}