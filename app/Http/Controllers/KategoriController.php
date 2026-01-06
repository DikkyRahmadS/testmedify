<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KategoriController extends Controller
{
    public function index()
    {
        return view('kategori.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        // Use Eloquent conditional filters (`when`) for readability and safety
        $data = Kategori::when($kode !== null && $kode !== '', function ($q) use ($kode) {
                $q->where('kode', 'LIKE', '%' . $kode . '%');
            })
            ->when($nama !== null && $nama !== '', function ($q) use ($nama) {
                $q->where('nama', 'LIKE', '%' . $nama . '%');
            })
            ->orderBy('id')
            ->get(['id', 'kode', 'nama']);

        return response()->json(['status' => 200, 'data' => $data]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = Kategori::find($id);
        }
        return view('kategori.form.index', ['method' => $method, 'item' => $item]);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $k = new Kategori();
        } else {
            $k = Kategori::find($id);
        }
        $k->kode = $request->kode;
        $k->nama = $request->nama;
        $k->save();

        return redirect('/kategori');
    }

    public function singleView($kode)
    {
        // eager-load master_items (only the fields we need) to avoid extra queries
        $kategori = Kategori::with(['master_items' => function ($q) {
            $q->select('master_items.id', 'master_items.kode', 'master_items.nama');
        }])->where('kode', $kode)->first();

        $items = [];
        if ($kategori) {
            $items = $kategori->master_items;
        }

        return view('kategori.single.index', ['kategori' => $kategori, 'items' => $items]);
    }

    public function printPdf($kode)
    {
        // load kategori with items
        $kategori = Kategori::with(['master_items' => function ($q) {
            $q->select('master_items.id','master_items.kode','master_items.nama','master_items.harga_beli');
        }])->where('kode', $kode)->first();

        if (!$kategori) {
            return redirect('/kategori')->with('error', 'Kategori tidak ditemukan');
        }

        $data = [
            'kategori' => $kategori,
            'items' => $kategori->master_items,
            'printed_at' => now()->format('d-m-Y H:i:s'),
        ];

        $pdf = Pdf::loadView('kategori.single.print', $data)->setPaper('a4', 'portrait');

        $fileName = 'kategori_' . $kategori->kode . '.pdf';
        return $pdf->download($fileName);
    }

    public function delete($id)
    {
        Kategori::find($id)->delete();
        return redirect('/kategori');
    }
}
