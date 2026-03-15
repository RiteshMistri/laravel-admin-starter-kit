@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="User Management" />

    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="border-b border-gray-200 px-6 py-5 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">All Users</h3>
                <p class="text-sm text-gray-500 mt-1">Manage user roles, permissions, and passwords.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition">
                Create User
            </a>
        </div>

        @if(session('success'))
            <div class="px-6 py-4 bg-success-50 text-success-700 border-b border-gray-200 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="px-6 py-4 bg-error-50 text-error-700 border-b border-gray-200 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto w-full">
            <table class="w-full whitespace-nowrap text-left text-sm text-gray-600">
                <thead class="bg-gray-50 uppercase text-gray-600 font-medium">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Roles</th>
                        <th class="px-6 py-4">Permissions</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 flex-shrink-0 rounded-full overflow-hidden bg-gray-100 border border-gray-200 text-transparent">
                                    @if($user->profile_image)
                                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="user" class="h-full w-full object-cover" />
                                    @else
                                        <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                          <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($user->roles as $role)
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="text-gray-400 italic">None</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($user->permissions as $permission)
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                        {{ $permission->name }}
                                    </span>
                                @empty
                                    <span class="text-gray-400 italic">None</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.users.edit', $user) }}" class="inline-block text-brand-500 hover:text-brand-600 font-medium text-sm mr-3">Edit</a>
                            
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-error-500 hover:text-error-600 font-medium text-sm">Delete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
