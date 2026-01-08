<x-app-layout>
    <x-slot:title>Arsip Surat</x-slot:title>
    <x-slot:breadcrumb>Detail</x-slot:breadcrumb>
    @if (session('error'))
        <x-alert>{{ session('error') }}</x-alert>
    @endif

    <div class="card">
        <div class="card-body">
            {{-- judul form --}}
            <x-form-title>
                <i class="ti ti-list fs-5 me-2"></i> Detail Data Arsip Surat
            </x-form-title>

            {{-- tampilkan detail data --}}
            <div class="table-responsive border rounded mb-4">
                <table class="table align-middle text-nowrap mb-0">
                    <tr>
                        <td width="150">Nama Surat</td>
                        <td width="10">:</td>
                        <td>{{ $arsip->nama_surat }}</td>
                    </tr>
                    <tr>
                        <td width="150">Nomor Surat</td>
                        <td width="10">:</td>
                        <td>{{ $arsip->nomor_surat }}</td>
                    </tr>
                    <tr>
                        <td width="150">Tanggal Surat</td>
                        <td width="10">:</td>
                        <td>{{ Carbon\Carbon::parse($arsip->tanggal_surat)->translatedFormat('j F Y') }}</td>
                    </tr>
                    @if($arsip->created_at->timestamp != $arsip->updated_at->timestamp)
                    <tr>
                        <td width="150">Tanggal Diubah</td>
                        <td width="10">:</td>
                        <td>{{ \Carbon\Carbon::parse($arsip->updated_at)->translatedFormat('j F Y H:i') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td width="150">Kategori</td>
                        <td width="10">:</td>
                        <td>{{ $arsip->kategori->nama }}</td>
                    </tr>
                    <tr>
                        <td width="150">Status</td>
                        <td width="10">:</td>
                        <td>
                            @if($arsip->created_at->timestamp == $arsip->updated_at->timestamp)
                                <button class="text-capitalize bg-success text-white px-4 py-1 rounded border-0">
                                    Asli
                                </button>
                            @else
                                <button class="text-capitalize bg-warning text-dark px-4 py-1 rounded border-0">
                                    Telah Diubah
                                </button>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td width="150">Status Keaslian</td>
                        <td width="10">:</td>
                        <td>
                            @if($arsip->isOriginal())
                                <button class="text-capitalize bg-success text-white px-4 py-1 rounded border-0">
                                    Asli
                                </button>
                            @else
                                <button class="text-capitalize bg-warning text-dark px-4 py-1 rounded border-0">
                                    Telah Diubah
                                </button>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            {{-- tampilkan dokumen elektronik --}}
            <div class="pt-2">
                <embed type="application/pdf" width="100%" height="700px"
                    src="{{ route('arsip.preview', $arsip->id) }}#toolbar=0" class="border rounded">
                <div class="mt-4">
                    <a href="{{ route('arsip.download', $arsip->id) }}" class="btn btn-primary">
                        <i class="ti ti-download me-1"></i> Download Dokumen
                    </a>
                </div>
            </div>

            {{-- action buttons --}}
            <x-form-action-buttons>arsip</x-form-action-buttons>
        </div>
    </div>
</x-app-layout>
