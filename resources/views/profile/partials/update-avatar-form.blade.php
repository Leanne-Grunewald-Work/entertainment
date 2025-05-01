<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Update Avatar</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Upload a square image (JPG or PNG).
        </p>
    </header>

    @if (session('status') === 'avatar-updated')
        <div class="mb-4 text-green-600 dark:text-green-400 font-medium">
            Avatar updated successfully!
        </div>
    @endif

    <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">Current Avatar:</p>
            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/image-avatar.png') }}"
                 alt="Current Avatar"
                 class="w-20 h-20 rounded-full object-cover border">
        </div>
        

        <div>
            <input type="file" name="avatar" accept="image/*" required>
            @error('avatar')
                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-primary-button>Upload</x-primary-button>
        </div>
    </form>
</section>
