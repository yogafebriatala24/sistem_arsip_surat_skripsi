<x-app-layout>
    <x-slot:title>Arsip Surat</x-slot:title>
    <x-slot:breadcrumb>Ubah</x-slot:breadcrumb>

    <div class="card">
        <div class="card-body">
            {{-- judul form --}}
            <x-form-title>
                <i class="ti ti-edit fs-5 me-2"></i> Ubah Data Arsip Surat
            </x-form-title>
            
            {{-- form ubah data --}}
            <form action="{{ route('arsip.update', $arsip->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Surat <span class="text-danger">*</span></label>
                            <textarea name="nama_surat" rows="2" class="form-control @error('nama_surat') is-invalid @enderror" autocomplete="off">{{ old('nama_surat', $arsip->nama_surat) }}</textarea>
                            
                            {{-- pesan error untuk nama surat --}}
                            @error('nama_surat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_surat" class="form-control @error('nomor_surat') is-invalid @enderror" value="{{ old('nomor_surat', $arsip->nomor_surat) }}" autocomplete="off">
                            
                            {{-- pesan error untuk nomor surat --}}
                            @error('nomor_surat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                            <input type="text" name="tanggal_surat" class="form-control datepicker @error('tanggal_surat') is-invalid @enderror" value="{{ old('tanggal_surat', $arsip->tanggal_surat) }}" autocomplete="off">
                            
                            {{-- pesan error untuk tanggal surat --}}
                            @error('tanggal_surat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori Surat <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select select2-single @error('kategori') is-invalid @enderror" autocomplete="off">
                                <option disabled value="">- Pilih Kategori Surat -</option>
                                @foreach ($kategori as $data)
                                    <option {{ old('kategori', $arsip->kategori_id) == $data->id ? 'selected' : '' }} value="{{ $data->id }}">{{ $data->nama }}</option>
                                @endforeach
                            </select>
    
                            {{-- pesan error untuk kategori --}}
                            @error('kategori')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Dokumen Elektronik</label>
                            <input type="file" accept=".pdf" name="dokumen_elektronik" class="form-control @error('dokumen_elektronik') is-invalid @enderror" autocomplete="off">
                
                            {{-- pesan error untuk dokumen elektronik --}}
                            @error('dokumen_elektronik')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            
                            {{-- tampilkan file --}}
                            <div class="mt-2">
                                <a href="{{ route('arsip.show', $arsip->id) }}">
                                    <i class="ti ti-file-text icon-file text-warning fs-1"></i>
                                </a>
                            </div>

                            <div class="form-text text-primary mt-2 mb-0">
                                <div class="badge fw-medium bg-primary-subtle text-primary">Keterangan :</div>
                                <div>
                                    - Jenis file yang bisa diunggah adalah: pdf. <br>
                                    - Ukuran file yang bisa diunggah maksimal 5 MB.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
                {{-- action buttons --}}
                <x-form-action-buttons>arsip</x-form-action-buttons>
            </form>
        </div>
    </div>
</x-app-layout>