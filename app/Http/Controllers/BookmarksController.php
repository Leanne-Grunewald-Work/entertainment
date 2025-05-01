<?php

namespace App\Http\Controllers;
use App\Models\Bookmark;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BookmarksController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bookmarks = $user->bookmarks;


        $movieItems = $bookmarks->where('media_type', 'movie')->map(function ($bookmark) {
            $id = $bookmark->media_id;
        
            $data = Http::get("https://api.themoviedb.org/3/movie/{$id}", [
                'api_key' => config('services.tmdb.key'),
                'language' => 'en-US',
            ])->json();
        
            $rating = Cache::remember("tmdb_rating_movie_{$id}", now()->addDays(1), function () use ($id) {
                $releaseData = Http::get("https://api.themoviedb.org/3/movie/{$id}/release_dates", [
                    'api_key' => config('services.tmdb.key'),
                ]);
                $us = collect($releaseData['results'] ?? [])->firstWhere('iso_3166_1', 'US');
                return collect($us['release_dates'] ?? [])->first()['certification'] ?? 'NR';
            });
        
            return [
                'id' => $id,
                'Title' => $data['title'] ?? 'N/A',
                'Year' => substr($data['release_date'] ?? '', 0, 4),
                'Poster' => $data['poster_path'] ? 'https://image.tmdb.org/t/p/w500' . $data['poster_path'] : 'images/fallback.jpg',
                'Type' => 'Movie',
                'Rating' => number_format($data['vote_average'] ?? 0, 1),
                'ContentRating' => $rating,
                'isBookmarked' => true,
            ];
        });
        
        $tvItems = $bookmarks->where('media_type', 'tv')->map(function ($bookmark) {
            $id = $bookmark->media_id;
        
            $data = Http::get("https://api.themoviedb.org/3/tv/{$id}", [
                'api_key' => config('services.tmdb.key'),
                'language' => 'en-US',
            ])->json();
        
            $rating = Cache::remember("tmdb_rating_tv_{$id}", now()->addDays(1), function () use ($id) {
                $releaseData = Http::get("https://api.themoviedb.org/3/tv/{$id}/content_ratings", [
                    'api_key' => config('services.tmdb.key'),
                ]);
                $us = collect($releaseData['results'] ?? [])->firstWhere('iso_3166_1', 'US');
                return $us['rating'] ?? 'NR';
            });
        
            return [
                'id' => $id,
                'Title' => $data['name'] ?? 'N/A',
                'Year' => substr($data['first_air_date'] ?? '', 0, 4),
                'Poster' => $data['poster_path'] ? 'https://image.tmdb.org/t/p/w500' . $data['poster_path'] : 'images/fallback.jpg',
                'Type' => 'TV Series',
                'Rating' => number_format($data['vote_average'] ?? 0, 1),
                'ContentRating' => $rating,
                'isBookmarked' => true,
            ];
        });

        return view('bookmarks.index', compact('movieItems', 'tvItems'));
    }

    public function toggle(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'media_id' => 'required|string',
            'media_type' => 'required|in:movie,tv',
        ]);

        $bookmark = $user->bookmarks()->where([
            'media_id' => $validated['media_id'],
            'media_type' => $validated['media_type'],
        ])->first();

        if ($bookmark) {
            $bookmark->delete();
            $status = 'removed';
        } else {
            $user->bookmarks()->create($validated);
            $status = 'added';
        }

        return response()->json(['status' => $status]);
    }


}
