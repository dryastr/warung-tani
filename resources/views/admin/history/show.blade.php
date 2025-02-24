@extends('layouts-otika.main')

@section('title', 'Detail Transaksi')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Detail Transaksi</h4>
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Informasi Transaksi</h5>
                                <dl class="row">
                                    <dt class="col-sm-4">ID Transaksi</dt>
                                    <dd class="col-sm-8">{{ $transaction->id }}</dd>

                                    <dt class="col-sm-4">Tanggal</dt>
                                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($transaction->date)->format('d F Y') }}</dd>

                                    <dt class="col-sm-4">Pelanggan</dt>
                                    <dd class="col-sm-8">{{ $transaction->customer->name }}</dd>

                                    <dt class="col-sm-4">Kasir</dt>
                                    <dd class="col-sm-8">{{ $transaction->user->name }}</dd>

                                    <dt class="col-sm-4">Total Harga</dt>
                                    <dd class="col-sm-8">{{ number_format($transaction->total_price, 0, ',', '.') }}</dd>

                                    <dt class="col-sm-4">Diskon</dt>
                                    <dd class="col-sm-8">{{ number_format($transaction->discount, 0, ',', '.') }}</dd>

                                    <dt class="col-sm-4">Total Pembayaran</dt>
                                    <dd class="col-sm-8">{{ number_format($transaction->total_payment, 0, ',', '.') }}</dd>
                                </dl>
                            </div>
                        </div>

                        <hr>

                        <h5>Daftar Produk</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaction->details as $key => $detail)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $detail->product->name }}</td>
                                        <td>{{ number_format($detail->price, 0, ',', '.') }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td>{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
