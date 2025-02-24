<?php

namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['customer', 'user', 'details'])->latest()->get();
        $customers = Customer::all();
        $products = Product::all();

        return view('admin.transactions.index', compact('transactions', 'customers', 'products'));
    }

    public function show($id)
    {
        $transaction = Transaction::with(['customer', 'user', 'details.product'])->findOrFail($id);

        return view('admin.transactions.show', compact('transaction'));
    }

    public function store(Request $request)
    {
        Log::info('Transaction store request received', ['request' => $request->all()]);

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'total_price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'total_payment' => 'required|numeric',
            'items' => 'required',
            'date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'date' => $request->date,
                'customer_id' => $request->customer_id,
                'total_price' => $request->total_price,
                'discount' => $request->discount ?? 0,
                'total_payment' => $request->total_payment,
                'user_id' => auth()->id(),
            ]);

            $items = json_decode($request->items, true);

            foreach ($items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            DB::commit();
            Log::info('Transaction created successfully', ['transaction_id' => $transaction->id]);
            return redirect()->back()->with('success', 'Transaction successfully added');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to add transaction', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to add transaction: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('Transaction update request received', ['transaction_id' => $id, 'request' => $request->all()]);

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'total_price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'total_payment' => 'required|numeric',
            'items' => 'required',
            'date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($id);

            foreach ($transaction->details as $detail) {
                $product = Product::find($detail->product_id);
                if ($product) {
                    $product->increment('stock', $detail->quantity);
                }
            }

            TransactionDetail::where('transaction_id', $transaction->id)->delete();

            $transaction->update([
                'date' => $request->date,
                'customer_id' => $request->customer_id,
                'total_price' => $request->total_price,
                'discount' => $request->discount ?? 0,
                'total_payment' => $request->total_payment,
                'user_id' => auth()->id(),
            ]);

            $items = json_decode($request->items, true);

            foreach ($items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            DB::commit();
            Log::info('Transaction updated successfully', ['transaction_id' => $transaction->id]);
            return redirect()->back()->with('success', 'Transaction successfully updated');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update transaction', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update transaction: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->delete();

            return redirect()->back()->with('success', 'Transaction successfully deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete transaction: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new TransactionsExport, 'transactions.xlsx');
    }
}
