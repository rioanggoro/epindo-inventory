@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Daftar Stok Inventaris</h1>

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

        <a href="{{ route('stocks.create') }}"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Tambah Stok
            Baru</a>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Nama Item</th>
                        <th class="py-3 px-6 text-left">Tipe</th>
                        <th class="py-3 px-6 text-left">Stok Saat Ini</th>
                        <th class="py-3 px-6 text-left">Terakhir Diperbarui</th>
                        <th class="py-3 px-6 text-center">Aksi</th> {{-- ✅ Kolom Aksi --}}
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    @foreach ($stocks as $stock)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $stock->id }}</td>
                            <td class="py-3 px-6 text-left">{{ $stock->item_name }}</td>
                            <td class="py-3 px-6 text-left">{{ ucfirst(str_replace('_', ' ', $stock->type)) }}</td>
                            <td
                                class="py-3 px-6 text-left font-bold {{ $stock->current_stock < 10 && $stock->type == 'raw_material' ? 'text-red-600' : ($stock->current_stock < 5 && $stock->type == 'finished_product' ? 'text-red-600' : 'text-green-600') }}">
                                {{ $stock->current_stock }}
                            </td>
                            <td class="py-3 px-6 text-left">{{ $stock->updated_at->format('d M Y H:i') }}</td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center space-x-2">
                                    <a href="{{ route('stocks.edit', $stock->id) }}"
                                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-xs">Edit</a>
                                    <form action="{{ route('stocks.destroy', $stock->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus item stok ini? Ini tidak akan mengembalikan transaksi yang terkait.');">
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
