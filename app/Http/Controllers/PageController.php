<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function home()
    {
        // 1. Get logged-in user's DB bookmarks
        $user = Auth::user();
        $bookmarkKeys = $user
            ? $user->bookmarks->map(fn($b) => "{$b->media_type}_{$b->media_id}")->toArray()
            : [];

        // 2. Get all genres (movies + TV)
        $genreMap = Cache::remember('tmdb_genres_all', now()->addDays(1), function () {
            $movieGenres = Http::get('https://api.themoviedb.org/3/genre/movie/list', [
                'api_key' => config('services.tmdb.key'),
                'language' => 'en-US',
            ])['genres'] ?? [];

            $tvGenres = Http::get('https://api.themoviedb.org/3/genre/tv/list', [
                'api_key' => config('services.tmdb.key'),
                'language' => 'en-US',
            ])['genres'] ?? [];

            return collect(array_merge($movieGenres, $tvGenres))->pluck('name', 'id');
        });

        // 3. Trending section
        $trendingResponse = Http::get('https://api.themoviedb.org/3/trending/all/week', [
            'api_key' => config('services.tmdb.key'),
        ]);

        $trendingItems = collect($trendingResponse['results'] ?? [])->take(10)->map(function ($item) use ($bookmarkKeys, $genreMap) {
            $type = $item['media_type'] === 'movie' ? 'movie' : 'tv';
            $id = $item['id'];
            $key = "{$type}_{$id}";

            return [
                'id' => $id,
                'Title' => $type === 'movie' ? ($item['title'] ?? 'N/A') : ($item['name'] ?? 'N/A'),
                'Year' => substr($type === 'movie' ? ($item['release_date'] ?? '') : ($item['first_air_date'] ?? ''), 0, 4),
                'Poster' => $item['poster_path']
                    ? 'https://image.tmdb.org/t/p/w500' . $item['poster_path']
                    : 'images/fallback.jpg',
                'Type' => $type === 'movie' ? 'Movie' : 'TV Series',
                'Genres' => collect($item['genre_ids'])->map(fn($gid) => $genreMap[$gid] ?? null)->filter()->take(2)->implode(', '),
                'Rating' => number_format($item['vote_average'] ?? 0, 1),
                'ContentRating' => 'NR',
                'isBookmarked' => in_array($key, $bookmarkKeys),
            ];
        });

        // 4. Recommended: 6 movies + 6 TV
        $movieResponse = Http::get('https://api.themoviedb.org/3/movie/popular', [
            'api_key' => config('services.tmdb.key'),
            'language' => 'en-US',
            'page' => 1,
        ]);

        $tvResponse = Http::get('https://api.themoviedb.org/3/tv/popular', [
            'api_key' => config('services.tmdb.key'),
            'language' => 'en-US',
            'page' => 1,
        ]);

        $movieRecommendations = collect($movieResponse['results'] ?? [])->take(6)->map(function ($movie) use ($bookmarkKeys, $genreMap) {
            $id = $movie['id'];
            $key = "movie_{$id}";

            return [
                'id' => $id,
                'Title' => $movie['title'],
                'Year' => substr($movie['release_date'] ?? '', 0, 4),
                'Poster' => $movie['poster_path']
                    ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path']
                    : 'images/fallback.jpg',
                'Type' => 'Movie',
                'Genres' => collect($movie['genre_ids'])->map(fn($gid) => $genreMap[$gid] ?? null)->filter()->take(2)->implode(', '),
                'Rating' => number_format($movie['vote_average'] ?? 0, 1),
                'ContentRating' => 'NR',
                'isBookmarked' => in_array($key, $bookmarkKeys),
            ];
        });

        $tvRecommendations = collect($tvResponse['results'] ?? [])->take(6)->map(function ($tv) use ($bookmarkKeys, $genreMap) {
            $id = $tv['id'];
            $key = "tv_{$id}";

            return [
                'id' => $id,
                'Title' => $tv['name'],
                'Year' => substr($tv['first_air_date'] ?? '', 0, 4),
                'Poster' => $tv['poster_path']
                    ? 'https://image.tmdb.org/t/p/w500' . $tv['poster_path']
                    : 'images/fallback.jpg',
                'Type' => 'TV Series',
                'Genres' => collect($tv['genre_ids'])->map(fn($gid) => $genreMap[$gid] ?? null)->filter()->take(2)->implode(', '),
                'Rating' => number_format($tv['vote_average'] ?? 0, 1),
                'ContentRating' => 'NR',
                'isBookmarked' => in_array($key, $bookmarkKeys),
            ];
        });

        $recommendedItems = $movieRecommendations->merge($tvRecommendations);

        return view('home', compact('trendingItems', 'recommendedItems'));
    }

    public function movies()
    {
        // 1. Get user's bookmarked movie IDs from DB
        $user = Auth::user();
        $bookmarkedMovieKeys = $user
            ? $user->bookmarks->where('media_type', 'movie')->pluck('media_id')->map(fn($id) => (string) $id)->toArray()
            : [];

        // 2. Get genre list from cache or TMDb
        $genreMap = Cache::remember('tmdb_genres_movies', now()->addDays(1), function () {
            $res = Http::get('https://api.themoviedb.org/3/genre/movie/list', [
                'api_key' => config('services.tmdb.key'),
                'language' => 'en-US',
            ]);
            return collect($res['genres'] ?? [])->pluck('name', 'id');
        });

        // 3. Fetch popular movies
        $response = Http::get('https://api.themoviedb.org/3/movie/popular', [
            'api_key' => config('services.tmdb.key'),
            'language' => 'en-US',
            'page' => 1,
        ]);

        $movieItems = collect($response['results'] ?? [])->map(function ($movie) use ($bookmarkedMovieKeys, $genreMap) {
            $id = $movie['id'];

            $rating = Cache::remember("tmdb_rating_{$id}", now()->addDays(1), function () use ($id) {
                $releaseData = Http::get("https://api.themoviedb.org/3/movie/{$id}/release_dates", [
                    'api_key' => config('services.tmdb.key'),
                ]);

                $us = collect($releaseData['results'] ?? [])
                    ->firstWhere('iso_3166_1', 'US');

                return collect($us['release_dates'] ?? [])
                    ->first()['certification'] ?? 'NR';
            });

            return [
                'id' => $id,
                'Title' => $movie['title'],
                'Year' => substr($movie['release_date'] ?? '', 0, 4),
                'Poster' => $movie['poster_path']
                    ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path']
                    : 'images/fallback.jpg',
                'Type' => 'Movie',
                'Genres' => collect($movie['genre_ids'])->map(fn($gid) => $genreMap[$gid] ?? null)->filter()->take(2)->implode(', '),
                'Rating' => number_format($movie['vote_average'], 1),
                'ContentRating' => $rating,
                'isBookmarked' => in_array((string) $id, $bookmarkedMovieKeys),
            ];
        });

        return view('movies', compact('movieItems'));
    }

    public function tvSeries()
    {
        // 1. Get user's bookmarked TV IDs from DB
        $user = Auth::user();
        $bookmarkedTVKeys = $user
            ? $user->bookmarks->where('media_type', 'tv')->pluck('media_id')->map(fn($id) => (string) $id)->toArray()
            : [];
    
        // 2. Get genre list from cache or TMDb
        $genreMap = Cache::remember('tmdb_genres_tv', now()->addDays(1), function () {
            $res = Http::get('https://api.themoviedb.org/3/genre/tv/list', [
                'api_key' => config('services.tmdb.key'),
                'language' => 'en-US',
            ]);
            return collect($res['genres'] ?? [])->pluck('name', 'id');
        });
    
        // 3. Fetch popular TV series
        $response = Http::get('https://api.themoviedb.org/3/tv/popular', [
            'api_key' => config('services.tmdb.key'),
            'language' => 'en-US',
            'page' => 1,
        ]);
    
        $tvItems = collect($response['results'] ?? [])->map(function ($tv) use ($bookmarkedTVKeys, $genreMap) {
            $id = $tv['id'];
    
            $rating = Cache::remember("tmdb_rating_tv_{$id}", now()->addDays(1), function () use ($id) {
                $releaseData = Http::get("https://api.themoviedb.org/3/tv/{$id}/content_ratings", [
                    'api_key' => config('services.tmdb.key'),
                ]);
    
                $us = collect($releaseData['results'] ?? [])
                    ->firstWhere('iso_3166_1', 'US');
    
                return $us['rating'] ?? 'NR';
            });
    
            return [
                'id' => $id,
                'Title' => $tv['name'],
                'Year' => substr($tv['first_air_date'] ?? '', 0, 4),
                'Poster' => $tv['poster_path']
                    ? 'https://image.tmdb.org/t/p/w500' . $tv['poster_path']
                    : 'images/fallback.jpg',
                'Type' => 'TV Series',
                'Genres' => collect($tv['genre_ids'])->map(fn($gid) => $genreMap[$gid] ?? null)->filter()->take(2)->implode(', '),
                'Rating' => number_format($tv['vote_average'], 1),
                'ContentRating' => $rating,
                'isBookmarked' => in_array((string) $id, $bookmarkedTVKeys),
            ];
        });
    
        return view('tv-series', compact('tvItems'));
    }


    public function search(Request $request)
    {
        $query = $request->query('q');

        // 1. Get user's bookmarks as "type_id" keys
        $user = Auth::user();
        $bookmarkKeys = $user
            ? $user->bookmarks->map(fn($b) => "{$b->media_type}_{$b->media_id}")->toArray()
            : [];

        $results = collect();

        if ($query) {
            // 2. Get genre list
            $genreMap = Cache::remember('tmdb_search_genres', now()->addDays(1), function () {
                $movieGenres = Http::get('https://api.themoviedb.org/3/genre/movie/list', [
                    'api_key' => config('services.tmdb.key'),
                    'language' => 'en-US',
                ])['genres'] ?? [];

                $tvGenres = Http::get('https://api.themoviedb.org/3/genre/tv/list', [
                    'api_key' => config('services.tmdb.key'),
                    'language' => 'en-US',
                ])['genres'] ?? [];

                return collect(array_merge($movieGenres, $tvGenres))->pluck('name', 'id');
            });

            // 3. Make TMDb search request
            $response = Http::get('https://api.themoviedb.org/3/search/multi', [
                'api_key' => config('services.tmdb.key'),
                'language' => 'en-US',
                'query' => $query,
                'page' => 1,
            ]);

            // 4. Process results
            $results = collect($response['results'] ?? [])->filter(function ($item) {
                return in_array($item['media_type'], ['movie', 'tv']);
            })->map(function ($item) use ($bookmarkKeys, $genreMap) {
                $id = $item['id'];
                $type = $item['media_type'];
                $key = "{$type}_{$id}";

                $contentRating = Cache::remember("tmdb_rating_search_{$type}_{$id}", now()->addDays(1), function () use ($id, $type) {
                    if ($type === 'movie') {
                        $releaseData = Http::get("https://api.themoviedb.org/3/movie/{$id}/release_dates", [
                            'api_key' => config('services.tmdb.key'),
                        ]);
                        $us = collect($releaseData['results'] ?? [])->firstWhere('iso_3166_1', 'US');
                        return collect($us['release_dates'] ?? [])->first()['certification'] ?? 'NR';
                    } else {
                        $releaseData = Http::get("https://api.themoviedb.org/3/tv/{$id}/content_ratings", [
                            'api_key' => config('services.tmdb.key'),
                        ]);
                        $us = collect($releaseData['results'] ?? [])->firstWhere('iso_3166_1', 'US');
                        return $us['rating'] ?? 'NR';
                    }
                });

                return [
                    'id' => $id,
                    'Title' => $type === 'movie' ? $item['title'] : $item['name'],
                    'Year' => substr($type === 'movie' ? ($item['release_date'] ?? '') : ($item['first_air_date'] ?? ''), 0, 4),
                    'Poster' => $item['poster_path']
                        ? 'https://image.tmdb.org/t/p/w500' . $item['poster_path']
                        : 'images/fallback.jpg',
                    'Type' => $type === 'movie' ? 'Movie' : 'TV Series',
                    'Rating' => number_format($item['vote_average'] ?? 0, 1),
                    'ContentRating' => $contentRating,
                    'Genres' => collect($item['genre_ids'] ?? [])->map(fn($gid) => $genreMap[$gid] ?? null)->filter()->take(2)->implode(', '),
                    'isBookmarked' => in_array($key, $bookmarkKeys),
                ];
            });
        }

        return view('search.index', compact('query', 'results'));
    }


    
}
