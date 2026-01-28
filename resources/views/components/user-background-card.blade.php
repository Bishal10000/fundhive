<!-- User Profile Background Card -->
<div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-md p-6">
    <div class="flex items-start justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-user-circle text-blue-600"></i>
                {{ Auth::user()->name }}'s Profile
            </h2>
            <p class="text-sm text-gray-600 mt-1">Member since {{ Auth::user()->created_at->format('F Y') }}</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 rounded-lg border border-gray-200 transition">
            <i class="fas fa-edit"></i> Edit Profile
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Contact Info -->
        <div class="bg-white rounded-lg p-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>Contact Info
            </h3>
            <div class="space-y-2 text-sm">
                <p><span class="font-medium text-gray-700">Email:</span><br>{{ Auth::user()->email }}</p>
                <p><span class="font-medium text-gray-700">Phone:</span><br>{{ Auth::user()->phone ?? 'Not provided' }}</p>
                <p><span class="font-medium text-gray-700">Location:</span><br>{{ Auth::user()->address ?? 'Not provided' }}</p>
            </div>
        </div>

        <!-- Background Info -->
        <div class="bg-white rounded-lg p-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">
                <i class="fas fa-briefcase text-green-500 mr-2"></i>Background
            </h3>
            <div class="space-y-2 text-sm">
                <p><span class="font-medium text-gray-700">Education:</span><br>{{ Auth::user()->education ?? 'Not provided' }}</p>
                <p><span class="font-medium text-gray-700">Occupation:</span><br>{{ Auth::user()->occupation ?? 'Not provided' }}</p>
                @if(Auth::user()->work_history)
                    <p><span class="font-medium text-gray-700">Experience:</span><br>{{ Str::limit(Auth::user()->work_history, 100) }}</p>
                @endif
            </div>
        </div>

        <!-- Verification Status -->
        <div class="bg-white rounded-lg p-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">
                <i class="fas fa-shield-alt text-purple-500 mr-2"></i>Verification Status
            </h3>
            <div class="space-y-2 text-sm">
                <p>
                    <span class="font-medium text-gray-700">Email:</span>
                    <span class="ml-2 inline-block px-2 py-1 rounded text-xs font-medium
                        {{ Auth::user()->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ Auth::user()->email_verified_at ? '✓ Verified' : '⚠ Pending' }}
                    </span>
                </p>
                <p>
                    <span class="font-medium text-gray-700">Profile:</span>
                    <span class="ml-2 inline-block px-2 py-1 rounded text-xs font-medium
                        {{ Auth::user()->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ Auth::user()->is_verified ? '✓ Verified' : '⚠ Pending' }}
                    </span>
                </p>
                @if(Auth::user()->verified_at)
                    <p class="text-xs text-gray-500">Verified on {{ Auth::user()->verified_at->format('M d, Y') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
