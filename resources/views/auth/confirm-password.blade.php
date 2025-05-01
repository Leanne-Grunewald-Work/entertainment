@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#10141E] px-4">
    <div class="max-w-md w-full space-y-8 bg-[#161D2F] p-8 rounded-xl shadow-xl text-white">
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-bold">Confirm Password</h2>
            <p class="text-sm text-gray-400">This is a secure area of the app. Please confirm your password before continuing.</p>
        </div>

        @if (session('status'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
            @csrf

            <div>
                <label for="password" class="block text-sm mb-1">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full bg-[#10141E] border border-[#5A698F] rounded px-4 py-2 focus:ring-[#FC4747] focus:outline-none text-white">

                @error('password')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" class="w-full py-2 px-4 bg-[#FC4747] text-white font-semibold rounded hover:bg-[#ff3d3d] transition">
                    Confirm Password
                </button>
            </div>

            @if (Route::has('password.request'))
                <div class="text-sm text-right">
                    <a href="{{ route('password.request') }}" class="text-gray-400 hover:text-white underline">Forgot your password?</a>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
