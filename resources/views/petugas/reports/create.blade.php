@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-[#073763] to-[#741B47] px-6 py-8">
            <h1 class="text-2xl font-bold text-white">
                Buat Laporan Temuan
            </h1>
            <p class="text-white/80 mt-1">
                Laporan yang dibuat oleh petugas akan otomatis disetujui
            </p>
        </div>

        {{-- FORM --}}
        <form method="POST"
              action="{{ route('petugas.reports.store') }}"
              enctype="multipart/form-data"
              class="p-6 md:p-8 space-y-8">
            @csrf

            <input type="hidden" name="type" value="found">

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
                           placeholder="Contoh: Kunci Motor Honda"
                           class="w-full px-4 py-3 rounded-lg
                                  border border-gray-300
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
                            class="w-full px-4 py-3 rounded-lg
                                   border border-gray-300
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
                            class="w-full px-4 py-3 rounded-lg
                                   border border-gray-300
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
                            class="w-full px-4 py-3 rounded-lg
                                   border border-gray-300
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
                        Tanggal Ditemukan
                    </label>
                    <input type="date"
                           name="event_date"
                           value="{{ old('event_date') }}"
                           max="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-3 rounded-lg
                                  border border-gray-300
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
                           class="w-full px-4 py-2 rounded-lg
                                  border border-gray-300">
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
                          class="w-full px-4 py-3 rounded-lg
                                 border border-gray-300 resize-y
                                 focus:ring-2 focus:ring-primary focus:border-primary">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- ACTION --}}
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('petugas.reports') }}"
                   class="px-6 py-2 rounded-lg
                          border border-gray-300
                          text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 rounded-lg
                               bg-primary text-white
                               font-semibold shadow
                               hover:bg-primary/90 transition">
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
    
    // Konversi data dari PHP ke objek JavaScript yang mudah dicari
    const buildingsData = @json($buildings->keyBy('id')); 
    
    function updateRooms() {
        const buildingId = buildingSelect.value;
        const oldRoomId = '{{ old('room_id') }}';
        
        roomSelect.innerHTML = '<option value="">Pilih Ruangan</option>';
        
        if (buildingId && buildingsData[buildingId] && buildingsData[buildingId].rooms) {
            buildingsData[buildingId].rooms.forEach(room => {
                const option = document.createElement('option');
                option.value = room.id;
                option.textContent = room.name;
                
                if (room.id == oldRoomId) {
                    option.selected = true;
                }
                
                roomSelect.appendChild(option);
            });
        }
    }

    buildingSelect.addEventListener('change', updateRooms);
    
    if (buildingSelect.value) {
        updateRooms();
    }
});
</script>
@endsection
