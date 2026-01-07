@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-8">
            <h1 class="text-2xl font-bold text-white">Edit Laporan (Petugas)</h1>
            <p class="text-indigo-100 mt-2">Perbarui detail untuk laporan: "{{ $report->item_name }}"</p>
        </div>

        {{-- ACTION FORM INI SUDAH DI-SET KHUSUS UNTUK PETUGAS --}}
        <form action="{{ Auth::user()->isAdmin() ? route('admin.reports.update', $report) : route('petugas.reports.update', $report) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Report Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Tipe Laporan</label>
                <div class="flex space-x-4">
                    {{-- Nilai form diisi dengan data lama dari $report --}}
                    <label class="flex items-center">
                        <input type="radio" name="type" value="lost" class="form-radio text-indigo-600" {{ old('type', $report->type) === 'lost' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Barang Hilang</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="type" value="found" class="form-radio text-indigo-600" {{ old('type', $report->type) === 'found' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Barang Temuan</span>
                    </label>
                </div>
                @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Item Name -->
                <div>
                    <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Barang</label>
                    <input type="text" name="item_name" id="item_name" value="{{ old('item_name', $report->item_name) }}" class="form-input">
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $report->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Building -->
                <div>
                    <label for="building_id" class="block text-sm font-medium text-gray-700 mb-2">Gedung</label>
                    <select name="building_id" id="building_id" class="form-select">
                         <option value="">Pilih Gedung</option>
                         @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ old('building_id', $report->room->building_id) == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Room -->
                <div>
                    <label for="room_id" class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                    <select name="room_id" id="room_id" class="form-select">
                        <option value="">Pilih Ruangan</option>
                    </select>
                     @error('room_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <!-- Event Date -->
                <div>
                    <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kejadian</label>
                    <input type="date" name="event_date" id="event_date" value="{{ old('event_date', $report->event_date->format('Y-m-d')) }}" class="form-input" max="{{ date('Y-m-d') }}">
                    @error('event_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Photo -->
                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">Ganti Foto (Opsional)</label>
                    <input type="file" name="photo" id="photo" accept="image/*" class="form-input">
                    @if($report->photo) <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ingin mengganti foto.</p> @endif
                    @error('photo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Detail</label>
                <textarea name="description" id="description" rows="4" class="form-textarea" placeholder="Jelaskan detail barang...">{{ old('description', $report->description) }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>           

            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ Auth::user()->isAdmin() ? route('admin.reports.validation') : route('petugas.reports') }}" class="btn-outline">
                    Batals
                </a>
                <button type="submit" class="btn-primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>
        
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buildingSelect = document.getElementById('building_id');
    const roomSelect = document.getElementById('room_id');
    const buildingsData = @json($buildings->keyBy('id')); 

    const oldRoomId = '{{ old('room_id', $report->room_id) }}';
    
    function updateRooms() {
        const buildingId = buildingSelect.value;
        roomSelect.innerHTML = '<option value="">Pilih Ruangan</option>';
        
        if (buildingId && buildingsData[buildingId] && buildingsData[buildingId].rooms) {
            buildingsData[buildingId].rooms.forEach(room => {
                const option = document.createElement('option');
                option.value = room.id;
                option.textContent = room.name;
                
                // JavaScript akan membandingkan '5' == 5 sebagai true, jadi ini aman
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