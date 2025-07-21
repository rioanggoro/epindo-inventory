<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; 

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua stok (bahan baku dan produk jadi).
     */
    public function index()
    {
        $stocks = Stock::orderBy('item_name')->get();
        return view('stocks.index', compact('stocks'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk menambah item stok baru.
     */
    public function create()
    {
        return view('stocks.create');
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan item stok baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255|unique:stocks,item_name', 
            'type' => ['required', Rule::in(['raw_material', 'finished_product'])], 
            'current_stock' => 'required|integer|min:0', 
        ]);

        Stock::create($request->all());

        return redirect()->route('stocks.index')
                         ->with('success', 'Item stok berhasil ditambahkan! ✅');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail satu item stok.
     */
    public function show(Stock $stock)
    {
        return view('stocks.show', compact('stock'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit item stok yang sudah ada.
     */
    public function edit(Stock $stock)
    {
        return view('stocks.edit', compact('stock'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui data item stok di database.
     */
    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            // Item name harus unik, kecuali untuk item yang sedang diedit ini sendiri
            'item_name' => ['required', 'string', 'max:255', Rule::unique('stocks')->ignore($stock->id)],
            'type' => ['required', Rule::in(['raw_material', 'finished_product'])],
            'current_stock' => 'required|integer|min:0',
        ]);

        $stock->update($request->all());

        return redirect()->route('stocks.index')
                         ->with('success', 'Item stok berhasil diperbarui! ✅');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus item stok dari database.
     */
    public function destroy(Stock $stock)
    {
        // PERHATIAN: Hapus stok tanpa pengecekan transaksi lain bisa berisiko.
        // Dalam sistem nyata, mungkin perlu validasi jika stok sedang digunakan/terkait transaksi aktif.
        $stock->delete();

        return redirect()->route('stocks.index')
                         ->with('success', 'Item stok berhasil dihapus! ✅');
    }
}