<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    /**
     * Query dasar yang dipakai bareng oleh index() & print()
     * biar datanya konsisten di kedua halaman.
     */
    protected function baseQuery()
    {
        return StockTransaction::with(['user', 'product'])
            ->latest();
    }

    public function index(Request $request)
    {
        $activities = $this->baseQuery()->get();

        return view('report.user_activity', compact('activities'));
    }

    public function print(Request $request)
    {
        $activities = $this->baseQuery()->get();

        return view('report.user_pdf', compact('activities'));
    }
}