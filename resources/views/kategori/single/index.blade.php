@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{ url('/kategori') }}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
            </div>
            <div class="card">
                <div class="card-header">Kategori: {{ $kategori->nama ?? '-' }}</div>

                <div class="card-body">
                    <table>
                        <tr>
                            <th>Kode</th>
                            <td>:</td>
                            <td>{{ $kategori->kode ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>:</td>
                            <td>{{ $kategori->nama ?? '-' }}</td>
                        </tr>
                    </table>

                    <h5 class="mt-3">Daftar Item pada Kategori ini</h5>
                    @if(count($items) > 0)
                        <ul>
                        @foreach($items as $it)
                            <li><a href="{{ url('/master-items/view/' . $it->kode) }}">{{ $it->nama }} ({{ $it->kode }})</a></li>
                        @endforeach
                        </ul>
                    @else
                        <p>- Tidak ada item untuk kategori ini -</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
