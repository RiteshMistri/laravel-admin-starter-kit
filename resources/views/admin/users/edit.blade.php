@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Edit User" />

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                <!-- Name -->
                <div class="col-span-2 sm:col-span-1">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10" />
                    <x-input-error class="mt-2 text-error-500 text-sm" :messages="$errors->get('name')" />
                </div>

                <!-- Email -->
                <div class="col-span-2 sm:col-span-1">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10" />
                    <x-input-error class="mt-2 text-error-500 text-sm" :messages="$errors->get('email')" />
                </div>

                <!-- Password -->
                <div class="col-span-2 sm:col-span-1">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">New Password <span class="text-xs text-gray-400 font-normal ml-1">(Leave blank to keep current)</span></label>
                    <input type="password" name="password" autocomplete="new-password" placeholder="Enter new password"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10" />
                    <x-input-error class="mt-2 text-error-500 text-sm" :messages="$errors->get('password')" />
                </div>

                <!-- Confirm Password -->
                <div class="col-span-2 sm:col-span-1">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" autocomplete="new-password" placeholder="Confirm new password"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10" />
                </div>
            </div>

            <hr class="border-gray-200 mb-6">

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                <!-- Roles -->
                <div>
                    <h4 class="mb-3 text-base font-semibold text-gray-800">Assign Role</h4>
                    <select name="roles" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10">
                        <option value="" disabled>Select a role...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ (old('roles', in_array($role->name, $userRoles) ? $role->name : null) == $role->name) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2 text-error-500 text-sm" :messages="$errors->get('roles')" />
                </div>

                <!-- Permissions -->
                <div class="col-span-2">
                    <h4 class="mb-3 text-base font-semibold text-gray-800">Assign Permissions <span class="text-xs text-gray-500 font-normal ml-1">(Module Based)</span></h4>
                    
                    @php
                        // Group permissions by prefix (e.g. user-management.read -> user-management)
                        $groupedPermissions = $permissions->groupBy(function($item) {
                            $parts = explode('.', $item->name);
                            return count($parts) > 1 ? $parts[0] : 'general';
                        });
                    @endphp

                    <div class="overflow-hidden rounded-xl border border-gray-200 shadow-theme-xs bg-white">
                        <table class="w-full text-left text-sm text-gray-700" id="permissions-matrix">
                            <thead class="bg-gray-50/80 border-b border-gray-200 text-gray-800">
                                <tr>
                                    <th class="px-5 py-3.5 font-semibold">Module</th>
                                    @php
                                        // Standard CRUD pillars we expect across the board
                                        $standardActions = ['read', 'add', 'edit', 'delete'];
                                    @endphp
                                    @foreach($standardActions as $globalAction)
                                        <th class="px-5 py-3.5 font-semibold text-center w-24">
                                            <div class="flex flex-col items-center gap-1.5">
                                                <span class="capitalize">{{ $globalAction }}</span>
                                                <input type="checkbox" 
                                                    class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500 matrix-header-checkbox"
                                                    data-header-action="{{ $globalAction }}"
                                                    title="Select all {{ $globalAction }}"
                                                >
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($groupedPermissions as $module => $modulePermissions)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-5 py-4 font-medium text-gray-800 capitalize">
                                            {{ str_replace('-', ' ', $module) }}
                                        </td>
                                        
                                        @foreach($standardActions as $targetAction)
                                            <td class="px-5 py-4 text-center">
                                                @php
                                                    // See if this module has this specific standard action permission
                                                    $foundPermission = $modulePermissions->first(function($p) use ($targetAction) {
                                                        $parts = explode('.', $p->name);
                                                        $action = count($parts) > 1 ? $parts[1] : $p->name;
                                                        return $action === $targetAction;
                                                    });
                                                @endphp
                                                
                                                @if($foundPermission)
                                                    <input type="checkbox" name="permissions[]" value="{{ $foundPermission->name }}" 
                                                        data-action="{{ $targetAction }}"
                                                        class="h-4.5 w-4.5 rounded border-gray-300 text-green-500 focus:ring-green-500 cursor-pointer"
                                                        {{ (is_array(old('permissions')) && in_array($foundPermission->name, old('permissions'))) || in_array($foundPermission->name, $userPermissions) ? 'checked' : '' }}
                                                    >
                                                @else
                                                    <span class="text-gray-300 text-xs">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 border-t border-gray-200 pt-6">
                <button type="submit" class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Save Changes
                </button>
                <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const matrix = document.getElementById('permissions-matrix');
            if (!matrix) return;

            matrix.addEventListener('change', function(e) {
                const target = e.target;
                
                // 1. If Header Checkbox is clicked
                if (target.matches('.matrix-header-checkbox')) {
                    const action = target.getAttribute('data-header-action');
                    const isChecked = target.checked;
                    
                    // Find all checkboxes in the body matching this action
                    const checkboxes = matrix.querySelectorAll(`tbody input[data-action='${action}']`);
                    checkboxes.forEach(cb => {
                        cb.checked = isChecked;
                        
                        // If we are checking Add/Edit/Delete, auto-check Read on this row
                        if (isChecked && action !== 'read') {
                            const row = cb.closest('tr');
                            if (row) {
                                const readCb = row.querySelector(`input[data-action='read']`);
                                if (readCb && !readCb.checked) readCb.checked = true;
                            }
                        }
                    });
                }
                
                // 2. If a Body Checkbox is clicked (Handle horizontal Read dependency)
                else if (target.matches('tbody input[data-action]')) {
                    const action = target.getAttribute('data-action');
                    if (target.checked && action !== 'read') {
                        const row = target.closest('tr');
                        if (row) {
                            const readCb = row.querySelector(`input[data-action='read']`);
                            if (readCb && !readCb.checked) readCb.checked = true;
                        }
                    }
                }
            });
        });
    </script>
    @endpush
@endsection
