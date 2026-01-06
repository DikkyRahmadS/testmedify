<?php

namespace App\Exports;

use App\Models\MasterItem;
use Illuminate\Collections\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MasterItemsExport implements FromCollection, WithHeadings
{
    /**
     * Return collection of rows to be exported
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $items = MasterItem::with('kategoris')->get();

        $rows = collect();
        $no = 1;
        foreach ($items as $item) {
            $kategoriNames = '';
            if ($item->kategoris && $item->kategoris->count()) {
                $kategoriNames = $item->kategoris->pluck('nama')->implode(', ');
            }

            $harga_jual = round($item->harga_beli + ($item->harga_beli * $item->laba / 100));

            // format harga as Rupiah and append percent sign to laba
            $formattedHarga = 'Rp ' . number_format($item->harga_beli, 0, ',', '.');
            $formattedLaba = $item->laba . ' %';
            $formattedHargaJual = 'Rp ' . number_format($harga_jual, 0, ',', '.');

            $rows->push([
                $no++,
                $kategoriNames,
                $item->nama,
                $item->supplier,
                $formattedHarga,
                $formattedLaba,
                $formattedHargaJual,
            ]);
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kategori',
            'Nama Items',
            'Nama Supplier',
            'Harga',
            'Laba',
            'Harga Jual',
        ];
    }
}
