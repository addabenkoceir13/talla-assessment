<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'check.permission'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('403', function(){abort(403);});
Route::get('user', function(){return Auth::user();});

Route::get('/admin/countries/search', function (\Illuminate\Http\Request $request) {
    $query = $request->query('query');

    if (strlen($query) < 3) {
        return response()->json([]);
    }

    try {
        $client = new \GuzzleHttp\Client();
        $response = $client->get("https://restcountries.com/v3.1/name/{$query}");
        $data = json_decode($response->getBody(), true);

        return response()->json($data);
    } catch (\Exception $e) {
        return response()->json([]);
    }
});

require __DIR__.'/auth.php';
