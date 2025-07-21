@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Produksi</h1>

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

        <form action="{{ route('produksis.update', $produksi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="product_name" class="block text-gray-700 text-sm font-bold mb-2">Nama Produk Jadi:</label>
                <input type="text" name="product_name" id="product_name"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ old('product_name', $produksi->product_name) }}" required>
            </div>
            <div class="mb-4">
                <label for="quantity_produced" class="block text-gray-700 text-sm font-bold mb-2">Jumlah Produk
                    Dihasilkan:</label>
                <input type="number" name="quantity_produced" id="quantity_produced"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ old('quantity_produced', $produksi->quantity_produced) }}" required min="1">
            </div>
            <div class="mb-4">
                <label for="raw_material_item_name" class="block text-gray-700 text-sm font-bold mb-2">Bahan Baku yang
                    Digunakan:</label>
                <select name="raw_material_item_name" id="raw_material_item_name"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                    <option value="">Pilih Bahan Baku</option>
                    @foreach ($rawMaterials as $material)
                        <option value="{{ $material->item_name }}"
                            {{ old('raw_material_item_name', $produksi->raw_material_item_name ?? '') == $material->item_name ? 'selected' : '' }}>
                            {{ $material->item_name }} (Stok: {{ $material->current_stock }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="raw_material_used" class="block text-gray-700 text-sm font-bold mb-2">Jumlah Bahan Baku
                    Digunakan:</label>
                <input type="number" name="raw_material_used" id="raw_material_used"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ old('raw_material_used', $produksi->raw_material_used) }}" required min="1">
            </div>
            <div class="mb-6">
                <label for="production_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Produksi:</label>
                <input type="date" name="production_date" id="production_date"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ old('production_date', $produksi->production_date) }}" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update
                </button>
                <a href="{{ route('produksis.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
