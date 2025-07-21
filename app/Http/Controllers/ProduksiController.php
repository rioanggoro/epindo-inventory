<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Validation\Rule;

class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua produksi.
     */
    public function index()
    {
        $produksis = Produksi::orderBy('production_date', 'desc')->get();
        return view('produksis.index', compact('produksis'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk menambah data produksi baru.
     */
    public function create()
    {
       
        $rawMaterials = Stock::where('type', 'raw_material')
                              ->where('current_stock', '>', 0)
                              ->get();
        return view('produksis.create', compact('rawMaterials'));
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan data produksi baru ke database, mengurangi stok bahan baku, dan menambah stok produk jadi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity_produced' => 'required|integer|min:1',
            'raw_material_used' => 'required|integer|min:1',
            'production_date' => 'required|date',
            'raw_material_item_name' => 'required|string|max:255|exists:stocks,item_name,type,raw_material'
        ]);

        try { 
            DB::transaction(function () use ($request) {
                $rawMaterialStock = Stock::where('item_name', $request->raw_material_item_name)
                                          ->where('type', 'raw_material')
                                          ->first();

                // Validasi stok bahan baku (ini yang melempar exception)
                if (!$rawMaterialStock || $rawMaterialStock->current_stock < $request->raw_material_used) {
                    throw new \Exception('Stok bahan baku (' . $request->raw_material_item_name . ') tidak cukup untuk produksi ini.');
                }

                Produksi::create([
                    'product_name' => $request->product_name,
                    'quantity_produced' => $request->quantity_produced,
                    'raw_material_used' => $request->raw_material_used,
                    'production_date' => $request->production_date,
                    'raw_material_item_name' => $request->raw_material_item_name,
                ]);

                $rawMaterialStock->current_stock -= $request->raw_material_used;
                $rawMaterialStock->save();

                $finishedProductStock = Stock::firstOrNew([
                    'item_name' => $request->product_name,
                    'type' => 'finished_product'
                ]);
                $finishedProductStock->current_stock += $request->quantity_produced;
                $finishedProductStock->save();
            });

            return redirect()->route('produksis.index')
                             ->with('success', 'Produksi berhasil ditambahkan, stok bahan baku berkurang, dan stok produk jadi bertambah! ✅');

        } catch (\Exception $e) { 
            return redirect()->back()
                             ->withInput() 
                             ->with('error', $e->getMessage()); 
        }
    }


    /**
     * Display the specified resource.
     * Menampilkan detail satu record produksi.
     */
    public function show(Produksi $produksi)
    {
        return view('produksis.show', compact('produksi'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit record produksi yang sudah ada.
     */
     public function edit(Produksi $produksi)
    {
        $rawMaterials = Stock::where('type', 'raw_material')
                              ->where('current_stock', '>', 0)
                              ->orWhere('item_name', $produksi->raw_material_item_name) 
                              ->get();
        return view('produksis.edit', compact('produksi', 'rawMaterials'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui data produksi di database dan menyesuaikan stok.
     */
    public function update(Request $request, Produksi $produksi)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity_produced' => 'required|integer|min:1',
            'raw_material_used' => 'required|integer|min:1',
            'production_date' => 'required|date',
            'raw_material_item_name' => 'required|string|max:255|exists:stocks,item_name,type,raw_material'
        ]);

        $oldQuantityProduced = $produksi->quantity_produced;
        $oldRawMaterialUsed = $produksi->raw_material_used;
        $oldProductName = $produksi->product_name;
        $oldRawMaterialItemName = $produksi->raw_material_item_name;

        try { 
            DB::transaction(function () use ($request, $produksi, $oldQuantityProduced, $oldRawMaterialUsed, $oldProductName, $oldRawMaterialItemName) {
                // Kembalikan stok lama sebelum diupdate
                $rawMaterialStockBeforeUpdate = Stock::where('item_name', $oldRawMaterialItemName)
                                                      ->where('type', 'raw_material')
                                                      ->first();
                if ($rawMaterialStockBeforeUpdate) {
                    $rawMaterialStockBeforeUpdate->current_stock += $oldRawMaterialUsed;
                    $rawMaterialStockBeforeUpdate->save();
                }

                $finishedProductStockBeforeUpdate = Stock::where('item_name', $oldProductName)
                                                        ->where('type', 'finished_product')
                                                        ->first();
                if ($finishedProductStockBeforeUpdate) {
                    $finishedProductStockBeforeUpdate->current_stock -= $oldQuantityProduced;
                    if ($finishedProductStockBeforeUpdate->current_stock < 0) {
                        $finishedProductStockBeforeUpdate->current_stock = 0;
                    }
                    $finishedProductStockBeforeUpdate->save();
                }

               
                $newRawMaterialStock = Stock::where('item_name', $request->raw_material_item_name)
                                             ->where('type', 'raw_material')
                                             ->first();

                if (!$newRawMaterialStock || $newRawMaterialStock->current_stock < $request->raw_material_used) {
                    throw new \Exception('Stok bahan baku (' . $request->raw_material_item_name . ') tidak cukup untuk perubahan produksi ini.');
                }

                $produksi->update([
                    'product_name' => $request->product_name,
                    'quantity_produced' => $request->quantity_produced,
                    'raw_material_used' => $request->raw_material_used,
                    'production_date' => $request->production_date,
                    'raw_material_item_name' => $request->raw_material_item_name,
                ]);

                $newRawMaterialStock->current_stock -= $request->raw_material_used;
                $newRawMaterialStock->save();

                $newFinishedProductStock = Stock::firstOrNew([
                    'item_name' => $request->product_name,
                    'type' => 'finished_product'
                ]);
                $newFinishedProductStock->current_stock += $request->quantity_produced;
                $newFinishedProductStock->save();
            });

            return redirect()->route('produksis.index')
                             ->with('success', 'Produksi berhasil diperbarui dan stok disesuaikan! ✅');

        } catch (\Exception $e) { // ✅ Tangkap exception di sini
            return redirect()->back()
                             ->withInput()
                             ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus record produksi dari database dan menyesuaikan stok.
     */
    public function destroy(Produksi $produksi)
    {
        DB::transaction(function () use ($produksi) {
            // 1. Tambahkan kembali Stok Bahan Baku yang sebelumnya digunakan untuk produksi ini
            $rawMaterialStock = Stock::where('item_name', $produksi->raw_material_item_name) // ✅ Sudah dikoreksi
                                      ->where('type', 'raw_material')
                                      ->first();

            if ($rawMaterialStock) {
                $rawMaterialStock->current_stock += $produksi->raw_material_used;
                $rawMaterialStock->save();
            }

            // 2. Kurangi Stok Produk Jadi yang sebelumnya dihasilkan dari produksi ini
            $finishedProductStock = Stock::where('item_name', $produksi->product_name)
                                         ->where('type', 'finished_product')
                                         ->first();

            if ($finishedProductStock) {
                $finishedProductStock->current_stock -= $produksi->quantity_produced;
                if ($finishedProductStock->current_stock < 0) {
                    $finishedProductStock->current_stock = 0; // Pastikan stok tidak negatif
                }
                $finishedProductStock->save();
            }

            // 3. Hapus data produksi
            $produksi->delete();
        });

        return redirect()->route('produksis.index')
                         ->with('success', 'Produksi berhasil dihapus, stok bahan baku dikembalikan, dan stok produk jadi dikurangi! ✅');
    }
}