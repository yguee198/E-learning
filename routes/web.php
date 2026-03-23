<?php

use App\Http\Controllers\ChatController;  // ← UPPERCASE "App" (this is the namespace)
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
Route::get('/chat/messages', [ChatController::class, 'fetchMessages'])->name('chat.messages');