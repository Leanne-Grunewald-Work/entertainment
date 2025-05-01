@extends('layouts.main-layout')

@section('content')

<!-- Trending Section -->
<section class="relative w-full mb-10 overflow-x-hidden">
    <h2 class="text-xl mb-6 px-4 sm:px-6 lg:px-8">Trending</h2>
    <div class="pl-4 pr-4 sm:pl-6 sm:pr-6 lg:pl-8 lg:pr-8">
        <div class="flex gap-6 overflow-x-auto scrollbar-hide cursor-grab active:cursor-grabbing touch-pan-x select-none" id="trending-scroll">
            @foreach($trendingItems as $item)
                <div class="relative min-w-[470px] h-[230px] sm:h-[230px] md:h-[300px] bg-cover bg-center rounded-lg overflow-hidden group shrink-0"
                     style="background-image: url('{{ $item['Poster'] }}')">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                        <button class="bg-white text-black font-semibold px-4 py-2 rounded-lg text-sm">Play ▶</button>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 backdrop-blur-md bg-black/50 p-4">
                        <p class="text-sm text-gray-300">
                            {{ $item['Year'] }} • {{ $item['Type'] }} • {{ $item['Genres'] ?? '—' }} • {{ $item['ContentRating'] ?? 'NR' }} • ★ {{ $item['Rating'] ?? '0.0' }}
                        </p>
                        <h3 class="text-lg font-semibold text-white">{{ $item['Title'] }}</h3>
                    </div>
                    
                    <!-- Bookmark Button -->
                    <button
                        onclick="toggleBookmark('{{ $item['id'] }}', '{{ strtolower($item['Type']) === 'movie' ? 'movie' : 'tv' }}', this)"
                        class="absolute top-2 right-2 w-10 h-10 flex items-center justify-center bg-black/50 rounded-full hover:bg-white transition group"
                        >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 17 20"
                            width="17"
                            height="20"
                            class="bookmark-icon transition"
                            fill="{{ $item['isBookmarked'] ? '#FC4747' : 'none' }}"
                            stroke="#FC4747"
                        >
                            <path d="M3.75 0A1.75 1.75 0 0 0 2 1.75v16.933a.5.5 0 0 0 .76.429l5.74-3.444a.5.5 0 0 1 .5 0l5.74 3.444a.5.5 0 0 0 .76-.429V1.75A1.75 1.75 0 0 0 13.75 0H3.75Z"/>
                        </svg>
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Recommended Section -->
<section class="px-4 sm:px-6 lg:px-8">
    <h2 class="text-xl mb-6">Recommended for You</h2>

    <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        @foreach($recommendedItems as $item)
            <div class="relative bg-cover bg-center h-[200px] sm:h-[260px] rounded-lg overflow-hidden group"
                 style="background-image: url('{{ $item['Poster'] }}')">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                    <button class="bg-white text-black font-semibold px-4 py-2 rounded-lg text-sm">Play ▶</button>
                </div>
                <div class="absolute bottom-0 left-0 right-0 backdrop-blur-md bg-black/50 p-4">
                    <p class="text-sm text-gray-300">
                        {{ $item['Year'] }} • {{ $item['Type'] }} • {{ $item['Genres'] ?? '—' }} • {{ $item['ContentRating'] ?? 'NR' }} • ★ {{ $item['Rating'] ?? '0.0' }}
                    </p>
                    <h3 class="text-lg font-semibold text-white">{{ $item['Title'] }}</h3>
                </div>
                
                <!-- Bookmark Button -->
                <button
                    onclick="toggleBookmark('{{ $item['id'] }}', '{{ strtolower($item['Type']) === 'movie' ? 'movie' : 'tv' }}', this)"
                    class="absolute top-2 right-2 w-10 h-10 flex items-center justify-center bg-black/50 rounded-full hover:bg-white transition group"
                    >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 17 20"
                        width="17"
                        height="20"
                        class="bookmark-icon transition"
                        fill="{{ $item['isBookmarked'] ? '#FC4747' : 'none' }}"
                        stroke="#FC4747"
                    >
                        <path d="M3.75 0A1.75 1.75 0 0 0 2 1.75v16.933a.5.5 0 0 0 .76.429l5.74-3.444a.5.5 0 0 1 .5 0l5.74 3.444a.5.5 0 0 0 .76-.429V1.75A1.75 1.75 0 0 0 13.75 0H3.75Z"/>
                    </svg>
                </button>
            </div>
        @endforeach
    </div>
</section>

<!-- Carousel Drag Script -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('trending-scroll');

        if (!container) return;

        let isDown = false;
        let startX;
        let scrollLeft;

        container.addEventListener('mousedown', (e) => {
            isDown = true;
            container.classList.add('cursor-grabbing');
            startX = e.pageX - container.offsetLeft;
            scrollLeft = container.scrollLeft;
        });

        container.addEventListener('mouseleave', () => {
            isDown = false;
            container.classList.remove('cursor-grabbing');
        });

        container.addEventListener('mouseup', () => {
            isDown = false;
            container.classList.remove('cursor-grabbing');
        });

        container.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - container.offsetLeft;
            const walk = (x - startX) * 2;
            container.scrollLeft = scrollLeft - walk;
        });
    });
</script>

@endsection
