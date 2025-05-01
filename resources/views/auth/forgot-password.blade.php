@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#10141E] px-4">
    <div class="w-full max-w-md bg-[#161D2F] p-8 rounded-2xl shadow-md">
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="h-6">
        </div>

        <h2 class="text-white text-2xl font-semibold mb-6 text-center">Reset Password</h2>

        @if (session('status'))
            <div class="text-green-500 text-sm mb-4 text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="sr-only">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full bg-transparent border-b border-gray-500 placeholder-gray-400 text-white py-2 focus:outline-none focus:border-[#FC4747]"
                    placeholder="Email address">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-[#FC4747] hover:bg-[#d43e3e] text-white font-medium py-2 rounded-md transition">
                    Send Reset Link
                </button>
            </div>
        </form>

        <p class="text-center text-sm text-gray-400 mt-6">
            Remember your password?
            <a href="{{ route('login') }}" class="text-[#FC4747] hover:underline">Login</a>
        </p>
    </div>
</div>
@endsection
