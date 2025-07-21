@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Invoice</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                <ul class="mt-3 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="invoice_number" class="block text-gray-700 text-sm font-bold mb-2">Nomor Invoice:</label>
                <input type="text" name="invoice_number" id="invoice_number"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ old('invoice_number', $invoice->invoice_number) }}" required>
            </div>
            <div class="mb-4">
                <label for="customer_name" class="block text-gray-700 text-sm font-bold mb-2">Nama Customer:</label>
                <input type="text" name="customer_name" id="customer_name"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ old('customer_name', $invoice->customer_name) }}" required>
            </div>
            <div class="mb-4">
                <label for="product_sold_name" class="block text-gray-700 text-sm font-bold mb-2">Produk Terjual:</label>
                <select name="product_sold_name" id="product_sold_name"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                    <option value="">Pilih Produk Jadi</option>
                    @foreach ($finishedProducts as $product)
                        <option value="{{ $product->item_name }}"
                            {{ old('product_sold_name', $invoice->product_sold_name) == $product->item_name ? 'selected' : '' }}>
                            {{ $product->item_name }} (Stok: {{ $product->current_stock }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="quantity_sold" class="block text-gray-700 text-sm font-bold mb-2">Jumlah Terjual:</label>
                <input type="number" name="quantity_sold" id="quantity_sold"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ old('quantity_sold', $invoice->quantity_sold) }}" required min="1">
            </div>
            <div class="mb-4">
                <label for="total_amount" class="block text-gray-700 text-sm font-bold mb-2">Total Harga:</label>
                <input type="number" step="0.01" name="total_amount" id="total_amount"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ old('total_amount', $invoice->total_amount) }}" required min="0">
            </div>
            <div class="mb-6">
                <label for="invoice_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Invoice:</label>
                <input type="date" name="invoice_date" id="invoice_date"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ old('invoice_date', $invoice->invoice_date) }}" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update
                </button>
                <a href="{{ route('invoices.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
