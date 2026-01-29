@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-[#04223b] to-[#741B47] px-6 py-8">
            <h1 class="text-2xl font-bold text-white">
                Buat Laporan Baru
            </h1>
            <p class="text-white/80 mt-1">
                Laporkan barang hilang atau barang temuan dengan lengkap
            </p>
        </div>

        {{-- FORM --}}
        <form method="POST"
              action="{{ route('reports.store') }}"
              enctype="multipart/form-data"
              class="p-6 md:p-8 space-y-8">
            @csrf

            {{-- TYPE --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    Tipe Laporan
                </label>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label
                        class="flex items-center gap-3 p-4 rounded-xl border cursor-pointer
                               transition
                               {{ old('type') === 'lost'
                                    ? 'border-red-500 bg-red-50'
                                    : 'border-gray-200 hover:bg-gray-50' }}">
                        <input type="radio" name="type" value="lost"
                               class="text-red-600"
                               {{ old('type') === 'lost' ? 'checked' : '' }}>
                        <div>
                            <p class="font-semibold text-gray-800">Barang Hilang</p>
                            <p class="text-xs text-gray-500">
                                Melaporkan barang milik Anda yang hilang
                            </p>
                        </div>
                    </label>

                    <label
                        class="flex items-center gap-3 p-4 rounded-xl border cursor-pointer
                               transition
                               {{ old('type') === 'found'
                                    ? 'border-green-500 bg-green-50'
                                    : 'border-gray-200 hover:bg-gray-50' }}">
                        <input type="radio" name="type" value="found"
                               class="text-green-600"
                               {{ old('type') === 'found' ? 'checked' : '' }}>
                        <div>
                            <p class="font-semibold text-gray-800">Barang Temuan</p>
                            <p class="text-xs text-gray-500">
                                Melaporkan barang yang Anda temukan
                            </p>
                        </div>
                    </label>
                </div>

                @error('type')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- ITEM NAME --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Barang
                    </label>
                    <input type="text"
                           name="item_name"
                           value="{{ old('item_name') }}"
                           placeholder="Contoh: iPhone 13 Pro"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300
                                  focus:ring-2 focus:ring-primary focus:border-primary">
                    @error('item_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- CATEGORY --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori
                    </label>
                    <select name="category_id"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300
                                   focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- BUILDING --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Gedung
                    </label>
                    <select name="building_id" id="building_id"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300
                                   focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Pilih Gedung</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}"
                                {{ old('building_id') == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ROOM --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ruangan
                    </label>
                    <select name="room_id" id="room_id"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300
                                   focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Pilih Ruangan</option>
                    </select>
                    @error('room_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DATE --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Kejadian
                    </label>
                    <input type="date"
                           name="event_date"
                           value="{{ old('event_date') }}"
                           max="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300
                                  focus:ring-2 focus:ring-primary focus:border-primary">
                    @error('event_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PHOTO --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Foto (Opsional)
                    </label>
                    <input type="file"
                           name="photo"
                           accept="image/*"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300">
                    <p class="mt-1 text-xs text-gray-500">
                        JPG / PNG, maksimal 2MB
                    </p>
                    @error('photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Detail
                </label>
                <textarea name="description"
                          rows="4"
                          placeholder="Ciri-ciri barang, kondisi, warna, dan detail lain..."
                          class="w-full px-4 py-3 rounded-lg border border-gray-300
                                 focus:ring-2 focus:ring-primary focus:border-primary">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- ACTION --}}
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('reports.index') }}"
                   class="px-6 py-2 rounded-lg border border-gray-300
                          text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 rounded-lg
                               bg-primary text-white
                               font-semibold shadow hover:bg-primary/90 transition">
                    Kirim Laporan
                </button>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buildingSelect = document.getElementById('building_id');
    const roomSelect = document.getElementById('room_id');
    
    const buildings = @json($buildings);
    
    buildingSelect.addEventListener('change', function() {
        const buildingId = this.value;
        roomSelect.innerHTML = '<option value="">Pilih Ruangan</option>';
        
        if (buildingId) {
            const building = buildings.find(b => b.id == buildingId);
            if (building && building.rooms) {
                building.rooms.forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.id;
                    option.textContent = room.name;
                    if ({{ old('room_id', 0) }} == room.id) {
                        option.selected = true;
                    }
                    roomSelect.appendChild(option);
                });
            }
        }
    });
    
    // Trigger change event if building is already selected
    if (buildingSelect.value) {
        buildingSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
