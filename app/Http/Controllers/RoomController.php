<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Building;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with('building');
        
        if ($request->has('building') && $request->building) {
            $query->where('building_id', $request->building);
        }
        
        $rooms = $query->paginate(10);
        
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $buildings = Building::all();
        return view('admin.rooms.create', compact('buildings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'building_id' => 'required|exists:buildings,id',
        ]);

        Room::create($request->all());

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit(Room $room)
    {
        $buildings = Building::all();
        return view('admin.rooms.edit', compact('room', 'buildings'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'building_id' => 'required|exists:buildings,id',
        ]);

        $room->update($request->all());

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        // Pastikan jika ruangan ini tidak memiliki laporan yang terkait
        if ($room->reports()->count() > 0) {
            return redirect()->route('admin.rooms.index')
                            ->with('error', 'Ruangan tidak bisa dihapus karena masih memiliki laporan.');
        }

        $room->delete();
        return redirect()->route('admin.rooms.index')
                        ->with('success', 'Ruangan berhasil dihapus');
    }
}
