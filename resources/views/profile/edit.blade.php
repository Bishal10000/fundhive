@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Profile Settings</h1>
            <p class="text-gray-600 mt-2">Manage your account information and preferences</p>
        </div>

        <div class="space-y-6">
            <!-- Update Profile Information -->
            <div class="p-6 bg-white shadow-sm rounded-lg">
                <div class="max-w-3xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-6 bg-white shadow-sm rounded-lg">
                <div class="max-w-3xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="p-6 bg-white shadow-sm rounded-lg">
                <div class="max-w-3xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
