<x-app-layout>
    <x-slot:title>Kategori Surat</x-slot:title>
    <x-slot:breadcrumb>Tambah</x-slot:breadcrumb>

    <div class="card">
        <div class="card-body">
            {{-- judul form --}}
            <x-form-title>
                <i class="ti ti-pencil-plus fs-5 me-2"></i> Tambah Data Kategori Surat
            </x-form-title>
            
            {{-- form tambah data --}}
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <label class="form-label">Kategori Surat <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" autocomplete="off">
                        
                        {{-- pesan error untuk nama --}}
                        @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
        
                {{-- action buttons --}}
                <x-form-action-buttons>kategori</x-form-action-buttons>
            </form>
        </div>
    </div>
</x-app-layout>