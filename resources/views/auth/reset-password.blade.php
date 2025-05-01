@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#10141E] px-4">
    <div class="w-full max-w-md bg-[#161D2F] text-white p-8 rounded-lg shadow-lg">
        <h1 class="text-2xl font-semibold mb-6 text-center">Reset Your Password</h1>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

            <div class="mb-4">
                <label for="password" class="block mb-1 text-sm">New Password</label>
                <input id="password" name="password" type="password" required autofocus
                    class="w-full px-4 py-2 rounded bg-[#10141E] text-white placeholder-gray-500 border border-[#5A698F] focus:outline-none focus:ring-2 focus:ring-[#FC4747]">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block mb-1 text-sm">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full px-4 py-2 rounded bg-[#10141E] text-white placeholder-gray-500 border border-[#5A698F] focus:outline-none focus:ring-2 focus:ring-[#FC4747]">
            </div>

            <button type="submit"
                class="w-full bg-[#FC4747] text-white py-2 rounded hover:bg-[#ff6a6a] transition font-semibold">
                Reset Password
            </button>
        </form>
    </div>
</div>
@endsection
