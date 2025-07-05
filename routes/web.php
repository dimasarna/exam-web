<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Welcome;
use App\Livewire\Post;

Route::get('/', Welcome::Class);

Route::get('/posts/{id}', Post::Class);
