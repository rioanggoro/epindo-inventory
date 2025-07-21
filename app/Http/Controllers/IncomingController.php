<?php

namespace App\Http\Controllers;

use App\Models\Incoming; // Import Model Incoming
use App\Models\Stock;    // Import Model Stock
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Untuk transaksi database

class IncomingController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua barang masuk.
     */
    public function index()
    {
        $incomings = Incoming::orderBy('incoming_date', 'desc')->get();
        return view('incomings.index', compact('incomings'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk menambah barang masuk baru.
     */
    public function create()
    {
        return view('incomings.create');
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan data barang masuk baru ke database dan memperbarui stok.
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk dari form
        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'incoming_date' => 'required|date',
        ]);


        DB::transaction(function () use ($request) {
            Incoming::create($request->all());
            $stock = Stock::firstOrNew([
                'item_name' => $request->item_name,
                'type' => 'raw_material'
            ]);
            $stock->current_stock += $request->quantity; // Tambahkan jumlah barang masuk ke stok saat ini
            $stock->save(); // Simpan perubahan stok
        });

        // Redirect kembali ke halaman daftar barang masuk dengan pesan sukses
        return redirect()->route('incomings.index')
                         ->with('success', 'Barang masuk berhasil ditambahkan dan stok diperbarui! ✅');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail satu record barang masuk.
     */
    public function show(Incoming $incoming)
    {
        return view('incomings.show', compact('incoming'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit record barang masuk yang sudah ada.
     */
    public function edit(Incoming $incoming)
    {
        return view('incomings.edit', compact('incoming'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui data barang masuk di database dan menyesuaikan stok.
     */
    public function update(Request $request, Incoming $incoming)
    {
        // 1. Validasi data yang masuk dari form
        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'incoming_date' => 'required|date',
        ]);

        $oldQuantity = $incoming->quantity;
        $oldItemName = $incoming->item_name; 

        DB::transaction(function () use ($request, $incoming, $oldQuantity, $oldItemName) {
            $incoming->update($request->all());

            if ($oldItemName !== $request->item_name) {
                // Kurangi stok dari item lama
                $oldStock = Stock::where('item_name', $oldItemName)
                                 ->where('type', 'raw_material')
                                 ->first();
                if ($oldStock) {
                    $oldStock->current_stock -= $oldQuantity;
                    $oldStock->save();
                }

                // Tambahkan stok ke item baru
                $newStock = Stock::firstOrNew([
                    'item_name' => $request->item_name,
                    'type' => 'raw_material'
                ]);
                $newStock->current_stock += $request->quantity;
                $newStock->save();
            } else {
                // Jika nama item tidak berubah, hanya sesuaikan berdasarkan perbedaan kuantitas
                $stock = Stock::where('item_name', $request->item_name)
                              ->where('type', 'raw_material')
                              ->first();
                if ($stock) {
                    $stock->current_stock += ($request->quantity - $oldQuantity); 
                    $stock->save();
                }
            }
        });


        return redirect()->route('incomings.index')
                         ->with('success', 'Barang masuk berhasil diperbarui dan stok disesuaikan! ✅');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus record barang masuk dari database dan menyesuaikan stok.
     */
    public function destroy(Incoming $incoming)
    {
        // Menggunakan transaksi database
        DB::transaction(function () use ($incoming) {
            // 1. ✅ Kurangi Stok Bahan Baku di tabel 'stocks' sebelum dihapus
            $stock = Stock::where('item_name', $incoming->item_name)
                          ->where('type', 'raw_material')
                          ->first();

            if ($stock) {
                $stock->current_stock -= $incoming->quantity; // Kurangi stok sejumlah barang yang dihapus
                // Pastikan stok tidak menjadi negatif (opsional, tergantung kebijakan bisnis)
                if ($stock->current_stock < 0) {
                    $stock->current_stock = 0; // Atau lempar exception jika tidak boleh negatif
                }
                $stock->save();
            }

            // 2. Hapus data dari tabel 'incomings'
            $incoming->delete();
        });

        // Redirect kembali ke halaman daftar barang masuk dengan pesan sukses
        return redirect()->route('incomings.index')
                         ->with('success', 'Barang masuk berhasil dihapus dan stok disesuaikan! ✅');
    }
}