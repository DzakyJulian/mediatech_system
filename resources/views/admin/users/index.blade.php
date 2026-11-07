@extends('admin.layouts.layout')

@section('title', 'User Management')
@section('page_title', 'User Management')
@section('page_breadcrumb', 'Administration / User Management')

@section('content')
    <div class="mb-6">
        <div class="mb-[7px] text-[12px] font-bold text-[#0d21a1]">Administration</div>
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <h1 class="m-0 text-[24px] font-extrabold leading-tight tracking-[-0.8px] text-[#171717] sm:text-[25px]">User
                Management</h1>
            @if (auth()->user()?->isSuperAdmin() &&
                    auth()->user()?->can('users.create') &&
                    auth()->user()?->can('users.assign_roles'))
                <a href="{{ route('admin.users.create') }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-[#0d21a1] px-[14px] py-[10px] text-[12px] font-bold text-white transition hover:bg-[#091772]"><svg
                        class="h-4 w-4 fill-none stroke-current" aria-hidden="true">
                        <use href="#i-plus"></use>
                    </svg>Tambah pengguna</a>
            @endif
        </div>
        <p class="mt-[6px] max-w-2xl text-[12px] leading-relaxed text-[#737373]">Kelola akun dan role dengan prinsip
            least privilege. Role Admin tidak otomatis memiliki hak approval transaksi.</p>
    </div>

    <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4">
        <div class="rounded-xl border border-[#e8eaf0] bg-white p-[18px] shadow-[0_1px_2px_rgba(20,27,55,0.02)]">
            <div class="text-[11px] font-semibold text-[#737373]">Total pengguna</div>
            <div class="mt-3 text-[25px] font-extrabold tracking-[-0.7px] text-[#171717]">{{ number_format($totalUsers) }}
            </div>
            <div class="mt-1 text-[10px] text-[#737373]">Seluruh role</div>
        </div>
        <div class="rounded-xl border border-[#e8eaf0] bg-white p-[18px] shadow-[0_1px_2px_rgba(20,27,55,0.02)]">
            <div class="text-[11px] font-semibold text-[#737373]">Akun aktif</div>
            <div class="mt-3 text-[25px] font-extrabold tracking-[-0.7px] text-[#16805d]">
                {{ number_format($activeUsersCount) }}</div>
            <div class="mt-1 text-[10px] text-[#737373]">Dapat login</div>
        </div>
        <div class="rounded-xl border border-[#e8eaf0] bg-white p-[18px] shadow-[0_1px_2px_rgba(20,27,55,0.02)]">
            <div class="text-[11px] font-semibold text-[#737373]">Role tersedia</div>
            <div class="mt-3 text-[25px] font-extrabold tracking-[-0.7px] text-[#171717]">{{ number_format($rolesCount) }}
            </div>
            <div class="mt-1 text-[10px] text-[#737373]">Sesuai baseline RBAC</div>
        </div>
    </div>

    <section class="overflow-hidden rounded-xl border border-[#e8eaf0] bg-white shadow-[0_1px_2px_rgba(20,27,55,0.02)]">
        <form id="user-filter-form" method="GET" action="{{ route('admin.users.index') }}"
            class="flex flex-col gap-2 border-b border-[#f0f1f5] p-[14px] sm:flex-row sm:flex-wrap sm:items-center sm:p-[16px]">
            <label class="relative block w-full sm:w-[230px]"><span class="sr-only">Cari pengguna</span><svg
                    class="absolute left-[11px] top-1/2 h-4 w-4 -translate-y-1/2 fill-none stroke-current text-[#a1a1a1]"
                    aria-hidden="true">
                    <use href="#i-search"></use>
                </svg><input id="user-search" type="search" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama / email…"
                    class="h-[36px] w-full rounded-lg border border-[#dedfe6] bg-white pl-[34px] pr-3 text-[12px] outline-none focus:border-[#5d6ac0] focus:ring-2 focus:ring-[#e7e9f6]"></label>
            <select id="user-role-filter" name="role_id" aria-label="Filter role"
                class="h-[36px] w-full rounded-lg border border-[#dedfe6] bg-white px-3 text-[12px] text-[#404040] sm:w-[165px]">
                <option value="">Semua role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->role_id }}" @selected((string) request('role_id') === (string) $role->role_id)>{{ $role->role_name }}</option>
                @endforeach
            </select>
            <select id="user-status-filter" name="status" aria-label="Filter status"
                class="h-[36px] w-full rounded-lg border border-[#dedfe6] bg-white px-3 text-[12px] text-[#404040] sm:w-[150px]">
                <option value="">Semua status</option>
                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
            </select>
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex h-[36px] items-center justify-center rounded-lg border border-[#dedfe6] bg-white px-3 text-[11px] font-semibold text-[#525252] hover:bg-[#fafafa]">Reset</a>
        </form>

        @can('users.export')
            <div class="flex justify-end px-4 pt-3"><a href="{{ route('admin.users.export', request()->query()) }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 bg-white px-3 py-[8px] text-[11px] font-semibold text-neutral-600 transition hover:bg-neutral-50"><svg
                        class="h-4 w-4 fill-none stroke-current" aria-hidden="true" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v11m0 0 4-4m-4 4-4-4M5 20h14"/></svg>Ekspor Excel / CSV</a></div>
        @endcan

        <div class="overflow-x-auto">
            <table class="w-full min-w-[820px] border-collapse text-left">
                <thead>
                    <tr class="bg-[#fafafa]">
                        <th class="border-b border-[#eeeeee] px-4 py-3 text-[11px] font-bold text-[#737373]">Pengguna</th>
                        <th class="border-b border-[#eeeeee] px-4 py-3 text-[11px] font-bold text-[#737373]">Email</th>
                        <th class="border-b border-[#eeeeee] px-4 py-3 text-[11px] font-bold text-[#737373]">Role</th>
                        <th class="border-b border-[#eeeeee] px-4 py-3 text-[11px] font-bold text-[#737373]">Terakhir aktif
                        </th>
                        <th class="border-b border-[#eeeeee] px-4 py-3 text-[11px] font-bold text-[#737373]">Status</th>
                        <th class="border-b border-[#eeeeee] px-4 py-3 text-[11px] font-bold text-[#737373]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="transition hover:bg-[#fcfcfe]">
                            <td class="border-b border-[#f0f1f5] px-4 py-[13px]">
                                <div class="flex items-center gap-[10px]"><span
                                        class="grid h-[34px] w-[34px] shrink-0 place-items-center rounded-full bg-[#e7e9f6] text-[11px] font-extrabold text-[#0d21a1]">{{ strtoupper(collect(explode(' ', trim($user->name)))->filter()->take(2)->map(fn($part) => mb_substr($part, 0, 1))->implode('')) }}</span><span
                                        class="min-w-0"><span
                                            class="block max-w-[190px] truncate text-[12px] font-bold text-[#262626]">{{ $user->name }}</span><span
                                            class="mt-[2px] block text-[10px] text-[#a1a1a1]">ID:
                                            {{ $user->user_id }}</span></span></div>
                            </td>
                            <td class="border-b border-[#f0f1f5] px-4 py-[13px] text-[11px] text-[#525252]">
                                {{ $user->email }}</td>
                            <td class="border-b border-[#f0f1f5] px-4 py-[13px]">
                                <div class="flex max-w-[210px] flex-wrap gap-1">
                                    @forelse ($user->roles as $userRole)
                                        <span
                                        class="inline-flex rounded-md bg-[#f1f2f5] px-2 py-1 text-[10px] font-semibold text-[#525252]">{{ $userRole->role_name }}</span>@empty<span
                                            class="text-[10px] text-[#a1a1a1]">Belum ada role</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="border-b border-[#f0f1f5] px-4 py-[13px] text-[11px] text-[#737373]">
                                {{ $user->last_login_at?->timezone('Asia/Jakarta')->format('d M Y, H:i') ?? 'Belum pernah login' }}
                            </td>
                            <td class="border-b border-[#f0f1f5] px-4 py-[13px]">
                                @if ($user->is_active)
                                    <span
                                        class="inline-flex items-center gap-[5px] rounded-[5px] bg-[#eaf7f0] px-2 py-1 text-[10px] font-extrabold text-[#16805d]"><span
                                        class="h-[5px] w-[5px] rounded-full bg-current"></span>Aktif</span>@else<span
                                        class="inline-flex items-center gap-[5px] rounded-[5px] bg-[#fff0ef] px-2 py-1 text-[10px] font-extrabold text-[#b54740]"><span
                                            class="h-[5px] w-[5px] rounded-full bg-current"></span>Nonaktif</span>
                                @endif
                            </td>
                            <td class="border-b border-[#f0f1f5] px-4 py-[13px]">
                                <div class="flex items-center gap-2"><a
                                        href="{{ route('admin.users.show', $user->user_id) }}"
                                        class="text-[11px] font-extrabold text-[#0d21a1] hover:underline">Detail</a>
                                    @can('users.update')
                                        <a href="{{ route('admin.users.edit', $user->user_id) }}"
                                            class="text-[11px] font-extrabold text-[#525252] hover:text-[#0d21a1] hover:underline">Edit</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <p class="text-[12px] font-bold text-[#404040]">Tidak ada pengguna.</p>
                                <p class="mt-1 text-[11px] text-[#737373]">Ubah kata kunci atau filter untuk melihat data
                                    lainnya.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-[#f0f1f5] px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap items-center gap-4 text-[10px] text-[#737373]"><span>Menampilkan
                    {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }}</span><label
                    class="flex items-center gap-2">Tampilkan <select id="user-per-page" name="per_page"
                        form="user-filter-form"
                        class="h-8 rounded-md border border-[#dedfe6] bg-white px-2 text-[10px] text-[#525252]">
                        <option value="5" @selected($perPage === 5)>5</option>
                        <option value="10" @selected($perPage === 10)>10</option>
                        <option value="25" @selected($perPage === 25)>25</option>
                        <option value="50" @selected($perPage === 50)>50</option>
                    </select></label></div>
            <div class="flex items-center justify-end gap-2 text-[11px]">
                <a href="{{ $users->previousPageUrl() ?? '#' }}"
                    @if (!$users->previousPageUrl()) aria-disabled="true" tabindex="-1" @endif
                    class="rounded-md border border-[#e5e5e5] px-3 py-2 font-semibold {{ $users->onFirstPage() ? 'pointer-events-none opacity-40' : 'text-[#525252] hover:bg-[#f5f6fb]' }}">Sebelumnya</a>
                <span class="px-1 text-[#737373]">Halaman
                    {{ $users->currentPage() }}/{{ max(1, $users->lastPage()) }}</span>
                <a href="{{ $users->nextPageUrl() ?? '#' }}"
                    @if (!$users->hasMorePages()) aria-disabled="true" tabindex="-1" @endif
                    class="rounded-md border border-[#e5e5e5] px-3 py-2 font-semibold {{ $users->hasMorePages() ? 'text-[#525252] hover:bg-[#f5f6fb]' : 'pointer-events-none opacity-40' }}">Berikutnya</a>
            </div>
        </div>
    </section>

    <div
        class="mt-[14px] rounded-lg border border-[#e7e9f6] bg-[#f5f6fb] px-4 py-3 text-[11px] leading-relaxed text-[#525252]">
        <span class="font-bold text-[#0d21a1]">Catatan akses:</span> Akun Super Admin terakhir tidak dapat dinonaktifkan.
        Pengguna dinonaktifkan, bukan dihapus, agar histori transaksi dan audit tetap terjaga.</div>
@endsection

@push('scripts')
    <script>
        (() => {
            const form = document.getElementById('user-filter-form');
            if (!form) return;
            const search = document.getElementById('user-search');
            const role = document.getElementById('user-role-filter');
            const status = document.getElementById('user-status-filter');
            const perPage = document.getElementById('user-per-page');
            let searchTimer;
            [role, status, perPage].forEach((field) => field?.addEventListener('change', () => form.requestSubmit()));
            search?.addEventListener('input', () => {
                window.clearTimeout(searchTimer);
                searchTimer = window.setTimeout(() => form.requestSubmit(), 350);
            });
        })();
    </script>
@endpush
