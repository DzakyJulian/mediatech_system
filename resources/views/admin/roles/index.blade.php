@extends('admin.layouts.layout')
@section('title', 'Manajemen Role')
@section('page_title', 'Manajemen Role')
@section('page_breadcrumb', 'Administration / Manajemen Role')
@section('content')
    <div class="mb-6">
        <div class="mb-[7px] text-[10px] font-extrabold uppercase tracking-[1.3px] text-[#0d21a1]">Administration</div>
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <h1 class="text-[25px] font-extrabold tracking-[-0.8px] text-[#171717]">Manajemen Role</h1>
            @can('role_management.create')
                <a href="{{ route('admin.roles.create') }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-[#0d21a1] px-[14px] py-[10px] text-[12px] font-bold text-white transition hover:bg-[#091772]"><svg
                        class="h-4 w-4 fill-none stroke-current" aria-hidden="true">
                        <use href="#i-plus"></use>
                    </svg>Tambah role</a>
            @endcan
        </div>
        <p class="mt-1 max-w-2xl text-[12px] leading-relaxed text-[#737373]">Atur role dan permission berdasarkan modul
            serta operasi. Role bawaan sistem tidak dapat dihapus.</p>
    </div>
    <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
        <div class="rounded-xl border border-[#e8eaf0] bg-white p-4">
            <p class="text-[10px] font-semibold text-[#737373]">Role aktif</p>
            <p class="mt-2 text-2xl font-extrabold text-[#171717]">{{ $activeRolesCount }}</p>
        </div>
        <div class="rounded-xl border border-[#e8eaf0] bg-white p-4">
            <p class="text-[10px] font-semibold text-[#737373]">Role bawaan</p>
            <p class="mt-2 text-2xl font-extrabold text-[#171717]">{{ $systemRolesCount }}</p>
        </div>
        <div class="rounded-xl border border-[#e8eaf0] bg-white p-4">
            <p class="text-[10px] font-semibold text-[#737373]">Role tambahan</p>
            <p class="mt-2 text-2xl font-extrabold text-[#171717]">{{ max(0, $activeRolesCount - $systemRolesCount) }}</p>
        </div>
    </div>
    <section class="overflow-hidden rounded-xl border border-[#e8eaf0] bg-white">
        <div class="border-b border-[#f0f1f5] px-4 py-4">
            <h2 class="text-[13px] font-extrabold text-[#262626]">Daftar role</h2>
            <p class="mt-1 text-[10px] text-[#737373]">Permission yang diberikan akan langsung digunakan saat pemeriksaan
                akses berikutnya.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] border-collapse text-left">
                <thead class="bg-[#fafafa]">
                    <tr>
                        <th class="px-4 py-3 text-[10px] font-extrabold uppercase tracking-wide text-[#737373]">Role</th>
                        <th class="px-4 py-3 text-[10px] font-extrabold uppercase tracking-wide text-[#737373]">Jenis</th>
                        <th class="px-4 py-3 text-[10px] font-extrabold uppercase tracking-wide text-[#737373]">Pengguna
                        </th>
                        <th class="px-4 py-3 text-[10px] font-extrabold uppercase tracking-wide text-[#737373]">Permission
                        </th>
                        <th class="px-4 py-3 text-[10px] font-extrabold uppercase tracking-wide text-[#737373]">Status</th>
                        <th class="px-4 py-3 text-[10px] font-extrabold uppercase tracking-wide text-[#737373]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr class="border-t border-[#f0f1f5] hover:bg-[#fcfcfe]">
                            <td class="px-4 py-3">
                                <p class="text-[12px] font-bold text-[#262626]">{{ $role->role_name }}</p>
                                <p class="mt-1 max-w-[280px] text-[10px] leading-relaxed text-[#737373]">
                                    {{ $role->description }}</p>
                            </td>
                            <td class="px-4 py-3">
                                @if ($role->is_system_role)
                                    <span
                                    class="rounded-md bg-[#f1f2f5] px-2 py-1 text-[10px] font-bold text-[#525252]">Bawaan</span>@else<span
                                        class="rounded-md bg-[#e7e9f6] px-2 py-1 text-[10px] font-bold text-[#0d21a1]">Kustom</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-[11px] text-[#525252]">{{ $role->users_count }}</td>
                            <td class="px-4 py-3 text-[11px] text-[#525252]">
                                {{ $role->is_super_admin ? 'Akses penuh' : $role->permissions_count . ' izin' }}</td>
                            <td class="px-4 py-3">
                                @if ($role->is_active && !$role->deleted_at)
                                    <span
                                    class="rounded-md bg-[#eaf7f0] px-2 py-1 text-[10px] font-bold text-[#16805d]">Aktif</span>@else<span
                                        class="rounded-md bg-[#fff0ef] px-2 py-1 text-[10px] font-bold text-[#b54740]">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @can('role_management.update')
                                        <a href="{{ route('admin.roles.edit', $role->role_id) }}"
                                            class="text-[11px] font-bold text-[#0d21a1] hover:underline">Atur</a>
                                        @endcan @if (!$role->is_system_role && !$role->is_super_admin)
                                            @can('role_management.delete')
                                                <details class="relative">
                                                    <summary
                                                        class="cursor-pointer list-none text-[11px] font-bold text-[#b54740] hover:underline">
                                                        Nonaktifkan</summary>
                                                    <div
                                                        class="absolute right-0 z-20 mt-2 w-[min(88vw,340px)] rounded-xl border border-[#e8eaf0] bg-white p-4 shadow-xl">
                                                        <form method="POST"
                                                            action="{{ route('admin.roles.destroy', $role->role_id) }}">@csrf
                                                            @method('DELETE')<p class="text-[12px] font-extrabold text-[#171717]">
                                                                Nonaktifkan role {{ $role->role_name }}?</p>
                                                            <p class="mt-1 text-[10px] leading-relaxed text-[#737373]">Role hanya
                                                                dapat dinonaktifkan jika tidak sedang digunakan pengguna.</p><label
                                                                class="mt-3 block text-[10px] font-bold text-[#404040]">Alasan</label>
                                                            <textarea name="deletion_reason" required minlength="5" maxlength="500"
                                                                class="mt-1 min-h-16 w-full rounded-lg border border-[#dedfe6] p-2 text-[11px] outline-none focus:border-[#5d6ac0]"
                                                                placeholder="Jelaskan alasan penonaktifan"></textarea>
                                                            <div class="mt-3 flex justify-end"><button
                                                                    class="rounded-lg bg-[#b54740] px-3 py-2 text-[10px] font-bold text-white">Konfirmasi</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </details>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                        </tr>@empty<tr>
                                <td colspan="6" class="px-4 py-10 text-center text-[12px] text-[#737373]">Belum ada role.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-[#f0f1f5] px-4 py-3 text-[11px]">{{ $roles->links() }}</div>
        </section>
        <div class="mt-4 rounded-lg border border-[#e7e9f6] bg-[#f5f6fb] px-4 py-3 text-[10px] leading-relaxed text-[#525252]">
            Role Super Admin memiliki akses penuh melalui Gate khusus. Role sistem seperti Sales, Manager, Warehouse, Finance,
            Finance Manager/Director, dan Admin CMS tidak dapat dihapus, tetapi permission-nya dapat disesuaikan sesuai
            keputusan perusahaan.</div>
    @endsection
