<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with('post');
        
        if ($request->has('status')) {
            if ($request->status == 'pendente') {
                $query->where('aprovado', false);
            } elseif ($request->status == 'aprovado') {
                $query->where('aprovado', true);
            }
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('comentario', 'LIKE', "%{$search}%");
            });
        }
        
        $comments = $query->latest()->paginate(20);
        
        $stats = [
            'total' => Comment::count(),
            'aprovados' => Comment::where('aprovado', true)->count(),
            'pendentes' => Comment::where('aprovado', false)->count(),
            'hoje' => Comment::whereDate('created_at', today())->count(),
        ];
        
        return view('admin.comentarios.index', compact('comments', 'stats'));
    }

    public function show(Comment $comment)
    {
        return view('admin.comentarios.show', compact('comment'));
    }

    public function aprovar(Comment $comment)
    {
        try {
            $comment->update(['aprovado' => true]);
            
            Log::info('Comentário aprovado', [
                'comment_id' => $comment->id,
                'admin_id' => auth()->id(),
                'post_id' => $comment->post_id
            ]);
            
            return redirect()->route('admin.comentarios.index')
                           ->with('success', 'Comentário aprovado com sucesso!');
            
        } catch (\Exception $e) {
            Log::error('Erro ao aprovar comentário: ' . $e->getMessage());
            return back()->with('error', 'Erro ao aprovar comentário.');
        }
    }

    // CORREÇÃO: Método destroy para excluir comentário individual
   public function destroy(Comment $comment)
{
    try {
        // Log para debug
        \Log::info('Tentando excluir comentário ID: ' . $comment->id);
        
        // Salvar informações antes de excluir
        $commentId = $comment->id;
        
        // Excluir
        $comment->delete();
        
        // Log de sucesso
        \Log::info('Comentário excluído com sucesso: ' . $commentId);
        
        return redirect()->route('admin.comentarios.index')
                         ->with('success', 'Comentário excluído com sucesso!');
                         
    } catch (\Exception $e) {
        \Log::error('Erro ao excluir comentário: ' . $e->getMessage());
        return back()->with('error', 'Erro ao excluir comentário: ' . $e->getMessage());
    }

}

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:aprovar,excluir',
            'comments' => 'required|array',
            'comments.*' => 'exists:comments,id'
        ]);
        
        $count = 0;
        
        if ($request->action == 'aprovar') {
            $count = Comment::whereIn('id', $request->comments)
                           ->where('aprovado', false)
                           ->update(['aprovado' => true]);
            $message = "{$count} comentário(s) aprovado(s) com sucesso!";
        } else {
            $count = Comment::whereIn('id', $request->comments)->delete();
            $message = "{$count} comentário(s) excluído(s) com sucesso!";
        }
        
        Log::info("Ação em massa: {$request->action}", [
            'count' => $count,
            'admin_id' => auth()->id()
        ]);
        
        return redirect()->route('admin.comentarios.index')
                       ->with('success', $message);
    }
}