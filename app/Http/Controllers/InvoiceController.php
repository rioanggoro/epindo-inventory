<?php

namespace App\Http\Controllers;

use App\Models\Invoice; 
use App\Models\Stock;   
use App\Models\Produksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; 
use Barryvdh\DomPDF\Facade\Pdf; 

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua invoice.
     */
    public function index()
    {
        $invoices = Invoice::orderBy('invoice_date', 'desc')->get();
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk membuat invoice baru.
     */
    public function create()
    {
        // Ambil daftar produk jadi dari stok untuk dropdown
        $finishedProducts = Stock::where('type', 'finished_product')->get();
        return view('invoices.create', compact('finishedProducts'));
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan invoice baru ke database dan mengurangi stok produk jadi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number',
            'customer_name' => 'required|string|max:255',
            'product_sold_name' => 'required|string|max:255|exists:stocks,item_name,type,finished_product',
            'quantity_sold' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
        ]);

        try { // ✅ Mulai blok try
            DB::transaction(function () use ($request) {
                $productStock = Stock::where('item_name', $request->product_sold_name)
                                      ->where('type', 'finished_product')
                                      ->first();

                if (!$productStock || $productStock->current_stock < $request->quantity_sold) {
                    throw new \Exception('Stok produk jadi (' . $request->product_sold_name . ') tidak cukup untuk penjualan ini.'); // Ini yang melempar exception
                }

                Invoice::create($request->all());

                $productStock->current_stock -= $request->quantity_sold;
                $productStock->save();
            });

            return redirect()->route('invoices.index')
                             ->with('success', 'Invoice berhasil dibuat, stok produk jadi berkurang! ✅');

        } catch (\Exception $e) { // ✅ Tangkap exception di sini
            return redirect()->back()
                             ->withInput()
                             ->with('error', $e->getMessage()); // Kirim pesan exception ke sesi
        }
    }


    /**
     * Display the specified resource.
     * Menampilkan detail satu record invoice.
     */
    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit record invoice yang sudah ada.
     */
    public function edit(Invoice $invoice)
    {
        $finishedProducts = Stock::where('type', 'finished_product')->get();
        return view('invoices.edit', compact('invoice', 'finishedProducts'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui data invoice di database dan menyesuaikan stok.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'invoice_number' => ['required', 'string', 'max:255', Rule::unique('invoices')->ignore($invoice->id)],
            'customer_name' => 'required|string|max:255',
            'product_sold_name' => 'required|string|max:255|exists:stocks,item_name,type,finished_product',
            'quantity_sold' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
        ]);

        $oldQuantitySold = $invoice->quantity_sold;
        $oldProductSoldName = $invoice->product_sold_name;

        try { 
            DB::transaction(function () use ($request, $invoice, $oldQuantitySold, $oldProductSoldName) {
                $oldProductStock = Stock::where('item_name', $oldProductSoldName)
                                         ->where('type', 'finished_product')
                                         ->first();
                if ($oldProductStock) {
                    $oldProductStock->current_stock += $oldQuantitySold;
                    $oldProductStock->save();
                }

                $newProductStock = Stock::where('item_name', $request->product_sold_name)
                                         ->where('type', 'finished_product')
                                         ->first();

                if (!$newProductStock || $newProductStock->current_stock < $request->quantity_sold) {
                    throw new \Exception('Stok produk jadi (' . $request->product_sold_name . ') tidak cukup untuk perubahan penjualan ini.'); 
                }

                $invoice->update($request->all());

                $newProductStock->current_stock -= $request->quantity_sold;
                $newProductStock->save();
            });

            return redirect()->route('invoices.index')
                             ->with('success', 'Invoice berhasil diperbarui dan stok disesuaikan! ✅');

        } catch (\Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus record invoice dari database dan menyesuaikan stok.
     */
    public function destroy(Invoice $invoice)
    {
        DB::transaction(function () use ($invoice) {
            // 1. ✅ Tambahkan kembali Stok Produk Jadi yang sebelumnya terjual
            $productStock = Stock::where('item_name', $invoice->product_sold_name)
                                  ->where('type', 'finished_product')
                                  ->first();

            if ($productStock) {
                $productStock->current_stock += $invoice->quantity_sold;
                $productStock->save();
            }

            // 2. Hapus data invoice
            $invoice->delete();
        });

        return redirect()->route('invoices.index')
                         ->with('success', 'Invoice berhasil dihapus, dan stok produk jadi dikembalikan! ✅');
    }

    /**
     * Generate PDF for the specified invoice.
     */
    public function printPdf(Invoice $invoice)
    {

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

  
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }
}