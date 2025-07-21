@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Daftar Invoice</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <a href="{{ route('invoices.create') }}"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Buat Invoice
            Baru</a>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Nomor Invoice</th>
                        <th class="py-3 px-6 text-left">Customer</th>
                        <th class="py-3 px-6 text-left">Produk Terjual</th>
                        <th class="py-3 px-6 text-left">Jumlah</th>
                        <th class="py-3 px-6 text-left">Total</th>
                        <th class="py-3 px-6 text-left">Tanggal Invoice</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    @foreach ($invoices as $invoice)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $invoice->id }}</td>
                            <td class="py-3 px-6 text-left">{{ $invoice->invoice_number }}</td>
                            <td class="py-3 px-6 text-left">{{ $invoice->customer_name }}</td>
                            <td class="py-3 px-6 text-left">{{ $invoice->product_sold_name }}</td>
                            <td class="py-3 px-6 text-left">{{ $invoice->quantity_sold }}</td>
                            <td class="py-3 px-6 text-left">Rp {{ number_format($invoice->total_amount, 2, ',', '.') }}</td>
                            <td class="py-3 px-6 text-left">{{ $invoice->invoice_date }}</td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center space-x-2">
                                    <a href="{{ route('invoices.edit', $invoice->id) }}"
                                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-xs">Edit</a>
                                    <a href="{{ route('invoices.show', $invoice->id) }}"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs">Lihat</a>
                                    {{-- Opsional: tombol view detail --}}
                                    <a href="{{ route('invoices.printPdf', $invoice->id) }}"
                                        class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-1 px-3 rounded text-xs"
                                        target="_blank">PDF</a> {{-- ✅ Tombol Cetak PDF --}}
                                    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus invoice ini? Stok produk jadi akan dikembalikan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
