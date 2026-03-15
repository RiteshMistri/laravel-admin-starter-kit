@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Settings" />
    
    <div class="grid grid-cols-1 xl:grid-cols-5 gap-8">
        <div class="col-span-5 xl:col-span-3">
            <div class="rounded-2xl border border-gray-200 bg-white">
                <div class="border-b border-gray-200 px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-800">Personal Information</h3>
                    <p class="text-sm text-gray-500 mt-1">Update your account's profile information.</p>
                </div>
                
                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('patch')

                    <div class="mb-6 flex items-center gap-5">
                        <div class="h-20 w-20 overflow-hidden rounded-full border border-gray-200 shadow-sm relative group bg-gray-50">
                            @if($user->profile_image)
                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="user" class="h-full w-full object-cover text-transparent" />
                            @else
                                <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                  <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <span class="mb-1.5 block text-sm font-medium text-gray-700">Profile Photo</span>
                            <div class="flex gap-3">
                                <label for="profile_image" class="cursor-pointer rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    Upload
                                    <input type="file" id="profile_image" name="profile_image" class="sr-only" accept="image/*">
                                </label>
                            </div>
                            <x-input-error class="mt-2 text-error-500 text-sm" :messages="$errors->get('profile_image')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Name -->
                        <div class="col-span-2 sm:col-span-1">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10" />
                            <x-input-error class="mt-2 text-error-500 text-sm" :messages="$errors->get('name')" />
                        </div>

                        <!-- Email (Disabled) -->
                        <div class="col-span-2 sm:col-span-1">
                            <label class="mb-1.5 flex items-center justify-between text-sm font-medium text-gray-700">
                                Email Address <span class="text-xs text-gray-400 font-normal ml-2">(Cannot be changed)</span>
                            </label>
                            <input type="email" value="{{ $user->email }}" disabled
                                class="h-11 w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 shadow-sm cursor-not-allowed" />
                        </div>

                        <!-- Phone Number -->
                        <div class="col-span-2 sm:col-span-1">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="+1 234 567 8900"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10" />
                            <x-input-error class="mt-2 text-error-500 text-sm" :messages="$errors->get('phone_number')" />
                        </div>
                        
                        <!-- Address -->
                        <div class="col-span-2">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" rows="3" placeholder="Enter your full address"
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10">{{ old('address', $user->address) }}</textarea>
                            <x-input-error class="mt-2 text-error-500 text-sm" :messages="$errors->get('address')" />
                        </div>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <button type="submit" class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                            Save Changes
                        </button>
                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-medium text-success-600">Saved successfully.</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="col-span-5 xl:col-span-2">
            <div class="rounded-2xl border border-gray-200 bg-white mb-8">
                <div class="border-b border-gray-200 px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-800">Security</h3>
                    <p class="text-sm text-gray-500 mt-1">Update your password to stay secure.</p>
                </div>
                
                <form method="post" action="{{ route('password.update') }}" class="p-6">
                    @csrf
                    @method('put')

                    <div class="space-y-5">
                        <!-- Current Password -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Current Password</label>
                            <input type="password" name="current_password" required autocomplete="current-password"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10" />
                            <x-input-error class="mt-2 text-error-500 text-sm" :messages="$errors->updatePassword->get('current_password')" />
                        </div>

                        <!-- New Password -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="password" required autocomplete="new-password"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10" />
                            <x-input-error class="mt-2 text-error-500 text-sm" :messages="$errors->updatePassword->get('password')" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" required autocomplete="new-password"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10" />
                            <x-input-error class="mt-2 text-error-500 text-sm" :messages="$errors->updatePassword->get('password_confirmation')" />
                        </div>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <button type="submit" class="rounded-lg bg-gray-800 px-5 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-gray-900">
                            Update Password
                        </button>
                        @if (session('status') === 'password-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-medium text-success-600">Password updated.</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
