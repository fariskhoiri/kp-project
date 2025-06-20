@extends('layouts.master')

@section('title')
    Edit Profil
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Edit Profil</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <form action="{{ route('user.update_profil') }}" method="post" class="form-profil" data-toggle="validator" enctype="multipart/form-data">
                @csrf
                <div class="box-body">
                    {{-- Menampilkan notifikasi sukses --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <i class="fa fa-check icon"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Menampilkan error validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group row">
                        <label for="name" class="col-lg-2 control-label">Nama</label>
                        <div class="col-lg-6">
                            <input type="text" name="name" class="form-control" id="name" required autofocus value="{{ $profil->name }}">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="foto" class="col-lg-2 control-label">Foto Profil</label>
                        <div class="col-lg-4">
                            <input type="file" name="foto" class="form-control" id="foto"
                                onchange="preview('.tampil-foto', this.files[0])">
                            <span class="help-block with-errors"></span>
                            <br>
                            <div class="tampil-foto">
                                {{-- Gunakan helper asset() untuk menampilkan gambar --}}
                                <img src="{{ asset($profil->foto ?? '/AdminLTE-2/dist/img/user2-160x160.jpg') }}" width="200">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-lg-2 control-label">Password</label>
                        <div class="col-lg-6">
                            <input type="password" name="password" class="form-control" id="password" 
                                placeholder="Kosongkan jika tidak ingin mengubah password">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password_confirmation" class="col-lg-2 control-label">Konfirmasi Password</label>
                        <div class="col-lg-6">
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"
                                data-match="#password"
                                placeholder="Kosongkan jika tidak ingin mengubah password">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                </div>
                <div class="box-footer text-right">
                    <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#old_password').on('keyup', function () {
            if ($(this).val() != "") $('#password, #password_confirmation').attr('required', true);
            else $('#password, #password_confirmation').attr('required', false);
        });

        $('.form-profil').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.ajax({
                    url: $('.form-profil').attr('action'),
                    type: $('.form-profil').attr('method'),
                    data: new FormData($('.form-profil')[0]),
                    async: false,
                    processData: false,
                    contentType: false
                })
                .done(response => {
                    $('[name=name]').val(response.name);
                    $('.tampil-foto').html(`<img src="{{ url('/') }}${response.foto}" width="200">`);
                    $('.img-profil').attr('src', `{{ url('/') }}/${response.foto}`);

                    $('.alert').fadeIn();
                    setTimeout(() => {
                        $('.alert').fadeOut();
                    }, 3000);
                })
                .fail(errors => {
                    if (errors.status == 422) {
                        alert(errors.responseJSON); 
                    } else {
                        alert('Tidak dapat menyimpan data');
                    }
                    return;
                });
            }
        });
    });
</script>
@endpush