@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-[#073763] to-[#073763] px-6 py-8">
            <h1 class="text-2xl font-bold text-white">Buat Laporan Temuan (Petugas)</h1>
            <p class="text-[#C0C0C0] mt-2">Laporan yang dibuat akan otomatis disetujui.</p>
        </div>

        <form method="POST" action="{{ route('petugas.reports.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <input type="hidden" name="type" value="found">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Barang</label>
                    <input type="text" name="item_name" id="item_name" value="{{ old('item_name') }}" 
                           class="form-input border-[#073763] focus:ring-[#073763]" placeholder="Contoh: Kunci Motor Honda">
                    @error('item_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category_id" id="category_id" class="form-select border-[#073763] focus:ring-[#073763]">
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
                
                <div>
                    <label for="building_id" class="block text-sm font-medium text-gray-700 mb-2">Gedung</label>
                    <select name="building_id" id="building_id" class="form-select border-[#073763] focus:ring-[#073763]">
                        <option value="">Pilih Gedung</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="room_id" class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                    <select name="room_id" id="room_id" class="form-select border-[#073763] focus:ring-[#073763]">
                        <option value="">Pilih Ruangan</option>
                    </select>
                    @error('room_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kejadian</label>
                    <input type="date" name="event_date" id="event_date" value="{{ old('event_date') }}" 
                           class="form-input border-[#073763] focus:ring-[#073763]" max="{{ date('Y-m-d') }}">
                    @error('event_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">Foto (Opsional)</label>
                    <input type="file" name="photo" id="photo" accept="image/*" class="form-input border-[#073763] focus:ring-[#073763]">
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG. Maksimal 2MB.</p>
                    @error('photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Detail</label>
                <textarea name="description" id="description" rows="4" class="form-textarea border-[#073763] focus:ring-[#073763] w-full max-w-4xl resize-y"
                        placeholder="Jelaskan detail barang, ciri-ciri khusus, kondisi, dll.">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>


            <div class="flex justify-end space-x-4">
                <a href="{{ route('petugas.reports') }}" class="btn-outline border-[#741847] text-[#741847] hover:bg-[#741847] hover:text-white">Batal</a>
                <button type="submit" class="btn-primary bg-[#741847] hover:bg-[#5b132f]">Kirim Laporan</button>
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
