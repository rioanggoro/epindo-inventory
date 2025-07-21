@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Barang Masuk</h1>

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

        {{-- Perhatikan method POST dan @method('PUT') --}}
        <form action="{{ route('incomings.update', $incoming->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Penting untuk HTTP PUT request --}}

            <div class="mb-4">
                <label for="item_name" class="block text-gray-700 text-sm font-bold mb-2">Nama Bahan Baku:</label>
                <input type="text" name="item_name" id="item_name"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ old('item_name', $incoming->item_name) }}" required>
            </div>
            <div class="mb-4">
                <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Jumlah:</label>
                <input type="number" name="quantity" id="quantity"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ old('quantity', $incoming->quantity) }}" required min="1">
            </div>
            <div class="mb-6">
                <label for="incoming_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Masuk:</label>
                <input type="date" name="incoming_date" id="incoming_date"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ old('incoming_date', $incoming->incoming_date) }}" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update
                </button>
                <a href="{{ route('incomings.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
