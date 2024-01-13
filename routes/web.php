<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DogBreedController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [DogBreedController::class, 'index'])->middleware('signature');
Route::get('/renderBreeds', [DogBreedController::class, 'renderBreeds'])->middleware('signature');