<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    /**
     * data transaksi untuk diekspor.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Transaction::with(['customer', 'user', 'details.product'])
            ->get()
            ->map(function ($transaction) {
                return [
                    'ID Transaksi' => $transaction->id,
                    'Tanggal' => \Carbon\Carbon::parse($transaction->date)->format('d F Y'),
                    'Pelanggan' => $transaction->customer->name,
                    'Kasir' => $transaction->user->name,
                    'Total Harga' => $transaction->total_price,
                    'Diskon' => $transaction->discount,
                    'Total Pembayaran' => $transaction->total_payment,
                    'Produk' => $transaction->details->map(function ($detail) {
                        return $detail->product->name . ' (Qty: ' . $detail->quantity . ', Subtotal: ' . $detail->subtotal . ')';
                    })->implode(', '),
                ];
            });
    }

    /**
     * header untuk file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Tanggal',
            'Pelanggan',
            'Kasir',
            'Total Harga',
            'Diskon',
            'Total Pembayaran',
            'Produk',
        ];
    }
}
