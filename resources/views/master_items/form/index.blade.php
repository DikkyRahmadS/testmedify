@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{url('master-items')}}" class="btn btn-secondary">Kembali ke Daftar Item</a>
            </div>
            <div class="card">

                @if($method == 'new')
                <div class="card-header">Buat Master Item Baru</div>
                @else
                <div class="card-header">Edit Master Item</div>
                @endif

                <div class="card-body">
                    {{-- Select2 CSS (loaded here for the form) --}}
                    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                    @include('master_items.form.form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // initialize select2 for kategori select
            var el = document.querySelectorAll('.select2-kategori');
            if (el && el.length) {
                el.forEach(function(node){
                    $(node).select2({
                        placeholder: 'Pilih kategori',
                        allowClear: true,
                        width: '100%'
                    });
                });
            }
        });
    </script>
@endsection