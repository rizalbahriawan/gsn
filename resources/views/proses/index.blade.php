@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'proses'
])

@section('title', 'Daftar Proses')

@section('content')
<div class="content">
    {{-- <div class="alert alert-success alert-dismissible fade show" role="alert" id="success" style="display:none;">
        <strong>
            <p id="successText"></p>
        </strong>
        <button type="button" class="close" onclick="$('#success').hide();" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div> --}}
    @if (!empty($successMessage))
    <div class="alert alert-success alert-dismissible fade show mt-3" onshow="dismissed()" role="alert" id="success-alert">
        <strong><p>{{ $successMessage }}</p></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="failed" style="display:none;">
        <strong>
            <p id="failedText"></p>
            <p id="mainError"></p>
        </strong>
        <button type="button" class="close" onclick="$('#failed').hide();" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> Daftar Proses</h4>
                </div>
                <div class="card-body">
                    <div style="float: right;">
                        @if(auth()->user()->peran->kode_peran == 4)
                            {{-- <a href="{{ route('home') }}" class="btn btn-primary">Submit Bulk</a> --}}
                        @endif
                        @if(auth()->user()->peran->kode_peran == 3)

                            {{-- <a class="btn btn-success" id="approve_bulk" onclick="approveBulk()">Setujui Bulk</a>
                            <a class="btn btn-danger" id="reject_bulk" onclick="rejectBulk()">Tolak Bulk</a> --}}
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="tableProses">
                            <thead class=" text-primary">
                                <th width="15%">Nama Siswa</th>
                                <th width="10%">No. Registrasi</th>
                                <th>Aktivis</th>
                                <th>#1</th>
                                <th>#2</th>
                                <th>#3</th>
                                <th>#4</th>
                                <th>#5</th>
                                <th>#6</th>
                                <th>#7</th>
                                <th>#8</th>
                                <th>#9</th>
                                <th>#10</th>
                                <th>Total</th>
                                <th>Waktu Proses</th>
                                <th>Status</th>
                                {{-- <th>Cek</th> --}}
                                <th>Aksi</th>
                            </thead>
                            <tbody>
                                @if(count($data) > 0)
                                    @foreach($data as $row)
                                        <tr>
                                            <td>{{ $row->nama_siswa }}</td>
                                            <td>{{ $row->no_reg }}</td>
                                            <td>{{ $row->aktivis }}</td>
                                            <td>{{ $row->tahap_1 }}</td>
                                            <td>{{ $row->tahap_2 }}</td>
                                            <td>{{ $row->tahap_3 }}</td>
                                            <td>{{ $row->tahap_4 }}</td>
                                            <td>{{ $row->tahap_5 }}</td>
                                            <td>{{ $row->tahap_6 }}</td>
                                            <td>{{ $row->tahap_7 }}</td>
                                            <td>{{ $row->tahap_8 }}</td>
                                            <td>{{ $row->tahap_9 }}</td>
                                            <td>{{ $row->tahap_10 }}</td>
                                            <td>{{ $row->amount }}</td>
                                            <td>{{ $row->waktu_proses }}</td>
                                            <td>
                                                @if ($row->tahapan == 1)
                                                <button class="btn btn-default" style="cursor:default">{{ $row->status }}</button>
                                                @elseif($row->tahapan == 2)
                                                <button class="btn btn-info" style="cursor:default">{{ $row->status }}</button>
                                                @elseif($row->tahapan == 3)
                                                <button class="btn btn-danger" style="cursor:default">{{ $row->status }}</button>
                                                @elseif($row->tahapan == 4)
                                                <button class="btn btn-success" style="cursor:default">{{ $row->status }}</button>
                                                @endif

                                            </td>
                                            {{-- <td>
                                                <input type="checkbox" class="cekProses" name="cekProsesList[]" id= {{ $row->id}} value={{ $row->id}}/>
                                            </td> --}}
                                            <td>
                                                {{-- <form method="get" action="{{ route('proses.store', $row->id) }}">
                                                    @csrf --}}

                                                    @if(auth()->user()->peran->kode_peran == 4)
                                                        @if($row->tahapan == 1 || $row->tahapan == 3)
                                                        <a name="{{ $row->nama_siswa }}" id="{{ $row->id }}" onclick="editModal(this)" class="btn btn-warning">Edit</a>
                                                        {{-- <a href="{{ route('proses.submit', $row->id) }}" class="btn btn-primary btn-sm">Submit</a> --}}
                                                        <a name="submit" id="{{ $row->id }}" onclick="processRowAlert(this)" class="btn btn-primary">Submit</a>
                                                        @endif
                                                    @endif
                                                    @if(auth()->user()->peran->kode_peran == 3 && $row->tahapan == 2)
                                                        <a name="approve" id="{{ $row->id }}" onclick="processRowAlert(this)" class="btn btn-success">Setujui</a>
                                                        <a name="reject" id="{{ $row->id }}" onclick="processRowAlert(this)" class="btn btn-danger">Tolak</a>
                                                    @endif
                                                {{-- </form> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="confirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"></h2>
            </div>
            <div class="modal-body">
                <div class="col-lg-12 form-group ditolak">
                    <label for="alasan">Alasan Penolakan</label>
                    <input type="text" name="alasan" class="form-control input-field reset" id="alasan" aria-describedby="alasan">
                    <small class="text-danger error" id="err-alasan"></small>
                </div>
                <h5 class="text-center" style="margin:0;">Apakah Anda yakin ingin <span class="modal-subject"></span> proses ini?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary process-btn" onclick="processRow(this)">OK</button>
            </div>
        </div>
    </div>
</div>

<div id="editModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Edit Data <span class="edit-name"></span></h5>
            </div>
            <form id="editForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id" id="id">
                        <div class="col-md-3">
                            <div class="form-group">
                                <p>Tahap 1<span style="color:red">*</span></p>
                                <input type="text" name="tahap_1" id="tahap_1" class="form-control">
                                <small class="text-danger error" id="err-tahap_1"></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <p>Tahap 2<span style="color:red">*</span></p>
                                <input type="text" name="tahap_2" id="tahap_2" class="form-control">
                                <small class="text-danger error" id="err-tahap_2"></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <p>Tahap 3<span style="color:red">*</span></p>
                                <input type="text" name="tahap_3" id="tahap_3" class="form-control">
                                <small class="text-danger error" id="err-tahap_3"></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <p>Tahap 4<span style="color:red">*</span></p>
                                <input type="text" name="tahap_4" id="tahap_4" class="form-control">
                                <small class="text-danger error" id="err-tahap_4"></small>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <p>Tahap 5<span style="color:red">*</span></p>
                                <input type="text" name="tahap_5" id="tahap_5" class="form-control">
                                <small class="text-danger error" id="err-tahap_5"></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <p>Tahap 6<span style="color:red">*</span></p>
                                <input type="text" name="tahap_6" id="tahap_6" class="form-control">
                                <small class="text-danger error" id="err-tahap_6"></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <p>Tahap 7<span style="color:red">*</span></p>
                                <input type="text" name="tahap_7" id="tahap_7" class="form-control">
                                <small class="text-danger error" id="err-tahap7"></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <p>Tahap 8<span style="color:red">*</span></p>
                                <input type="text" name="tahap_8" id="tahap_8" class="form-control">
                                <small class="text-danger error" id="err-tahap_8"></small>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <p>Tahap 9<span style="color:red">*</span></p>
                                <input type="text" name="tahap_9" id="tahap_9" class="form-control">
                                <small class="text-danger error" id="err-tahap_9"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <p>Tahap 10<span style="color:red">*</span></p>
                                <input type="text" name="tahap_10" id="tahap_10" class="form-control">
                                <small class="text-danger error" id="err-tahap_10"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row ditolak-input">
                        <div class="col-md-12">
                            <div class="form-group">
                                <p>Alasan Penolakan</p>
                                <input readonly type="text" name="alasan_input" id="alasan_input" class="form-control">
                                <small class="text-danger error" id="err-alasan_input"></small>
                            </div>
                        </div>
                    </div>
                    {{-- <h5 class="text-center">Seragam</h5> --}}
                    {{-- <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <label>Seragam Nasional</label><br>
                                    <label class="form-check-label">
                                        <input class="form-check-input" id="seragam_nasional" name="seragam_nasional" type="checkbox">
                                        <span id = "seragam_nasional_text" class="form-check-sign">Belum terima</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <label>Seragam Putih Biru</label><br>
                                    <label class="form-check-label">
                                        <input class="form-check-input" id="seragam_putih_biru" name="seragam_putih_biru" type="checkbox">
                                        <span id = "seragam_putih_biru_text" class="form-check-sign">Belum terima</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <label>Seragam Putih Abu</label><br>
                                    <label class="form-check-label">
                                        <input class="form-check-input" id="seragam_putih_abu" name="seragam_putih_abu" type="checkbox">
                                        <span id = "seragam_putih_abu_text" class="form-check-sign">Belum terima</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <label>Seragam Pramuka</label><br>
                                    <label class="form-check-label">
                                        <input class="form-check-input" id="seragam_pramuka" name="seragam_pramuka" type="checkbox">
                                        <span id = "seragam_pramuka_text" class="form-check-sign">Belum terima</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-check">
                                    <label>Tas PAUD</label><br>
                                    <label class="form-check-label">
                                        <input class="form-check-input" id="tas_paud" name="tas_paud" type="checkbox">
                                        <span id = "tas_paud_text" class="form-check-sign">Belum terima</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-check">
                                    <label>Tas SD</label><br>
                                    <label class="form-check-label">
                                        <input class="form-check-input" id="tas_sd" name="tas_sd" type="checkbox">
                                        <span id = "tas_sd_text" class="form-check-sign">Belum terima</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-check">
                                    <label>Tas SMP</label><br>
                                    <label class="form-check-label">
                                        <input class="form-check-input" id="tas_smp" name="tas_smp" type="checkbox">
                                        <span id = "tas_smp_text" class="form-check-sign">Belum terima</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <label>Tas SMA/SMK</label><br>
                                    <label class="form-check-label">
                                        <input class="form-check-input" id="tas_sma_smk" name="tas_sma_smk" type="checkbox">
                                        <span id = "tas_sma_smk_text" class="form-check-sign">Belum terima</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <label>Tas Perguruan Tinggi</label><br>
                                    <label class="form-check-label">
                                        <input class="form-check-input" id="tas_perguruan_tinggi" name="tas_perguruan_tinggi" type="checkbox">
                                        <span id = "tas_perguruan_tinggi_text" class="form-check-sign">Belum terima</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h5 class="text-center">Buku & Pulpen</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <label>Buku</label><br>
                                    <label class="form-check-label">
                                        <input class="form-check-input" id="buku" name="buku" type="checkbox">
                                        <span id = "buku_text" class="form-check-sign">Belum terima</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <label>Pulpen</label><br>
                                    <label class="form-check-label">
                                        <input class="form-check-input" id="bulpen" name="bulpen" type="checkbox">
                                        <span id ="bulpen_text" class="form-check-sign">Belum terima</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
                    <button class="btn btn-primary edit-btn" type="submit">OK</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

<script>
    $(document).ready(function() {

        // $('#seragam_nasional').change(function() {
        //     onChangeSeragamNasional();
        // })
        // $('#seragam_putih_abu').change(function(){
        //     onChangeSeragamPutihAbu();
        // })
        // $('#seragam_putih_biru').change(function(){
        //     onChangeSeragamPutihBiru();
        // })
        // $('#seragam_pramuka').change(function(){
        //     onChangeSeragamPramuka();
        // })
        // $('#tas_paud').change(function(){
        //     onChangeTasPaud();
        // })
        // $('#tas_sd').change(function(){
        //     onChangeTasSd();
        // })
        // $('#tas_smp').change(function(){
        //     onChangeTasSmp();
        // })
        // $('#tas_sma_smk').change(function(){
        //     onChangeTasSmaSmk();
        // })
        // $('#tas_perguruan_tinggi').change(function(){
        //     onChangeTasPerguruanTinggi();
        // })
        // $('#buku').change(function(){
        //     onChangeBuku();
        // })
        // $('#bulpen').change(function(){
        //     onChangeBulpen();
        // })


    });

    function fillEditModal(res) {
        $('#id').val(res.data.id)
        $('#tahap_1').val(res.data.tahap_1)
        $('#tahap_2').val(res.data.tahap_2)
        $('#tahap_3').val(res.data.tahap_3)
        $('#tahap_4').val(res.data.tahap_4)
        $('#tahap_5').val(res.data.tahap_5)
        $('#tahap_6').val(res.data.tahap_6)
        $('#tahap_7').val(res.data.tahap_7)
        $('#tahap_8').val(res.data.tahap_8)
        $('#tahap_9').val(res.data.tahap_9)
        $('#tahap_10').val(res.data.tahap_10)
        if (res.data.tahapan == 3) {
            $('#alasan_input').val(res.data.alasan_tolak)
            $(".ditolak-input").show()
        } else {
            $(".ditolak-input").hide()
        }
        // if (res.data.seragam_nasional == 1) {
        //     $('#seragam_nasional').prop('checked', true);
        // } else {
        //     $('#seragam_nasional').prop('checked', false);
        // }
        // if (res.data.seragam_putih_abu == 1) {
        //     $('#seragam_putih_abu').prop('checked', true);
        // } else {
        //     $('#seragam_putih_abu').prop('checked', false);
        // }
        // if (res.data.seragam_putih_biru == 1) {
        //     $('#seragam_putih_biru').prop('checked', true);
        // } else {
        //     $('#seragam_putih_biru').prop('checked', false);
        // }
        // if (res.data.seragam_pramuka == 1) {
        //     $('#seragam_pramuka').prop('checked', true);
        // } else {
        //     $('#seragam_pramuka').prop('checked', false);
        // }
        // if (res.data.tas_paud == 1) {
        //     $('#tas_paud').prop('checked', true);
        // } else {
        //     $('#tas_paud').prop('checked', false);
        // }
        // if (res.data.tas_sd == 1) {
        //     $('#tas_sd').prop('checked', true);
        // } else {
        //     $('#tas_sd').prop('checked', false);
        // }
        // if (res.data.tas_smp == 1) {
        //     $('#tas_smp').prop('checked', true);
        // } else {
        //     $('#tas_smp').prop('checked', false);
        // }
        // if (res.data.tas_sma_smk == 1) {
        //     $('#tas_sma_smk').prop('checked', true);
        // } else {
        //     $('#tas_sma_smk').prop('checked', false);
        // }
        // if (res.data.tas_perguruan_tinggi == 1) {
        //     $('#tas_perguruan_tinggi').prop('checked', true);
        // } else {
        //     $('#tas_perguruan_tinggi').prop('checked', false);
        // }
        // if (res.data.buku == 1) {
        //     $('#buku').prop('checked', true);
        // } else {
        //     $('#buku').prop('checked', false);
        // }if (res.data.bulpen == 1) {
        //     $('#bulpen').prop('checked', true);
        // } else {
        //     $('#bulpen').prop('checked', false);
        // }
    }

    // function onChangeCheckbox() {
    //     onChangeSeragamNasional();
    //     onChangeSeragamPutihAbu();
    //     onChangeSeragamPutihBiru();
    //     onChangeSeragamPramuka();
    //     onChangeTasPaud();
    //     onChangeTasSd();
    //     onChangeTasSmp();
    //     onChangeTasSmaSmk();
    //     onChangeTasPerguruanTinggi();
    //     onChangeBuku();
    //     onChangeBulpen();
    // }

    // function onChangeSeragamNasional() {
    //     // $('#seragam_nasional').change(function() {
    //         if ($('#seragam_nasional').is(":checked")) {
    //             $('#seragam_nasional_text').html('Terima')
    //         } else {
    //             $('#seragam_nasional_text').html('Belum terima')
    //         }
    //     // });
    // }

    // function onChangeSeragamPutihAbu(){
    //     // $('#seragam_putih_abu').change(function(){
    //         if ($('#seragam_putih_abu').is(":checked")) {
    //             $('#seragam_putih_abu_text').html('Terima')
    //         } else {
    //             $('#seragam_putih_abu_text').html('Belum terima')
    //         }
    //     // })
    // }

    // function onChangeSeragamPutihBiru(){
    //     // $('#seragam_putih_biru').change(function(){
    //         if ($('#seragam_putih_biru').is(":checked")) {
    //             $('#seragam_putih_biru_text').html('Terima')
    //         } else {
    //             $('#seragam_putih_biru_text').html('Belum terima')
    //         }
    //     // })
    // }

    // function onChangeSeragamPramuka(){
    //     // $('#seragam_pramuka').change(function(){
    //         if ($('#seragam_pramuka').is(":checked")) {
    //             $('#seragam_pramuka_text').html('Terima')
    //         } else {
    //             $('#seragam_pramuka_text').html('Belum terima')
    //         }
    //     // })
    // }

    // function onChangeTasPaud(){
    //         // $('#tas_paud').change(function(){
    //         if ($('#tas_paud').is(":checked")) {
    //             $('#tas_paud_text').html('Terima')
    //         } else {
    //             $('#tas_paud_text').html('Belum terima')
    //         }
    //     // })
    // }

    // function onChangeTasSd(){
    //     // $('#tas_sd').change(function(){
    //         if ($('#tas_sd').is(":checked")) {
    //             $('#tas_sd_text').html('Terima')
    //         } else {
    //             $('#tas_sd_text').html('Belum terima')
    //         }
    //     // })
    // }

    // function onChangeTasSmp(){
    //     // $('#tas_smp').change(function(){
    //         if ($('#tas_smp').is(":checked")) {
    //             $('#tas_smp_text').html('Terima')
    //         } else {
    //             $('#tas_smp_text').html('Belum terima')
    //         }
    //     // })
    // }

    // function onChangeTasSmaSmk(){
    //     // $('#tas_sma_smk').change(function(){
    //         if ($('#tas_sma_smk').is(":checked")) {
    //             $('#tas_sma_smk_text').html('Terima')
    //         } else {
    //             $('#tas_sma_smk_text').html('Belum terima')
    //         }
    //     // })
    // }

    // function onChangeTasPerguruanTinggi(){
    //     // $('#tas_perguruan_tinggi').change(function(){
    //         if ($('#tas_perguruan_tinggi').is(":checked")) {
    //             $('#tas_perguruan_tinggi_text').html('Terima')
    //         } else {
    //             $('#tas_perguruan_tinggi_text').html('Belum terima')
    //         }
    //     // })
    // }

    // function onChangeBuku(){
    //     // $('#buku').change(function(){
    //         if ($('#buku').is(":checked")) {
    //             $('#buku_text').html('Terima')
    //         } else {
    //             $('#buku_text').html('Belum terima')
    //         }
    //     // })
    // }

    // function onChangeBulpen(){
    //     // $('#bulpen').change(function(){
    //         if ($('#bulpen').is(":checked")) {
    //             $('#bulpen_text').html('Terima')
    //         } else {
    //             $('#bulpen_text').html('Belum terima')
    //         }
    //     // })
    // }

    var process = "";
    var table = $('#tableProses').DataTable({
        order: []

    })



    function editModal(obj) {
        console.log(obj.name)
        var id = obj.id;
        obj.setAttribute('disabled', 'disabled');

        $.ajax({
            url: "{{ route('proses.edit') }}" + '?id=' + id,
            type: "GET",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'GET',
            },
            dataType: 'json',
            success: function (res) {
                if(res.success) {
                    fillEditModal(res);
                    // onChangeCheckbox();
                    $(".edit-name").html(obj.name);
                    $(".edit-btn").attr("id", id);
                    $('#editModal').modal('show');
                    console.log(JSON.stringify(res.data));
                } else {
                    $(".edit-name").html("");
                    $(".edit-btn").attr("id", "");
                    $('#editModal').modal('hide');
                }
                obj.removeAttribute('disabled');
            },
            error: function (res) {
                $(".edit-name").html("");
                $(".edit-btn").attr("id", "");
                $('#editModal').modal('hide');
                obj.removeAttribute('disabled');
            }
        });
    }

    $('.edit-btn').click(function (e) {
        $('#editForm').submit(function (event) {
            // console.log("id "+editBtn.id)
            event.preventDefault()
            event.stopImmediatePropagation();
            $('#mainError').html("")
            $('.edit-btn').attr('disabled', 'disabled');
            var formData = new FormData($(this)[0]);
            // formData.append('id',editBtn.id);
            console.log(JSON.stringify(formData))
            $('.error').each(function (i, obj) {
                obj.innerHTML = ''
            });
            $('#success').hide();
            $('#failed').hide();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('proses.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('.error').each(function (i, obj) {
                        obj.innerHTML = ''
                    });
                    if(data.success) {
                        console.log('store success '+data.nama_siswa )
                        window.location.href = "{{ route('proses.index') }}" + '?message=' + data.message + ' a.n siswa: ' + data.nama_siswa
                    } else {
                        $('.error').each(function (i, obj) {
                            if (data.message[obj.id.substring(4)]) {
                                obj.innerHTML = data.message[obj.id.substring(4)].filter(Boolean).join(", ")
                            }
                        });
                    }
                    // table.ajax.reload();
                    $('.edit-btn').removeAttr('disabled');
                },
                error: function (data) {
                    $('#failedText').html(JSON.stringify(data));
                    $('#failed').show();
                    $('.edit-btn').removeAttr('disabled');
                }
            });
        })

    })

    function processRowAlert(obj) {
        process = obj.name
        console.log(process)
        var id = obj.id;
        $(".process-btn").attr("id", id);
        $('.modal-title').html($(obj).text() + ' Proses');
        $('.modal-subject').html(obj.name);
        $('#confirmModal').modal('show');
        if (process == 'reject') {
            $(".ditolak").show()
        } else {
            $(".ditolak").hide()
        }
    }

    function processRow(processBtn){
        console.log(processBtn.id)
        console.log($('#alasan').val())


        processBtn.setAttribute('disabled', 'disabled');
        $('#success').hide();
        $('#failed').hide();

        if(processBtn.id != '' && process != '') {

            if (process == 'submit') {
                $.ajax({
                    url: "{{ route('proses.submit') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: processBtn.id
                    },
                    dataType: 'json',
                    success: function (data) {
                        if(data.success) {
                            // $('#successText').html(data.message);
                            // $('#success').show();
                            // $(".process-btn").attr("id", "");
                            // $('#confirmModal').modal('hide');
                            window.location.href = "{{ route('proses.index') }}" + '?message=' + data.message + ' a.n siswa: ' + data.nama_siswa
                        // } else {
                        //     $('#failedText').html(data.message);
                        //     $('#failed').show();
                        //     $(".process-btn").attr("id", "");
                        //     $('#confirmModal').modal('hide');
                        }
                        // table.ajax.reload();
                        processBtn.removeAttribute('disabled');
                    },
                    error: function (data) {
                        $('#failedText').modal(data.responseText);
                        $('#failed').show();
                        $(".process-btn").attr("id", "");
                        $('#confirmModal').modal('hide');
                        processBtn.removeAttribute('disabled');
                    }
                });
            } else if (process == 'approve') {
                $.ajax({
                    url: "{{ route('proses.approve') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: processBtn.id
                    },
                    dataType: 'json',
                    success: function (data) {
                        if(data.success) {
                            // $('#successText').html(data.message);
                            // $('#success').show();
                            // $(".process-btn").attr("id", "");
                            // $('#confirmModal').modal('hide');
                            window.location.href = "{{ route('proses.index') }}" + '?message=' + data.message + ' a.n siswa: ' + data.nama_siswa
                        // } else {
                        //     $('#failedText').html(data.message);
                        //     $('#failed').show();
                        //     $(".process-btn").attr("id", "");
                        //     $('#confirmModal').modal('hide');
                        }
                        // table.ajax.reload();
                        processBtn.removeAttribute('disabled');
                    },
                    error: function (data) {
                        $('#failedText').modal(data.responseText);
                        $('#failed').show();
                        $(".process-btn").attr("id", "");
                        $('#confirmModal').modal('hide');
                        processBtn.removeAttribute('disabled');
                    }
                });
            } else {
                $.ajax({
                    url: "{{ route('proses.reject') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: processBtn.id,
                        alasan: $('#alasan').val()
                    },
                    dataType: 'json',
                    success: function (data) {
                        if(data.success) {
                        //     $('#successText').html(data.message);
                        //     $('#success').show();
                        //     $(".process-btn").attr("id", "");
                        //     $('#confirmModal').modal('hide');
                        window.location.href = "{{ route('proses.index') }}" + '?message=' + data.message + ' a.n siswa: ' + data.nama_siswa
                        // } else {
                        //     $('#failedText').html(data.message);
                        //     $('#failed').show();
                        //     $(".process-btn").attr("id", "");
                        //     $('#confirmModal').modal('hide');
                        }
                        // table.ajax.reload();
                        processBtn.removeAttribute('disabled');
                    },
                    error: function (data) {
                        $('#failedText').modal(data.responseText);
                        $('#failed').show();
                        $(".process-btn").attr("id", "");
                        $('#confirmModal').modal('hide');
                        processBtn.removeAttribute('disabled');
                    }
                });
            }

        }
    };
</script>


@endsection
