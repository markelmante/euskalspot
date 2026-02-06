<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Spot;
use App\Models\Favorito;
use App\Models\Review;

Route::get('/reviews', function () {
    return Review::inRandomOrder()->take(3)->get();
});

