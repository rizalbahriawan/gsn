@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'rekap'
])

@section('title', 'Daftar Rekap')

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
    {{-- @if (!empty($successMessage))
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
    </div> --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Rekap</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tableRekap">
                            <thead class=" text-primary">
                                <th width="15%">Nama Siswa</th>
                                <th>Aktivis</th>
                                <th>Tahap 1</th>
                                <th>Tahap 2</th>
                                <th>Tahap 3</th>
                                <th>Tahap 4</th>
                                <th>Tahap 5</th>
                                <th>Tahap 6</th>
                                <th>Tahap 7</th>
                                <th>Tahap 8</th>
                                <th>Tahap 9</th>
                                <th>Tahap 10</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Waktu Proses</th>
                            </thead>
                            <tbody>
                                @if(count($data) > 0)
                                    @foreach($data as $row)
                                        <tr>
                                            <td>{{ $row->nama_siswa }}</td>
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
                                            <td>{{ $row->status }}</td>
                                            <td>{{ $row->waktu_proses }}</td>
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
<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

<script>
    var table = $('#tableRekap').DataTable({

})
</script>


@endsection
