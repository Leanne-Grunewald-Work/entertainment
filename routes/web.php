<?php

use App\Http\Controllers\BookmarksController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

Route::middleware('auth')->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home');
    Route::get('/movies', [PageController::class, 'movies'])->name('movies');
    Route::get('/tv-series', [PageController::class, 'tvSeries'])->name('tvSeries');

    Route::post('/bookmarks/toggle', [BookmarksController::class, 'toggle'])->name('bookmarks.toggle');
    Route::get('/bookmarks', [BookmarksController::class, 'index'])->name('bookmarks');
    
    Route::get('/search', [PageController::class, 'search'])->name('search');

    Route::post('/bookmark', function (Request $request) {
        $id = $request->input('id');
        $type = $request->input('type');

        if (!in_array($type, ['movie', 'tv'])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        $key = $type === 'movie' ? 'bookmarks_movies' : 'bookmarks_tv';

        $bookmarks = session()->get($key, []);

        if (in_array($id, $bookmarks)) {
            $bookmarks = array_filter($bookmarks, fn ($item) => $item !== $id);
        } else {
            $bookmarks[] = $id;
        }

        session()->put($key, $bookmarks);

        return response()->json(['bookmarked' => in_array($id, $bookmarks)]);
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
});


require __DIR__.'/auth.php';
