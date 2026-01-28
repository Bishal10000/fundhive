<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Basic Information -->
        <div class="border-b pb-4">
            <h3 class="text-md font-semibold text-gray-800 mb-4">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="name" :value="__('Full Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div>
                            <p class="text-sm mt-2 text-gray-800">
                                {{ __('Your email address is unverified.') }}

                                <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div>
                    <x-input-label for="phone" :value="__('Phone Number')" />
                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" autocomplete="tel" />
                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                </div>

                <div>
                    <x-input-label for="address" :value="__('Address')" />
                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)" autocomplete="address" />
                    <x-input-error class="mt-2" :messages="$errors->get('address')" />
                </div>
            </div>
        </div>

        <!-- Background Information -->
        <div class="border-b pb-4">
            <h3 class="text-md font-semibold text-gray-800 mb-2">Background Information</h3>
            <p class="text-sm text-gray-600 mb-4">This information helps increase your trust rating and credibility</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="education" :value="__('Education')" />
                    <x-text-input id="education" name="education" type="text" class="mt-1 block w-full" :value="old('education', $user->education)" placeholder="e.g., Bachelor's in Computer Science" />
                    <x-input-error class="mt-2" :messages="$errors->get('education')" />
                </div>

                <div>
                    <x-input-label for="occupation" :value="__('Current Occupation')" />
                    <x-text-input id="occupation" name="occupation" type="text" class="mt-1 block w-full" :value="old('occupation', $user->occupation)" placeholder="e.g., Software Engineer" />
                    <x-input-error class="mt-2" :messages="$errors->get('occupation')" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="work_history" :value="__('Work History / Experience')" />
                    <textarea id="work_history" name="work_history" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Briefly describe your professional experience...">{{ old('work_history', $user->work_history) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('work_history')" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="bio" :value="__('Bio / About Me')" />
                    <textarea id="bio" name="bio" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                </div>
            </div>
        </div>

        <!-- Verification Status -->
        @if($user->is_verified)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-600"></i>
                    <span class="text-sm font-medium text-green-800">Your profile is verified</span>
                </div>
                @if($user->verified_at)
                    <p class="text-xs text-green-600 mt-1">Verified on {{ $user->verified_at->format('F d, Y') }}</p>
                @endif
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    <span class="text-sm font-medium text-yellow-800">Profile verification pending</span>
                </div>
                <p class="text-xs text-yellow-600 mt-1">Complete your background information to improve your trust rating</p>
            </div>
        @endif

        <div class="flex items-center gap-4 pt-4">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved successfully!') }}</p>
            @endif
        </div>
    </form>
</section>
