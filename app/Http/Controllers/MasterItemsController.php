<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use App\Models\Kategori;
use Illuminate\Http\Request;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;
        // Use Eloquent with eager loading and conditional filters
        $data_search = MasterItem::with('kategoris')
            ->when($kode !== null && $kode !== '', function ($q) use ($kode) {
                $q->where('kode', $kode);
            })
            ->when($nama !== null && $nama !== '', function ($q) use ($nama) {
                $q->where('nama', 'LIKE', '%' . $nama . '%');
            })
            ->when($hargamin !== null && $hargamin !== '', function ($q) use ($hargamin) {
                $q->where('harga_beli', '>=', $hargamin);
            })
            ->when($hargamax !== null && $hargamax !== '', function ($q) use ($hargamax) {
                $q->where('harga_beli', '<=', $hargamax);
            })
            ->orderBy('id')
            ->get(['id','kode', 'foto', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier']);

        return response()->json([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = MasterItem::find($id);
        }
        $categories = Kategori::orderBy('nama')->get();
        $data['item'] = $item;
        $data['method'] = $method;
        $data['categories'] = $categories;
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        // handle file upload for foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            if ($file->isValid()) {
                // delete old foto if exists
                if (!empty($data_item->foto) && \Illuminate\Support\Facades\Storage::disk('public')->exists($data_item->foto)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($data_item->foto);
                }
                $path = $file->store('master_items', 'public');
                $data_item->foto = $path;
            }
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;
        $data_item->save();

        // sync kategori many-to-many
        $kategoriIds = $request->input('kategori', []);
        if (!is_array($kategoriIds)) {
            $kategoriIds = [];
        }
        $data_item->kategoris()->sync($kategoriIds);

        return redirect('master-items');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach($data as $item)
        {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100,1000000);
            $item->laba = rand(10,99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi','Bukulapuk','TokoBagas','E Commurz','Blublu'];
        $random = rand(0,4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat','Alkes','Matkes','Umum','ATK'];
        $random = rand(0,4);
        return $array[$random];
    }
}
