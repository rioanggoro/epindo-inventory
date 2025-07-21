@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Daftar Barang Masuk</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <a href="{{ route('incomings.create') }}"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Tambah Barang
            Masuk Baru</a>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Nama Barang</th>
                        <th class="py-3 px-6 text-left">Jumlah</th>
                        <th class="py-3 px-6 text-left">Tanggal Masuk</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    @foreach ($incomings as $incoming)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $incoming->id }}</td>
                            <td class="py-3 px-6 text-left">{{ $incoming->item_name }}</td>
                            <td class="py-3 px-6 text-left">{{ $incoming->quantity }}</td>
                            <td class="py-3 px-6 text-left">{{ $incoming->incoming_date }}</td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center space-x-2">
                                    <a href="{{ route('incomings.edit', $incoming->id) }}"
                                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-xs">Edit</a>
                                    <form action="{{ route('incomings.destroy', $incoming->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE') {{-- Method Spoofing untuk DELETE --}}
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
