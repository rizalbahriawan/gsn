@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'siswa'
])

@section('title', 'Daftar Siswa')

@section('content')




<div class="content">
    {{-- @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>
            <p>{{ $message }}</p>
        </strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif --}}

    {{-- notifikasi form validasi --}}

    {{-- notifikasi sukses --}}
    @if ($sukses = Session::get('sukses'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $sukses }}</strong>
    </div>
    @endif
    <!-- Import Excel -->
    <div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{ route('siswa.import') }}" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
                    </div>
                    <div class="modal-body">

                        {{ csrf_field() }}

                        <label>Pilih file excel</label>
                        <div>
                            <input type="file" name="file" required="required">
                        </div>
                        @if ($errors->has('file'))
                            <div class="text-danger">
                                <strong>{{ $errors->first('file') }}</strong>
                            </div>
                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Page --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Daftar Siswa</h3>
                    {{-- <div class="row">
                        <div class="col-lg-12">
                            <form method="post" action="{{ route('siswa.import') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <h4>Pilih file excel</h4>
                                <div>
                                    <input type="file" name="file2" required="required">
                                    <button type="submit" class="btn btn-primary">Import</button>
                                </div>
                            </form>
                        </div>
                    </div> --}}
                </div>
                <div class="card-body">
                    <div style="float: right; padding-bottom:20px;">
                        {{-- <button type="button" id='tambah-dealer' class="btn btn-primary" onclick="generateTable()">Cari</button> --}}
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importExcel">
                            Import Excel
                        </button>
                        <a href="{{ route('siswa.tambah') }}" class="btn btn-success">Tambah Siswa</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="tabelSiswa">
                            <thead class=" text-primary">
                                <th>Nama</th>
                                <th>No. Registrasi</th>
                                <th>Sekolah</th>
                                <th>Tempat Lahir</th>
                                <th>Jenis Kelamin</th>
                                <th>Tingkat Pendidikan</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>
                                @if(count($data) > 0)
                                    @foreach($data as $row)
                                        <tr>
                                            {{-- <td><img src="{{ asset('images/' . $row->student_image) }}" width="75" /></td> --}}
                                            <td>{{ $row->nama_lengkap }}</td>
                                            <td>{{ $row->nomor_registrasi_gsn }}</td>
                                            <td>{{ $row->alamat_sekolah }}</td>
                                            <td>{{ $row->tempat_lahir }}</td>
                                            <td>{{ $row->jenis_kelamin }}</td>
                                            <td>{{ $row->tingkat_pendidikan }}</td>
                                            <td>
                                                <form method="get" action="{{ route('siswa.edit', $row->id) }}">
                                                    {{-- @csrf --}}
                                                    <a href="{{ route('siswa.show') . '?id='.$row->id }}" class="btn btn-primary btn-sm" target="_blank">View</a>
                                                    <input type="submit" class="btn btn-warning btn-sm" value="Edit" />
                                                    <a href="{{ route('siswa.cetak_pdf', $row->id) }}" class="btn btn-info btn-sm" target="_blank">Cetak PDF</a>
                                                    {{-- <a href="{{ route('siswa.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}
                                                    {{-- <input type="submit" class="btn btn-danger btn-sm" value="Delete" /> --}}
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                {{-- @else
                                    <tr>
                                        <td colspan="7" class="text-center">No Data Found</td>
                                    </tr> --}}
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
    var table = $('#tabelSiswa').DataTable({
        order:[]
    })

</script>
@endsection
