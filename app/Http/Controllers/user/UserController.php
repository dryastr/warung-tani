<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Customer;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalAdmins = User::whereHas('role', function ($query) {
            $query->where('name', 'admin');
        })->count();
        $totalTransactions = Transaction::count();
        $totalCustomers = Customer::count();

        $monthlyTransactions = Transaction::selectRaw('MONTH(date) as month, SUM(total_payment) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = $monthlyTransactions->pluck('month')->map(function ($m) {
            return date('F', mktime(0, 0, 0, $m, 1));
        });

        $totals = $monthlyTransactions->pluck('total');

        return view('user.dashboard', compact(
            'totalProducts',
            'totalAdmins',
            'totalTransactions',
            'totalCustomers',
            'months',
            'totals'
        ));
    }
}
