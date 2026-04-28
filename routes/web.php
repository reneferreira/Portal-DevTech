<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\PostController;
use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\ContatoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ContatoController as AdminContatoController;
use App\Http\Controllers\Admin\PushNotificationController as AdminPushNotificationController;
use App\Http\Controllers\PushSubscriptionController;

Route::get('/push/public-key', [PushSubscriptionController::class, 'publicKey'])->name('push.public-key');
Route::post('/push/subscribe', [PushSubscriptionController::class, 'store'])->name('push.subscribe');
Route::delete('/push/unsubscribe', [PushSubscriptionController::class, 'destroy'])->name('push.unsubscribe');

/*
|--------------------------------------------------------------------------
| Frontend
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/busca/sugestoes', [HomeController::class, 'sugestoes'])->name('busca.sugestoes');
Route::get('/busca', [HomeController::class, 'busca'])->name('busca');

// Posts
Route::get('/post/{slug}', [PostController::class, 'show'])->name('post');
Route::post('/post/{postId}/comentario', [PostController::class, 'storeComment'])->name('comentario.store');

// Categorias
Route::get('/categoria/{slug}', [CategoryController::class, 'show'])->name('categoria');

// Contato
Route::get('/contato', [ContatoController::class, 'index'])->name('contato');
Route::post('/contato/enviar', [ContatoController::class, 'store'])->name('contato.enviar');

// Newsletter
Route::post('/newsletter', function (Request $request) {
    return back()->with('success', 'Inscrição realizada com sucesso!');
})->name('newsletter');

/*
|--------------------------------------------------------------------------
| Dashboard do usuário
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('posts', AdminPostController::class);
    Route::resource('categorias', AdminCategoryController::class);
    Route::resource('tags', TagController::class);

     // Comentários
    Route::get('/comentarios', [App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comentarios.index');
    Route::get('/comentarios/{comment}', [App\Http\Controllers\Admin\CommentController::class, 'show'])->name('comentarios.show');
    Route::patch('/comentarios/{comment}/aprovar', [App\Http\Controllers\Admin\CommentController::class, 'aprovar'])->name('comentarios.aprovar');
    Route::delete('/comentarios/{comment}', [App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('comentarios.destroy');
    
    // CORREÇÃO: Usar POST em vez de PATCH para bulk action
    Route::post('/comentarios/bulk', [App\Http\Controllers\Admin\CommentController::class, 'bulkAction'])->name('comentarios.bulk');
    
       // Vídeos
    Route::resource('videos', App\Http\Controllers\Admin\VideoController::class);
    
    // Banners
    Route::resource('banners', App\Http\Controllers\Admin\BannerController::class);
    // Atualizar ordem do banner
    Route::patch('/banners/{banner}/ordem', [App\Http\Controllers\Admin\BannerController::class, 'updateOrdem'])->name('banners.ordem');
    
    // Contatos
    Route::get('/contatos', [App\Http\Controllers\Admin\ContatoController::class, 'index'])->name('contatos.index');
    Route::get('/contatos/{contato}', [App\Http\Controllers\Admin\ContatoController::class, 'show'])->name('contatos.show');
    Route::patch('/contatos/{contato}/marcar-lido', [App\Http\Controllers\Admin\ContatoController::class, 'marcarLido'])->name('contatos.marcar-lido');
    Route::patch('/contatos/{contato}/respondido', [App\Http\Controllers\Admin\ContatoController::class, 'marcarRespondido'])->name('contatos.respondido');
    Route::delete('/contatos/{contato}', [App\Http\Controllers\Admin\ContatoController::class, 'destroy'])->name('contatos.destroy');

    Route::get('/notificacoes-push', [AdminPushNotificationController::class, 'index'])->name('push.index');
    Route::post('/notificacoes-push', [AdminPushNotificationController::class, 'store'])->name('push.store');
});

require __DIR__.'/auth.php';
