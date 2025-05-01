@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#10141E] px-4">
    <div class="max-w-md w-full space-y-6 bg-[#161D2F] p-8 rounded-xl shadow-xl text-white">
        <div class="text-center space-y-3">
            <h2 class="text-2xl font-bold">Verify Your Email</h2>
            <p class="text-sm text-gray-400">
                Thanks for signing up! Before getting started, please verify your email address by clicking the link we just emailed to you.
                If you didn’t receive the email, we’ll gladly send you another.
            </p>
        </div>

        @if (session('status') === 'verification-link-sent')
            <div class="text-green-400 text-sm">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
            @csrf

            <div>
                <button type="submit" class="w-full py-2 px-4 bg-[#FC4747] text-white font-semibold rounded hover:bg-[#ff3d3d] transition">
                    Resend Verification Email
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-sm text-gray-400 hover:text-white underline">
                Log Out
            </button>
        </form>
    </div>
</div>
@endsection
