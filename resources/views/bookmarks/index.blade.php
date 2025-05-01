@extends('layouts.main-layout')

@section('content')

<section class="px-4 sm:px-6 lg:px-8">
    <h2 class="text-xl mb-6">Bookmarked Movies</h2>
    @if($movieItems->isEmpty())
        <p class="text-gray-400">You haven’t bookmarked any movies yet.</p>
    @else
        <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @foreach($movieItems as $item)
                @include('partials.media-card', ['item' => $item])
            @endforeach
        </div>
    @endif
</section>

<section class="px-4 sm:px-6 lg:px-8 mt-12">
    <h2 class="text-xl mb-6">Bookmarked TV Series</h2>
    @if($tvItems->isEmpty())
        <p class="text-gray-400">You haven’t bookmarked any TV series yet.</p>
    @else
        <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @foreach($tvItems as $item)
                @include('partials.media-card', ['item' => $item])
            @endforeach
        </div>
    @endif
</section>

@endsection
