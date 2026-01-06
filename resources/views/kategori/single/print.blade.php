<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Kategori - {{ $kategori->kode ?? '' }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .meta { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { border: 1px solid #333; padding: 6px; text-align: left; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Daftar Item - Kategori</h2>
    </div>

    <div class="meta">
        <strong>Nama Kategori:</strong> {{ $kategori->nama ?? '-' }}<br>
        <strong>Kode Kategori:</strong> {{ $kategori->kode ?? '-' }}
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:15%">Kode</th>
                    <th style="width:65%">Nama</th>
                    <th style="width:20%">Harga Beli</th>
                </tr>
            </thead>
            <tbody>
                @if(count($items) > 0)
                    @foreach($items as $it)
                        <tr>
                            <td>{{ $it->kode }}</td>
                            <td>{{ $it->nama }}</td>
                            <td>{{ number_format($it->harga_beli,0,',','.') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" style="text-align:center">- Tidak ada item -</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dicetak: {{ $printed_at }}
    </div>

</body>
</html>
