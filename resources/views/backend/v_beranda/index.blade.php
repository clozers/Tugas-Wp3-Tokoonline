@extends('backend.v_layouts.app')
@section('content')
    <!-- contentAwal -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body border-top">
                    <h5 class="card-title"> {{ $judul }}</h5>
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading"> Selamat Datang, {{ Auth::user()->nama }}</h4>
                        Aplikasi Toko Online dengan hak akses yang anda miliki sebagai
                        <b>
                            @if (Auth::user()->role == 1)
                                Super Admin
                            @elseif(Auth::user()->role == 0)
                                Admin
                            @endif
                        </b>
                        ini adalah halaman utama dari aplikasi Web Programming. Studi Kasus Toko Online.
                        <hr>
                        <p class="mb-0">Kuliah..? BSI Aja !!!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Site Analysis -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex align-items-center">
                        <div>
                            <h4 class="card-title">Site Analysis</h4>
                            <h5 class="card-subtitle">Overview of Latest Month</h5>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Grafik -->
                        <div class="col-lg-9">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-line-chart" style="height: 300px;"></div>
                            </div>
                        </div>
                        <!-- Statistik -->
                        <div class="col-lg-3">
                            <div class="row">
                                <div class="col-6">
                                    <div class="bg-dark p-10 text-white text-center">
                                        <i class="mdi mdi-account fs-3 mb-1 font-16"></i>
                                        <h5 class="mb-0 mt-1">{{ $totalcus }}</h5>
                                        <small class="font-light">Total Customer</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-dark p-10 text-white text-center">
                                        <i class="mdi mdi-plus fs-3 font-16"></i>
                                        <h5 class="mb-0 mt-1">{{ $newCustomer }}</h5>
                                        <small class="font-light">New Customer</small>
                                    </div>
                                </div>
                                <div class="col-6 mt-3">
                                    <div class="bg-dark p-10 text-white text-center">
                                        <i class="mdi mdi-tag fs-3 mb-1 font-16"></i>
                                        <h5 class="mb-0 mt-1">{{ $ttlpesananselesai }}</h5>
                                        <small class="font-light">Total Orders</small>
                                    </div>
                                </div>
                                <div class="col-6 mt-3">
                                    <div class="bg-dark p-10 text-white text-center">
                                        <i class="mdi mdi-table fs-3 mb-1 font-16"></i>
                                        <h5 class="mb-0 mt-1">{{ $ttlpesananproses }}</h5>
                                        <small class="font-light">Pending Orders</small>
                                    </div>
                                </div>
                                <div class="col-6 mt-3">
                                    <div class="bg-dark p-10 text-white text-center">
                                        <i class="mdi mdi-truck-delivery fs-3 mb-1 font-16"></i>
                                        <h5 class="mb-0 mt-1">{{ $ttlpesanankirim }}</h5>
                                        <small class="font-light">In Delivery</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- column -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (wajib untuk Flot) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Flot CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.tooltip.min.js"></script>

    <!-- Grafik dari data asli -->
    <script>
        $(function() {
            var data = [{
                label: "Jumlah Order per Bulan",
                data: {!! $dataChart !!},
                lines: {
                    show: true,
                    fill: true
                },
                points: {
                    show: true
                }
            }];

            var options = {
                grid: {
                    hoverable: true,
                    clickable: true
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%s : %y",
                    shifts: {
                        x: -60,
                        y: 25
                    }
                },
                xaxis: {
                    tickDecimals: 0,
                    tickFormatter: function(val) {
                        const bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt',
                            'Nov', 'Des'
                        ];
                        return bulan[val - 1];
                    }
                }
            };

            $.plot($("#flot-line-chart"), data, options);
        });
    </script>
    <!-- contentAkhir -->
@endsection
