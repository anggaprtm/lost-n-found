<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = Building::withCount('rooms')->paginate(10);
        return view('admin.buildings.index', compact('buildings'));
    }

    public function create()
    {
        return view('admin.buildings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:buildings,name',
        ]);

        Building::create($request->all());

        return redirect()->route('admin.buildings.index')
            ->with('success', 'Gedung berhasil ditambahkan.');
    }

    public function edit(Building $building)
    {
        return view('admin.buildings.edit', compact('building'));
    }

    public function update(Request $request, Building $building)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:buildings,name,' . $building->id,
        ]);

        $building->update($request->all());

        return redirect()->route('admin.buildings.index')
            ->with('success', 'Gedung berhasil diperbarui.');
    }

    public function destroy(Building $building)
    {
        // Pastikan gedung yang dihapus tidak memiliki ruangan
        if ($building->rooms_count > 0) {
            return redirect()->route('admin.buildings.index')
                            ->with('error', 'Gedung tidak bisa dihapus karena memiliki ruangan.');
        }

        $building->delete();

        return redirect()->route('admin.buildings.index')
                        ->with('success', 'Gedung berhasil dihapus');
    }


}
