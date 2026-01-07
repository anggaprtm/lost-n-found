@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8">
            <h1 class="text-2xl font-bold text-white">Buat Laporan Baru</h1>
            <p class="text-blue-100 mt-2">Laporkan barang hilang atau temuan Anda</p>
        </div>

        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Report Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Tipe Laporan</label>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="type" value="lost" class="form-radio text-blue-600" {{ old('type') === 'lost' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Barang Hilang</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="type" value="found" class="form-radio text-blue-600" {{ old('type') === 'found' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Barang Temuan</span>
                    </label>
                </div>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Item Name -->
                <div>
                    <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Barang</label>
                    <input type="text" name="item_name" id="item_name" value="{{ old('item_name') }}" 
                           class="form-input" placeholder="Contoh: iPhone 13 Pro">
                    @error('item_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Building -->
                <div>
                    <label for="building_id" class="block text-sm font-medium text-gray-700 mb-2">Gedung</label>
                    <select name="building_id" id="building_id" class="form-select">
                        <option value="">Pilih Gedung</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
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
                    @error('room_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Date -->
                <div>
                    <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kejadian</label>
                    <input type="date" name="event_date" id="event_date" value="{{ old('event_date') }}" 
                           class="form-input" max="{{ date('Y-m-d') }}">
                    @error('event_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Photo -->
                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">Foto (Opsional)</label>
                    <input type="file" name="photo" id="photo" accept="image/*" class="form-input">
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG. Maksimal 2MB.</p>
                    @error('photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Detail</label>
                <textarea name="description" id="description" rows="4" class="form-textarea" 
                          placeholder="Jelaskan detail barang, ciri-ciri khusus, kondisi, dll.">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('reports.index') }}" class="btn-outline">Batal</a>
                <button type="submit" class="btn-primary">Kirim Laporan</button>
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
