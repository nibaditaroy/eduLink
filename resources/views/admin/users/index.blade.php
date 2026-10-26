@extends('admin.layouts.admin')

@section('title', 'User & Student Management')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">User Management</h1>
            <p class="text-xs text-gray-500 mt-0.5">Manage student accounts, instructors, security access (active, suspended, blocked), and track learning progress.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                <i class="fa-solid fa-user-plus me-2"></i> Add New User
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards (Click to filter) -->
    <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
        <a href="{{ route('admin.users.index') }}" 
           class="bg-white p-4 rounded-2xl border {{ !request('role') && !request('status') ? 'border-slate-900 ring-2 ring-slate-900/10' : 'border-gray-200 hover:border-gray-300' }} shadow-sm transition block">
            <div class="text-xs text-gray-500 font-medium">Total Users</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total']) }}</div>
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'student', 'search' => request('search')]) }}" 
           class="bg-white p-4 rounded-2xl border {{ request('role') === 'student' ? 'border-indigo-600 ring-2 ring-indigo-600/10' : 'border-gray-200 hover:border-indigo-200' }} shadow-sm transition block">
            <div class="text-xs text-indigo-600 font-medium">Students</div>
            <div class="text-2xl font-bold text-indigo-600 mt-1">{{ number_format($stats['students']) }}</div>
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'admin', 'search' => request('search')]) }}" 
           class="bg-white p-4 rounded-2xl border {{ request('role') === 'admin' ? 'border-purple-600 ring-2 ring-purple-600/10' : 'border-gray-200 hover:border-purple-200' }} shadow-sm transition block">
            <div class="text-xs text-purple-600 font-medium">Admins</div>
            <div class="text-2xl font-bold text-purple-600 mt-1">{{ number_format($stats['admins']) }}</div>
        </a>
        <a href="{{ route('admin.users.index', ['status' => 'active', 'search' => request('search')]) }}" 
           class="bg-emerald-50/70 p-4 rounded-2xl border {{ request('status') === 'active' ? 'border-emerald-600 ring-2 ring-emerald-600/20' : 'border-emerald-200 hover:border-emerald-300' }} shadow-sm transition block">
            <div class="text-xs text-emerald-700 font-medium flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active
            </div>
            <div class="text-2xl font-bold text-emerald-800 mt-1">{{ number_format($stats['active']) }}</div>
        </a>
        <a href="{{ route('admin.users.index', ['status' => 'suspended', 'search' => request('search')]) }}" 
           class="bg-amber-50/70 p-4 rounded-2xl border {{ request('status') === 'suspended' ? 'border-amber-600 ring-2 ring-amber-600/20' : 'border-amber-200 hover:border-amber-300' }} shadow-sm transition block">
            <div class="text-xs text-amber-700 font-medium flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span> Suspended
            </div>
            <div class="text-2xl font-bold text-amber-800 mt-1">{{ number_format($stats['suspended']) }}</div>
        </a>
        <a href="{{ route('admin.users.index', ['status' => 'blocked', 'search' => request('search')]) }}" 
           class="bg-rose-50/70 p-4 rounded-2xl border {{ request('status') === 'blocked' ? 'border-rose-600 ring-2 ring-rose-600/20' : 'border-rose-200 hover:border-rose-300' }} shadow-sm transition block">
            <div class="text-xs text-rose-700 font-medium flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span> Blocked
            </div>
            <div class="text-2xl font-bold text-rose-800 mt-1">{{ number_format($stats['blocked']) }}</div>
        </a>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-center">
            <div class="md:col-span-2 relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or phone (press Enter)..." 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-9 p-2.5">
            </div>

            <div>
                <select name="role" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 cursor-pointer">
                    <option value="">All Roles</option>
                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
                <button type="submit" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition flex-shrink-0">
                    Filter
                </button>
                @if(request('search') || request('role') || request('status'))
                <a href="{{ route('admin.users.index') }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition text-xs flex-shrink-0" title="Reset Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4">User</th>
                        <th scope="col" class="px-6 py-4">Role</th>
                        <th scope="col" class="px-6 py-4">Phone</th>
                        <th scope="col" class="px-6 py-4">Status & Access</th>
                        <th scope="col" class="px-6 py-4 text-center">Enrollments</th>
                        <th scope="col" class="px-6 py-4 text-center">Quick Status Change</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/60 transition">
                        <!-- User Info -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700 border-purple-200' : 'bg-indigo-100 text-indigo-700 border-indigo-200' }} border flex items-center justify-center font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="font-bold text-gray-900 hover:text-indigo-600 transition block">
                                        {{ $user->name }}
                                    </a>
                                    <span class="text-xs text-gray-400">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Role -->
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-purple-50 text-purple-700 border border-purple-200">
                                <i class="fa-solid fa-shield-halved me-1 text-[10px]"></i> Admin
                            </span>
                            @else
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-blue-50 text-blue-700 border border-blue-200">
                                <i class="fa-solid fa-graduation-cap me-1 text-[10px]"></i> Student
                            </span>
                            @endif
                        </td>

                        <!-- Phone -->
                        <td class="px-6 py-4 text-xs font-medium text-gray-700">
                            {{ $user->phone ?? '—' }}
                        </td>

                        <!-- Status Badge -->
                        <td class="px-6 py-4">
                            @if($user->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                            @elseif($user->status === 'suspended')
                            <div>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Suspended
                                </span>
                                @if($user->status_reason)
                                <div class="text-[10px] text-gray-500 mt-1 max-w-xs truncate" title="{{ $user->status_reason }}">
                                    {{ $user->status_reason }}
                                </div>
                                @endif
                            </div>
                            @elseif($user->status === 'blocked')
                            <div>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <i class="fa-solid fa-ban text-[10px]"></i> Blocked
                                </span>
                                @if($user->status_reason)
                                <div class="text-[10px] text-rose-600 mt-1 max-w-xs truncate font-medium" title="{{ $user->status_reason }}">
                                    {{ $user->status_reason }}
                                </div>
                                @endif
                            </div>
                            @endif
                        </td>

                        <!-- Enrollments -->
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                {{ $user->enrollments_count }} {{ Str::plural('Course', $user->enrollments_count) }}
                            </span>
                        </td>

                        <!-- Quick Status Switch Form -->
                        <td class="px-6 py-4 text-center">
                            @if(Auth::id() !== $user->id)
                            <div class="inline-flex items-center gap-1">
                                <select onchange="handleUserStatusChange(this, {{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->status }}', '{{ addslashes($user->status_reason ?? '') }}')" class="text-xs py-1 px-2 rounded-lg border border-gray-300 bg-white font-medium text-gray-700 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer shadow-xs">
                                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>✅ Active</option>
                                    <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>⏸️ Suspended</option>
                                    <option value="blocked" {{ $user->status === 'blocked' ? 'selected' : '' }}>🚫 Blocked</option>
                                </select>
                            </div>
                            @else
                            <span class="text-[11px] text-gray-400 italic">Self Account</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.users.show', $user->id) }}" title="View 360° Learning Progress & Transactions" 
                                   class="p-2 text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition text-xs">
                                    <i class="fa-solid fa-chart-line"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" title="Edit User" 
                                   class="p-2 text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 rounded-lg transition text-xs">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @if(Auth::id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" data-confirm-delete="Are you sure you want to permanently delete user &lt;strong&gt;{{ addslashes($user->name) }}&lt;/strong&gt; ({{ $user->email }})? All course enrollments, learning progress, and payments will be removed." data-confirm-title="Delete User Account">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete User" class="p-2 text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 rounded-lg transition text-xs">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                <i class="fa-solid fa-user-slash"></i>
                            </div>
                            <div class="font-medium text-gray-900">No users found</div>
                            <p class="text-xs text-gray-500 mt-1">Try tweaking your search or filter parameters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>

<!-- User Status Change Modal -->
<div id="user-status-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-200 ease-out" role="dialog" aria-modal="true">
    <div id="user-status-modal-card" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-gray-100 transform scale-95 transition-transform duration-200 ease-out">
        <form id="user-status-form" method="POST" action="">
            @csrf
            @method('PATCH')
            
            <div class="flex items-center gap-3 mb-4">
                <div id="status-icon-box" class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-user-lock"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Change Account Status</h3>
                    <p id="status-modal-user-name" class="text-xs text-gray-500 font-medium"></p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Account Status</label>
                    <select name="status" id="modal-status-select" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 font-semibold">
                        <option value="active">🟢 Active (Full Access)</option>
                        <option value="suspended">⏸️ Suspended (Temporary Hold)</option>
                        <option value="blocked">🚫 Blocked (Restricted)</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">
                        Reason for Suspension / Restriction <span class="text-gray-400 font-normal">(Optional)</span>
                    </label>
                    <textarea name="status_reason" id="modal-status-reason" rows="3" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3"
                        placeholder="e.g. Account flagged for academic irregularity, disputed payment, etc."></textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                <button type="button" onclick="closeUserStatusModal()" class="px-4 py-2.5 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl transition shadow-xs">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-md shadow-indigo-600/20">
                    <i class="fa-solid fa-check me-1.5"></i> Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let activeStatusSelect = null;
let originalStatusVal = null;

function handleUserStatusChange(selectElement, userId, userName, currentStatus, currentReason) {
    activeStatusSelect = selectElement;
    originalStatusVal = currentStatus;
    const targetStatus = selectElement.value;

    const modal = document.getElementById('user-status-modal');
    const modalCard = document.getElementById('user-status-modal-card');
    const form = document.getElementById('user-status-form');
    const nameEl = document.getElementById('status-modal-user-name');
    const statusSelect = document.getElementById('modal-status-select');
    const reasonEl = document.getElementById('modal-status-reason');

    form.action = `/admin/users/${userId}/status`;
    nameEl.textContent = userName;
    statusSelect.value = targetStatus;
    reasonEl.value = currentReason || '';

    modal.classList.remove('opacity-0', 'pointer-events-none');
    modalCard.classList.remove('scale-95');
    modalCard.classList.add('scale-100');
    document.body.style.overflow = 'hidden';
}

function closeUserStatusModal() {
    const modal = document.getElementById('user-status-modal');
    const modalCard = document.getElementById('user-status-modal-card');
    modal.classList.add('opacity-0', 'pointer-events-none');
    modalCard.classList.remove('scale-100');
    modalCard.classList.add('scale-95');
    document.body.style.overflow = '';
    if (activeStatusSelect && originalStatusVal) {
        activeStatusSelect.value = originalStatusVal;
    }
}

document.getElementById('user-status-modal').addEventListener('click', (e) => {
    if (e.target === document.getElementById('user-status-modal')) {
        closeUserStatusModal();
    }
});
</script>
@endsection
