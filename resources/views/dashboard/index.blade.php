<x-app-layout>
    <x-slot:title>Dashboard</x-slot:title>

    {{-- Heroes --}}
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center g-4">
                <div class="col-lg-3 col-xxl-2">
                    <img src="{{ asset('images/img-dashboard.svg') }}" class="img-fluid opacity-85" alt="images" loading="lazy">
                </div>
                <div class="col-lg-9 col-xxl-10">
                    <h5 class="text-primary mb-2">
                        Selamat datang <span class="fw-semibold">{{ auth()->user()->nama_user }}</span> di <span class="fw-semibold">{{ config('app.name') }}</span>!
                    </h5>
                    <p class="lead-dashboard mb-0">Aplikasi Pengelolaan Arsip Surat adalah aplikasi berbasis web yang digunakan untuk mengelola penyimpanan arsip surat dalam bentuk dokumen elektronik. Aplikasi arsip memberikan efisiensi dan keamanan penyimpanan arsip surat, serta mempermudah dalam mencari informasi arsip surat.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- menampilkan informasi jumlah data arsip surat --}}
        <div class="col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body ">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="ti ti-archive"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Arsip Surat</p>
                                {{-- tampilkan data --}}
                                <h4 class="card-title">{{ $totalArsip }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- menampilkan informasi jumlah data kategori surat --}}
        <div class="col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="ti ti-category"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Kategori Surat</p>
                                {{-- tampilkan data --}}
                                <h4 class="card-title">{{ $totalKategori }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- menampilkan informasi jumlah data user --}}
        <div class="col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="ti ti-user"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Pengguna Aplikasi</p>
                                {{-- tampilkan data --}}
                                <h4 class="card-title">{{ $totalUser }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- menampilkan grafik jumlah arsip surat per kategori surat --}}
    <div class="card">
        <div class="card-body">
            {{-- judul grafik --}}
            <x-form-title>
                <i class="ti ti-chart-bar fs-5 me-2"></i> Jumlah Arsip Surat Per Kategori Surat
            </x-form-title>
            {{-- tampilkan grafik --}}
            <canvas id="grafikArsip" height="80"></canvas>
        </div>
    </div>

    {{-- script grafik bar --}}
    <script>
        Chart.defaults.font.family = 'Nunito', 'sans-serif';
        Chart.defaults.font.size = 14;

        const ctx = document.getElementById('grafikArsip');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($kategori as $data)
                        '{{ $data->nama }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Jumlah Arsip',
                    maxBarThickness: 50,
                    borderRadius: 12,
                    data: [
                        @foreach ($kategori as $data)
                            {{ $data->arsip_count }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(99, 91, 255, 0.6)',
                    hoverBackgroundColor: 'rgba(99, 91, 255, 1)',
                    borderWidth: 1,
                    borderSkipped: false
                }]
            },
            options: {
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            tickBorderDash: [2]
                        },
                        border: {
                            display: false,
                            dash: [2]
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        titleMarginBottom: 10,
                        padding: 15,
                        boxPadding: 7,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                return label + ' : ' + context.parsed.y + ' Surat';
                            }
                        }
                    },
                }
            }
        });
    </script>
</x-app-layout>
