<div class="relative bg-cover bg-center h-[200px] sm:h-[260px] rounded-lg overflow-hidden group"
     style="background-image: url('{{ $item['Poster'] }}')">
    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
        <button class="bg-white text-black font-semibold px-4 py-2 rounded-lg text-sm">Play ▶</button>
    </div>
    <div class="absolute bottom-0 left-0 right-0 backdrop-blur-md bg-black/50 p-4">
        <p class="text-sm text-gray-300">
            {{ $item['Year'] }} • {{ $item['Type'] }} • {{ $item['ContentRating'] }} • ★ {{ $item['Rating'] }}
        </p>
        <h3 class="text-lg font-semibold">{{ $item['Title'] }}</h3>
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
