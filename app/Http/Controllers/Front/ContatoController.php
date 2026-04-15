<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Contato;
use Illuminate\Http\Request;

class ContatoController extends Controller
{
    public function index()
    {
        return view('front.contato');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'assunto' => 'required|string|max:255',
            'mensagem' => 'required|string',
            'post_id' => 'nullable|exists:posts,id'
        ]);

        $contato = Contato::create($request->all());

        // Aqui você pode adicionar lógica para enviar email de notificação

        return back()->with('success', 'Mensagem enviada com sucesso! Entraremos em contato em breve.');
    }
}