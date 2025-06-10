@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'user'
])

@section('title', 'Daftar Pengguna')

@section('content')

<div class="content">
    @if ($sukses = Session::get('sukses'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $sukses }}</strong>
    </div>
    @endif

    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successResetPassword" style="display:none;">
        <strong>
            <p id="successResetPasswordText"></p>
        </strong>
        <button type="button" class="close" onclick="$('#successResetPassword').hide();" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="failedResetPassword" style="display:none;">
        <strong>
            <p id="failedResetPasswordText"></p>
        </strong>
        <button type="button" class="close" onclick="$('#failedResetPassword').hide();" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> Daftar Pengguna</h4>
                    {{-- <p>{{ $elementActive }}</p> --}}
                </div>
                <div class="card-body">
                    <div style="float: right; padding-bottom:20px;">
                        <a href="{{ route('user.tambah') }}" class="btn btn-success">Tambah Pengguna</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="tabelUser">
                            <thead class=" text-primary">
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Peran</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>
                                @if(count($data) > 0)
                                    @foreach($data as $row)
                                        <tr>
                                            <td>{{ $row->username }}</td>
                                            <td>{{ $row->name }}</td>
                                            <td>{{ $row->email }}</td>
                                            <td>{{ $row->peran }}</td>
                                            <td>
                                                <form method="post" action="{{ route('user.delete', $row->id) }}">
                                                    @csrf
                                                    <a href="{{ route('user.show') . '?id='.$row->id }}" class="btn btn-primary btn-sm">View</a>
                                                    <a href="{{ route('user.edit') . '?id='.$row->id }}" class="btn btn-warning btn-sm">Edit</a>
                                                    @if(auth()->user()->peran->kode_peran == 2)
                                                        <a class="btn btn-danger btn-sm" name={{ $row->username }} id="reset_{{ $row->id }}" onclick="resetPasswordAlert(this)">Reset Password</a>
                                                        {{-- <a href="{{ route('user.resetPassword', $row->id) }}" class="btn btn-info btn-sm">Reset Password</a> --}}
                                                        {{-- <a id = {{ $row->username}} class="btn btn-danger btn-sm tesmodal">Tes Modal</a> --}}
                                                    @endif
                                                    {{-- <input type="submit" class="btn btn-danger btn-sm" value="Delete" /> --}}
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">No Data Found</td>
                                    </tr>
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
                <h2 class="modal-title">Reset Password</h2>
            </div>
            <div class="modal-body">
                <h5 class="text-center" style="margin:0;">Apakah Anda yakin reset password untuk pengguna <b><span id="username-modal"></span></b>?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary reset-btn" onclick="resetPassword(this)">OK</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

<script>
    var table = $('#tabelUser').DataTable({

    })

    $('.tesmodal').click(function(){
        alert(this.id);
    })

    function resetPasswordAlert(obj) {
        console.log(obj.name)
        var id = obj.id.substring(6);
        $(".reset-btn").attr("id", id);
        $('#username-modal').html(obj.name);
        $('#confirmModal').modal('show');
    }

    function resetPassword(resetBtn){
        console.log(resetBtn.id)
        resetBtn.setAttribute('disabled', 'disabled');
        $('#successResetPassword').hide();
        $('#failedResetPassword').hide();

        if(resetBtn.id != '') {
            $.ajax({
                url: "{{ route('user.resetPassword') }}" + '?id=' + resetBtn.id,
                type: "GET",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'GET',
                },
                dataType: 'json',
                success: function (data) {
                    if(data.success) {
                        $('#successResetPasswordText').html(data.message);
                        $('#successResetPassword').show();
                        $(".reset-btn").attr("id", "");
                        $('#confirmModal').modal('hide');
                    } else {
                        $('#failedResetPasswordText').html(data.message);
                        $('#failedResetPassword').show();
                        $(".reset-btn").attr("id", "");
                        $('#confirmModal').modal('hide');
                    }
                    resetBtn.removeAttribute('disabled');
                },
                error: function (data) {
                    $('#failedResetPasswordText').modal(data.responseText);
                    $('#failedResetPassword').show();
                    $(".reset-btn").attr("id", "");
                    $('#confirmModal').modal('hide');
                    resetBtn.removeAttribute('disabled');
                }
            });
        }
    };
</script>


@endsection
