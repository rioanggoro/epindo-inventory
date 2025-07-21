@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Detail Invoice #{{ $invoice->invoice_number }}</h1>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Invoice:</label>
            <p class="text-gray-900">{{ $invoice->invoice_number }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Customer:</label>
            <p class="text-gray-900">{{ $invoice->customer_name }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Produk Terjual:</label>
            <p class="text-gray-900">{{ $invoice->product_sold_name }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Terjual:</label>
            <p class="text-gray-900">{{ $invoice->quantity_sold }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Total Harga:</label>
            <p class="text-gray-900">Rp {{ number_format($invoice->total_amount, 2, ',', '.') }}</p>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Invoice:</label>
            <p class="text-gray-900">{{ $invoice->invoice_date }}</p>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('invoices.edit', $invoice->id) }}"
                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Edit
            </a>
            <a href="{{ route('invoices.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Kembali ke Daftar
            </a>
            <a href="{{ route('invoices.printPdf', $invoice->id) }}"
                class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                target="_blank">
                Cetak PDF
            </a>
        </div>
    </div>
@endsection
