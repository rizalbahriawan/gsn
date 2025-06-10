@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'peran'
])

@section('title', 'Daftar Peran')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> Daftar Peran</h4>
                    {{-- <p>{{$elementActive}}</p> --}}
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class=" text-primary">
                                <th width="5%">Kode</th>
                                <th>Peran</th>
                                <th>Deskripsi</th>
                            </thead>
                            <tbody>
                                @if(count($data) > 0)
                                    @foreach($data as $row)
                                        <tr>
                                            <td>{{ $row->kode_peran }}</td>
                                            <td>{{ $row->nama_peran }}</td>
                                            <td>{{ $row->deskripsi }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center">No Data Found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-12">
            <div class="card card-plain">
                <div class="card-header">
                    <h4 class="card-title"> Daftar Peran</h4>
                    <p class="card-category"> Here is a subtitle for this table</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class=" text-primary">
                                <th>Nomor</th>
                                <th>Peran</th>
                                <th>Deskripsi</th>
                            </thead>
                            <tbody>
                                @if(count($data) > 0)
                                    <?php $i=1 ?>
                                    @foreach($data as $row)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $row->nama_peran }}</td>
                                            <td>{{ $row->deskripsi }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center">No Data Found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>



@endsection
