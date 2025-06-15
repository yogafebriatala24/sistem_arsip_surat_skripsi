<div class="sidebar sidebar-style-2" data-background-color="white"">
    {{-- Sidebar Logo --}}
    <div class="sidebar-logo">
        {{-- Logo Header --}}
        <x-logo-header></x-logo-header>
    </div>
    
    {{-- Sidebar Menu --}}
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-primary">
                <li class="nav-item">
                    <x-sidebar-link href="{{ route('dashboard.index') }}" :active="request()->routeIs('dashboard.index')">
                        <i class="ti ti-layout-dashboard"></i>
                        <p>Dashboard</p>
                    </x-sidebar-link>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="ti ti-dots fs-5"></i>
                    </span>
                    <h4 class="text-section">Surat</h4>
                </li>
                <li class="nav-item">
                    <x-sidebar-link href="{{ route('arsip.index') }}" :active="request()->routeIs('arsip.*')">
                        <i class="ti ti-archive"></i>
                        <p>Arsip Surat</p>
                    </x-sidebar-link>
                </li>
                <li class="nav-item">
                    <x-sidebar-link href="{{ route('kategori.index') }}" :active="request()->routeIs('kategori.*')">
                        <i class="ti ti-category"></i>
                        <p>Kategori Surat</p>
                    </x-sidebar-link>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="ti ti-dots fs-5"></i>
                    </span>
                    <h4 class="text-section">Laporan</h4>
                </li>
                <li class="nav-item">
                    <x-sidebar-link href="{{ route('laporan.index') }}" :active="request()->routeIs('laporan.*')">
                        <i class="ti ti-file-text"></i>
                        <p>Laporan</p>
                    </x-sidebar-link>
                </li>

                {{-- tampilkan menu pengaturan jika role = Admin --}}
                @if (auth()->user()->role === 'Admin')
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="ti ti-dots fs-5"></i>
                        </span>
                        <h4 class="text-section">Pengaturan</h4>
                    </li>
                    <li class="nav-item">
                        <x-sidebar-link href="{{ route('user.index') }}" :active="request()->routeIs('user.*')">
                            <i class="ti ti-user"></i>
                            <p>Manajemen User</p>
                        </x-sidebar-link>
                    </li>
                @endif


               
              
            </ul>
        </div>
    </div>
</div>