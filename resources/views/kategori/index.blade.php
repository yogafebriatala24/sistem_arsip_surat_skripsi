<x-app-layout>
    <x-slot:title>Kategori Surat</x-slot:title>

    <div class="card">
        <div class="card-body">
            {{-- menampilkan pesan berhasil --}}
            <x-alert></x-alert>

            <div class="d-sm-flex flex-sm-row-reverse justify-content-between align-items-center mb-4">
                <div class="d-grid gap-2 mb-3 mb-sm-0">
                    {{-- button form tambah data --}}
                    <a href="{{ route('kategori.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-2"></i> Tambah Kategori
                    </a>
                </div>

                {{-- form pencarian --}}
                <form action="{{ route('kategori.index') }}" method="GET" class="position-relative">
                    <input type="text" name="search" class="form-control ps-5" value="{{ request('search') }}" placeholder="Cari Kategori Surat ..." autocomplete="off">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-5 ms-3"></i>
                </form>
            </div>

            {{-- tabel tampil data --}}
            <div class="table-responsive border rounded mb-4">
                <table class="table align-middle text-nowrap mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori Surat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($kategori as $data)
                        {{-- jika data ada, tampilkan data --}}
                        <tr>
                            <td width="100">{{ ++$i }}</td>
                            <td>{{ $data->nama }}</td>
                            <td width="200">
                                {{-- button form ubah data --}}
                                <a href="{{ route('kategori.edit', $data->id) }}" class="btn btn-primary btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Ubah">
                                    <i class="ti ti-edit"></i>
                                </a>
                                {{-- button modal hapus data --}}
                                <button type="button" class="btn btn-danger btn-sm m-1" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $data->id }}" data-bs-tooltip="tooltip" data-bs-title="Hapus"> 
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- Modal hapus data --}}
                        <div class="modal fade" id="modalHapus{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title" id="exampleModalLabel">
                                            <i class="ti ti-trash me-1"></i> Hapus Data Kategori Surat
                                        </h1>
                                    </div>
                                    <div class="modal-body">
                                        {{-- informasi data yang akan dihapus --}}
                                        <p class="mb-2">
                                            Anda yakin ingin menghapus data kategori <span class="fw-bold mb-2">{{ $data->nama }}</span>?
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                                        {{-- button hapus data --}}
                                        <form action="{{ route('kategori.destroy', $data->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"> Ya, Hapus! </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        {{-- jika data tidak ada, tampilkan pesan data tidak tersedia --}}
                        <tr>
                            <td colspan="3">
                                <div class="d-flex justify-content-center align-items-center">
                                    <i class="ti ti-info-circle fs-5 me-2"></i>
                                    <div>Tidak ada data tersedia.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{-- pagination --}}
            <div class="pagination-links">{{ $kategori->links() }}</div>
        </div>
    </div>
</x-app-layout>
