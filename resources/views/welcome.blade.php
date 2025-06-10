@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard'
])

@section('content')
    <div class="content">
        @if(auth()->user()->peran->kode_peran <= 4)
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-globe text-warning"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Jumlah Siswa</p>
                                    <p id="jumlah_siswa" class="card-title">42000
                                        <p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        {{-- <div class="stats">
                            <i class="fa fa-refresh"></i> Update Now
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-money-coins text-success"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Total Simpanan</p>
                                    <p id="total_proses" class="card-title">Rp 10.000.000
                                        <p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        {{-- <div class="stats">
                            <i class="fa fa-calendar-o"></i> Last day
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-vector text-danger"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Jumlah Forum</p>
                                    <p id="jumlah_forum" class="card-title">23
                                        <p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        {{-- <div class="stats">
                            <i class="fa fa-clock-o"></i> In the last hour
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-favourite-28 text-primary"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Jumlah Keluarga yang terbantu</p>
                                    <p id="jumlah_keluarga" class="card-title">7300
                                        <p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        {{-- <div class="stats">
                            <i class="fa fa-refresh"></i> Update now
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Grafik Jumlah Siswa</h5>
                        <p class="card-category">1 Tahun</p>
                    </div>
                    <div class="card-body ">
                        <canvas id=chartHours width="400" height="100"></canvas>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-history"></i> Updated 3 minutes ago
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Grafik Jumlah Siswa</h5>
                        <p class="card-category">Berdasarkan Kabupaten</p>
                    </div>
                    <div class="card-body ">
                        <canvas id="chartEmail"></canvas>
                    </div>
                    <div class="card-footer ">
                        <div class="legend">
                            <i class="fa fa-circle text-primary"></i> Nagekeo
                            <i class="fa fa-circle text-warning"></i> Rote Ndao
                            <i class="fa fa-circle text-danger"></i> Belu
                            <i class="fa fa-circle text-gray"></i> Malaka
                        </div>
                        <hr>
                        {{-- <div class="stats">
                            <i class="fa fa-calendar"></i> Number of emails sent
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-chart">
                    <div class="card-header">
                        <h5 class="card-title">Grafik Siswa</h5>
                        <p class="card-category">per Jenis Kelamin</p>
                    </div>
                    <div class="card-body">
                        <canvas id="speedChart" width="400" height="100"></canvas>
                    </div>
                    <div class="card-footer">
                        <div class="chart-legend">
                            <i class="fa fa-circle text-info"></i> Laki-laki
                            <i class="fa fa-circle text-warning"></i> Perempuan
                        </div>
                        <hr />
                        <div class="card-stats">
                            <i class="fa fa-check"></i> Data information certified
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        @endif
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                url: "{{ route('getDashboard') }}",
                type: "GET",
                contentType: false,
                processData: false,
                success: function(data) {
                    // alert('suces ajax');
                    console.log(JSON.stringify(data));
                    $('#jumlah_siswa').html(data.jumlah_siswa)
                    $('#total_proses').html(data.total_proses)
                    $('#jumlah_keluarga').html(data.jumlah_keluarga)
                    $('#jumlah_forum').html(data.jumlah_forum)
                    $('#hash').html(data.hash)
                },
                error: function(data) {
                    // alert('error ajax')
                    alert(JSON.stringify(data));
                    console.log(JSON.stringify(data));
                }
            });


            // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
            demo.initChartsPages();


        });
    </script>

@endpush
