<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Entertainment App</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#10141E] text-white font-sans antialiased">

    <div class="h-screen flex">
        <!-- Sidebar -->
        @auth
            <aside class="h-screen w-20 lg:w-24 bg-[#161D2F] flex flex-col justify-between py-6 px-4 rounded-2xl shrink-0 overflow-y-auto">

                <div class="space-y-8">
                    <div class="mb-10">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="mx-auto">
                        </a>
                    </div>
                    <nav class="space-y-6 text-center">
                        <a href="{{ route('home') }}" class="py-2 block group" aria-label="Home">
                            @php $isActive = request()->routeIs('home') ? '#FFFFFF' : '#5A698F'; @endphp
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="{{ $isActive }}" class="mx-auto group-hover:fill-[#FC4747] transition" viewBox="0 0 20 20">
                                <path d="M8.875 1.5a1.5 1.5 0 0 1 2.25 0l6.625 7.5A1.5 1.5 0 0 1 16.625 11H15v5.5a1.5 1.5 0 0 1-1.5 1.5h-2.25V13a.75.75 0 0 0-.75-.75h-1a.75.75 0 0 0-.75.75v5H6.5A1.5 1.5 0 0 1 5 16.5V11H3.375a1.5 1.5 0 0 1-1.125-2.5l6.625-7.5Z"/>
                            </svg>                          
                        </a>
                        <a href="{{ route('movies') }}" class="py-2 block group" aria-label="Movies">
                            @php $isActive = request()->routeIs('movies') ? '#FFFFFF' : '#5A698F'; @endphp
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="{{ $isActive }}" class="mx-auto group-hover:fill-[#FC4747] transition" viewBox="0 0 20 20">
                                <path d="M4.5 4.375a.625.625 0 0 1 .625-.625h1.25a.625.625 0 0 1 .625.625v.625h1.25v-.625A.625.625 0 0 1 8.875 3.75h1.25a.625.625 0 0 1 .625.625v.625h1.25v-.625a.625.625 0 0 1 .625-.625h1.25a.625.625 0 0 1 .625.625v.625H17a.625.625 0 0 1 .625.625v10a.625.625 0 0 1-.625.625H3a.625.625 0 0 1-.625-.625v-10A.625.625 0 0 1 3 5h1.5v-.625Z"/>
                            </svg>                          
                        </a>
                        <a href="{{ route('tvSeries') }}" class="py-2 block group" aria-label="TV Series">
                            @php $isActive = request()->routeIs('tvSeries') ? '#FFFFFF' : '#5A698F'; @endphp
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="{{ $isActive }}" class="mx-auto group-hover:fill-[#FC4747] transition" viewBox="0 0 20 20">
                                <path d="M10.403 3.267a.5.5 0 1 0-.806-.6L8.053 5H4.5A1.5 1.5 0 0 0 3 6.5v8A1.5 1.5 0 0 0 4.5 16h11a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 15.5 5H11.95l-1.547-1.733Z"/>
                            </svg>
                        </a>
                        <a href="{{ route('bookmarks') }}" class="py-2 block group" aria-label="Bookmarks">
                            @php $isActive = request()->routeIs('bookmarks') ? '#FFFFFF' : '#5A698F'; @endphp
                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="20" fill="{{ $isActive }}" class="mx-auto group-hover:fill-[#FC4747] transition" viewBox="0 0 17 20">
                                <path d="M3.75 0A1.75 1.75 0 0 0 2 1.75v16.933a.5.5 0 0 0 .76.429l5.74-3.444a.5.5 0 0 1 .5 0l5.74 3.444a.5.5 0 0 0 .76-.429V1.75A1.75 1.75 0 0 0 13.75 0H3.75Z"/>
                            </svg>
                        </a>
                        <a href="{{ route('search') }}" class="py-2 block group relative" aria-label="Search">
                            @php $isActive = request()->routeIs('search') ? '#FFFFFF' : '#5A698F'; @endphp
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="{{ $isActive }}" class="mx-auto group-hover:fill-[#FC4747] transition" viewBox="0 0 24 24">
                                <path d="M10 2a8 8 0 105.293 14.293l5.207 5.207a1 1 0 001.414-1.414l-5.207-5.207A8 8 0 0010 2zm0 2a6 6 0 110 12 6 6 0 010-12z"/>
                            </svg>
                            <span class="sr-only">Search</span>
                        </a>
                        
                        
                    </nav>
                </div>

                <div class="text-center">
                    <a href="{{ route('profile.edit') }}">
                        <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/image-avatar.png') }}"
                        alt="User Avatar"
                        class="w-10 h-10 rounded-full object-cover mx-auto border">
                    </a>
                    @if(Auth::check())
                        <form method="POST" action="{{ route('logout') }}" class="text-center mt-6">
                            @csrf
                            <button type="submit" class="text-sm text-[#FC4747] hover:underline">
                                Logout
                            </button>
                        </form>
                    @endif

                </div>
            </aside>
        @endauth

        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-x-hidden">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleBookmark(id, type, buttonElement) {
            fetch('/bookmarks/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    media_id: id,
                    media_type: type,
                }),
            })
            .then(res => res.json())
            .then(data => {
                const svg = buttonElement.querySelector('.bookmark-icon');
                if (data.status === 'added') {
                    svg.setAttribute('fill', '#FC4747');
                } else if (data.status === 'removed') {
                    svg.setAttribute('fill', 'none');
                }
            })
            .catch(error => {
                console.error('Bookmark toggle failed:', error);
            });
        }


        </script>

        

</body>
</html>
