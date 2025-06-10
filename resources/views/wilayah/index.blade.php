@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'wilayah'
])

@section('title', 'Daftar Wilayah')

@section('content')

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col col-md-12"><h3>Daftar Wilayah</h3></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" style="float: left; width:40%;margin-right:80px;" id="tabelKabupaten">
                            <thead class=" text-primary">
                                <th>Kode Kabupaten</th>
                                <th>Kabupaten</th>
                            </thead>
                            <tbody>
                                @if(count($kabupaten) > 0)
                                    @foreach($kabupaten as $row)
                                        <tr>
                                            <td>{{ $row->kode_kabupaten }}</td>
                                            <td>{{ $row->kabupaten }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <table class="table"style="float: left; width:40%" id="tabelKecamatan">
                            <thead class=" text-primary">
                                <th>Kode Kecamatan</th>
                                <th>Kecamatan</th>
                                <th>Kabupaten</th>
                            </thead>
                            <tbody>
                                @if(count($kecamatan) > 0)
                                @foreach($kecamatan as $row)
                                    <tr>
                                        <td>{{ $row->kode_kecamatan }}</td>
                                        <td>{{ $row->kecamatan }}</td>
                                        <td>{{ $row->kabupaten }}</td>
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
    var tabelKabupaten = $('#tabelKabupaten').DataTable({

    })

    var tabelKecamatan = $('#tabelKecamatan').DataTable({

})

@endsection
