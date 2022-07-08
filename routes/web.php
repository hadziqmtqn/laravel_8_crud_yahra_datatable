<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/books-list', [BookController::class, 'index']);
Route::post('/add-book', [BookController::class, 'addBook'])->name('add.book');
Route::get('/getBooksList', [BookController::class, 'getBooksList'])->name('get.books.list');
Route::post('/getBookDetails', [BookController::class, 'getBookDetails'])->name('get.book.details');
Route::post('/updateBookDetails', [BookController::class, 'updateBookDetails'])->name('update.book.details');
Route::post('/deleteBook', [BookController::class, 'deleteBook'])->name('delete.book');
