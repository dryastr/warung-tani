@extends('layouts-otika.main')

@section('title', 'Data Transaksi')

@push('styles')
    <style>
        .delete {
            padding: 10px 20px;
            font-weight: 500;
            line-height: 1.2;
            font-size: 13px;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4>Data Transaksi</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-transactions">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Tanggal</th>
                                        <th>Total Bayar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $key => $transaction)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>{{ $transaction->customer->name }}</td>
                                            <td>{{ $transaction->date }}</td>
                                            <td>Rp {{ number_format($transaction->total_payment, 2, ',', '.') }}</td>
                                            <td class="">
                                                @if (auth()->user()->role->name == 'admin')
                                                    <a href="{{ route('transactions.show', $transaction->id) }}"
                                                        class="btn btn-sm btn-primary">Detail</a>
                                                @else
                                                    <a href="{{ route('transactions-owner.show', $transaction->id) }}"
                                                        class="btn btn-sm btn-primary">Detail</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
