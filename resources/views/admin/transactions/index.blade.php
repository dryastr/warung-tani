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
                        <div class="d-flex align-items-center gap-2">
                            @if (auth()->user()->role->name == 'user')
                                <a href="{{ route('transactions.export') }}" class="btn btn-success">
                                    <i class="fas fa-download"></i> Download Excel
                                </a>
                            @else
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                                    Tambah Transaksi
                                </button>
                                <a href="{{ route('transactions.export') }}" class="btn btn-success">
                                    <i class="fas fa-download"></i> Download Excel
                                </a>
                            @endif
                        </div>
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
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $transaction->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $transaction->id }}">
                                                        @if (auth()->user()->role->name == 'admin')
                                                            <li>
                                                                <a class="dropdown-item" href="#"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editTransactionModal"
                                                                    onclick="editTransaction({{ json_encode($transaction) }})">Ubah</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('transactions.show', $transaction->id) }}"
                                                                    class="dropdown-item">Detail</a>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <a href="{{ route('transactions-owner.show', $transaction->id) }}"
                                                                    class="dropdown-item">Detail</a>
                                                            </li>
                                                            <li class="">
                                                                <form
                                                                    action="{{ route('transactions-owner.destroy', $transaction->id) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="dropdown-item delete">Hapus</button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
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

    <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransactionLabel">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addTransactionForm" action="{{ route('transactions.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Pelanggan</label>
                            <select class="form-control" name="customer_id" required>
                                <option value="" disabled selected>Pilih Pelanggan</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="date" required>
                        </div>

                        <div class="mb-3">
                            <label for="total_price" class="form-label">Total Harga</label>
                            <input type="number" class="form-control" id="total_price" name="total_price" required
                                readonly>
                        </div>

                        <div class="mb-3">
                            <label for="discount" class="form-label">Diskon</label>
                            <input type="number" class="form-control" id="discount" name="discount" value="0"
                                oninput="calculateTotal()">
                        </div>

                        <div class="mb-3">
                            <label for="total_payment" class="form-label">Total Bayar</label>
                            <input type="number" class="form-control" id="total_payment" name="total_payment" required
                                readonly>
                        </div>

                        <h5>Produk</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="product-list">
                                <tr>
                                    <td>
                                        <select class="form-control product-select" required onchange="updatePrice(this)">
                                            <option value="" disabled selected>Pilih Produk</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                    {{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control price" readonly></td>
                                    <td><input type="number" class="form-control quantity" min="1"
                                            value="1" oninput="updateSubtotal(this)"></td>
                                    <td><input type="number" class="form-control subtotal" readonly></td>
                                    <td><button type="button" class="btn btn-danger btn-sm"
                                            onclick="removeRow(this)">Hapus</button></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="d-flex align-items-center justify-content-between">
                            <button type="button" class="btn btn-success" onclick="addRow()">Tambah Produk</button>

                            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTransactionModal" tabindex="-1" aria-labelledby="editTransactionLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTransactionLabel">Edit Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTransactionForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editTransactionId" name="id">

                        <div class="mb-3">
                            <label for="edit_customer_id" class="form-label">Pelanggan</label>
                            <select class="form-control" name="customer_id" id="edit_customer_id" required>
                                <option value="" disabled selected>Pilih Pelanggan</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 d-none">
                            <label for="edit_date" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="date" id="edit_date" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_total_price" class="form-label">Total Harga</label>
                            <input type="number" class="form-control" id="edit_total_price" name="total_price" required
                                readonly>
                        </div>

                        <div class="mb-3">
                            <label for="edit_discount" class="form-label">Diskon</label>
                            <input type="number" class="form-control" id="edit_discount" name="discount"
                                value="0" oninput="calculateEditTotal()">
                        </div>

                        <div class="mb-3">
                            <label for="edit_total_payment" class="form-label">Total Bayar</label>
                            <input type="number" class="form-control" id="edit_total_payment" name="total_payment"
                                required readonly>
                        </div>

                        <h5>Produk</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="edit-product-list">
                            </tbody>
                        </table>
                        <div class="d-flex align-items-center justify-content-between">
                            <button type="button" class="btn btn-success" onclick="addEditRow()">Tambah Produk</button>

                            <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function addRow() {
            let row = `<tr>
        <td>
            <select class="form-control product-select" required onchange="updatePrice(this)">
                <option value="" disabled selected>Pilih Produk</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="number" class="form-control price" readonly></td>
        <td><input type="number" class="form-control quantity" min="1" value="1" oninput="updateSubtotal(this)"></td>
        <td><input type="number" class="form-control subtotal" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Hapus</button></td>
    </tr>`;
            document.getElementById('product-list').insertAdjacentHTML('beforeend', row);
        }

        function updatePrice(select) {
            let price = select.options[select.selectedIndex].dataset.price;
            let row = select.closest('tr');
            row.querySelector('.price').value = price;
            row.querySelector('.subtotal').value = price * row.querySelector('.quantity').value;
            calculateTotal();
        }

        function updateSubtotal(input) {
            let row = input.closest('tr');
            let price = row.querySelector('.price').value;
            row.querySelector('.subtotal').value = price * input.value;
            calculateTotal();
        }

        function removeRow(button) {
            button.closest('tr').remove();
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(subtotal => {
                total += parseFloat(subtotal.value || 0);
            });
            document.getElementById('total_price').value = total;
            let discount = parseFloat(document.getElementById('discount').value || 0);
            document.getElementById('total_payment').value = total - discount;
        }

        document.getElementById('addTransactionForm').addEventListener('submit', function(event) {
            let items = [];
            document.querySelectorAll('#product-list tr').forEach(row => {
                let productId = row.querySelector('.product-select')?.value;
                let quantity = row.querySelector('.quantity')?.value;
                let price = row.querySelector('.price')?.value;
                let subtotal = row.querySelector('.subtotal')?.value;

                if (productId && quantity && price && subtotal) {
                    items.push({
                        product_id: productId,
                        quantity: quantity,
                        price: price,
                        subtotal: subtotal
                    });
                }
            });

            console.log("Items yang dikirim:", items);

            let inputHidden = document.createElement("input");
            inputHidden.type = "hidden";
            inputHidden.name = "items";
            inputHidden.value = JSON.stringify(items);
            this.appendChild(inputHidden);
        });
    </script>

    <script>
        function editTransaction(transaction) {
            console.log('Transaction data received:', transaction);

            document.getElementById('editTransactionForm').reset();
            document.getElementById('edit-product-list').innerHTML = '';

            document.getElementById('editTransactionId').value = transaction.id;
            document.getElementById('edit_customer_id').value = transaction.customer_id;
            if (transaction.date) {
                let date = new Date(transaction.date);
                let formattedDate = date.toISOString().split('T')[0];
                document.getElementById('edit_date').value = formattedDate;
            } else {
                document.getElementById('edit_date').value = '';
            }
            document.getElementById('edit_discount').value = transaction.discount || 0;
            document.getElementById('edit_total_price').value = transaction.total_price;
            document.getElementById('edit_total_payment').value = transaction.total_payment;

            document.getElementById('editTransactionForm').action = `/transactions/${transaction.id}`;

            if (transaction.details && transaction.details.length > 0) {
                console.log('Transaction details:', transaction.details);
                transaction.details.forEach(detail => {
                    console.log('Adding detail:', detail);
                    addEditRow(detail);
                });
            } else {
                console.log('No details found, adding empty row');
                addEditRow();
            }

            calculateEditTotal();
        }

        function addEditRow(detail = null) {
            console.log('Adding row with detail:', detail);

            let row = `<tr>
    <td>
        <select class="form-control edit-product-select" required onchange="updateEditPrice(this)">
            <option value="" disabled selected>Pilih Produk</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}"
                        data-price="{{ $product->price }}"
                        ${detail && detail.product_id == {{ $product->id }} ? 'selected="selected"' : ''}>
                    {{ $product->name }}
                </option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="number" class="form-control edit-price"
               value="${detail ? detail.price : ''}" readonly>
    </td>
    <td>
        <input type="number" class="form-control edit-quantity"
               min="1"
               value="${detail ? detail.quantity : 1}"
               oninput="updateEditSubtotal(this)">
    </td>
    <td>
        <input type="number" class="form-control edit-subtotal"
               value="${detail ? detail.subtotal : ''}" readonly>
    </td>
    <td>
        <button type="button" class="btn btn-danger btn-sm" onclick="removeEditRow(this)">Hapus</button>
    </td>
    </tr>`;

            document.getElementById('edit-product-list').insertAdjacentHTML('beforeend', row);

            if (detail) {
                let lastRow = document.getElementById('edit-product-list').lastElementChild;
                let select = lastRow.querySelector('.edit-product-select');

                if (select.value) {
                    console.log('Triggering price update for product:', select.value);
                    updateEditPrice(select);
                }
            }
        }

        function updateEditPrice(select) {
            let price = select.options[select.selectedIndex].dataset.price;
            let row = select.closest('tr');
            row.querySelector('.edit-price').value = price;
            updateEditSubtotal(row.querySelector('.edit-quantity'));
        }

        function updateEditSubtotal(input) {
            let row = input.closest('tr');
            let price = parseFloat(row.querySelector('.edit-price').value) || 0;
            let quantity = parseFloat(input.value) || 0;
            let subtotal = price * quantity;
            row.querySelector('.edit-subtotal').value = subtotal;
            calculateEditTotal();
        }

        function removeEditRow(button) {
            let tbody = document.getElementById('edit-product-list');
            if (tbody.children.length > 1) {
                button.closest('tr').remove();
                calculateEditTotal();
            } else {
                alert('Minimal harus ada satu produk dalam transaksi');
            }
        }

        function calculateEditTotal() {
            let total = 0;
            document.querySelectorAll('.edit-subtotal').forEach(subtotal => {
                total += parseFloat(subtotal.value || 0);
            });
            document.getElementById('edit_total_price').value = total;
            let discount = parseFloat(document.getElementById('edit_discount').value || 0);
            document.getElementById('edit_total_payment').value = total - discount;
        }

        document.getElementById('editTransactionForm').addEventListener('submit', function(event) {
            let validProducts = document.querySelectorAll('.edit-product-select').length;
            if (validProducts === 0) {
                event.preventDefault();
                alert('Minimal harus ada satu produk dalam transaksi');
                return;
            }

            let items = [];
            document.querySelectorAll('#edit-product-list tr').forEach(row => {
                let productId = row.querySelector('.edit-product-select')?.value;
                let quantity = row.querySelector('.edit-quantity')?.value;
                let price = row.querySelector('.edit-price')?.value;
                let subtotal = row.querySelector('.edit-subtotal')?.value;

                if (productId && quantity && price && subtotal) {
                    items.push({
                        product_id: productId,
                        quantity: parseInt(quantity),
                        price: parseFloat(price),
                        subtotal: parseFloat(subtotal)
                    });
                }
            });

            if (items.length === 0) {
                event.preventDefault();
                alert('Mohon lengkapi data produk');
                return;
            }

            let inputHidden = document.createElement("input");
            inputHidden.type = "hidden";
            inputHidden.name = "items";
            inputHidden.value = JSON.stringify(items);
            this.appendChild(inputHidden);
        });

        document.addEventListener('DOMContentLoaded', function() {
            let editTransactionModal = document.getElementById('editTransactionModal');
            if (editTransactionModal) {
                editTransactionModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('editTransactionForm').reset();
                    document.getElementById('edit-product-list').innerHTML = '';
                });
            }
        });
    </script>
@endpush
