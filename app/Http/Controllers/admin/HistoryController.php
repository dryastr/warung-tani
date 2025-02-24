<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['customer', 'user', 'details'])->latest()->get();
        $customers = Customer::all();
        $products = Product::all();

        return view('admin.history.index', compact('transactions', 'customers', 'products'));
    }
}
