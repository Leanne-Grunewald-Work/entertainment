@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#10141E] px-4">
    <div class="w-full max-w-md bg-[#161D2F] p-8 rounded-2xl shadow-md">
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="h-6">
        </div>

        <h2 class="text-white text-2xl font-semibold mb-6 text-center">Login</h2>

        @if(session('status'))
            <div class="text-green-500 text-sm mb-4 text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="sr-only">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                    class="w-full bg-transparent border-b border-gray-500 placeholder-gray-400 text-white py-2 focus:outline-none focus:border-[#FC4747]"
                    placeholder="Email address">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="sr-only">Password</label>
                <input id="password" name="password" type="password" required
                    class="w-full bg-transparent border-b border-gray-500 placeholder-gray-400 text-white py-2 focus:outline-none focus:border-[#FC4747]"
                    placeholder="Password">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" name="remember" type="checkbox"
                    class="text-[#FC4747] focus:ring-[#FC4747] rounded border-gray-600 bg-[#10141E]">
                <label for="remember_me" class="ml-2 text-sm text-gray-300">
                    Remember me
                </label>
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-[#FC4747] hover:bg-[#d43e3e] text-white font-medium py-2 rounded-md transition">
                    Login to your account
                </button>
            </div>
        </form>

        <p class="text-center text-sm text-gray-400 mt-6">
            Don’t have an account?
            <a href="{{ route('register') }}" class="text-[#FC4747] hover:underline">Sign Up</a>
        </p>
    </div>
</div>
@endsection
