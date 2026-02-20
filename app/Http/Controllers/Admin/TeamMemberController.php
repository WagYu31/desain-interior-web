<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    private function getPositions(): array
    {
        return [
            1 => 'President Director',
            2 => 'Director of Sales',
            3 => 'Director of Operational',
            4 => 'Media and Promotion',
            5 => 'Marketing Manager',
            6 => 'Business of Development',
            7 => 'Operational Manager',
            8 => 'Accounting Manager',
            9 => 'Head of Design',
            10 => 'Head of Drafter',
        ];
    }

    public function index()
    {
        $positions = $this->getPositions();
        $orderClause = "CASE ";
        foreach ($positions as $rank => $name) {
            $orderClause .= "WHEN position = '{$name}' THEN {$rank} ";
        }
        $orderClause .= "ELSE 99 END";
        $teamMembers = TeamMember::orderByRaw($orderClause)->paginate(10);

        return view('admin.team.index', compact('teamMembers'));
    }

    public function create()
    {
        $positions = $this->getPositions();
        return view('admin.team.create', compact('positions'));
    }

    public function store(Request $request)
    {
        // Tambahkan validasi untuk field baru
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|in:' . implode(',', $this->getPositions()),
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'linkedin_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'whatsapp_url' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $data = $validated; // Ambil semua data yang sudah divalidasi
        unset($data['photo']); // Hapus 'photo' karena itu file, bukan path

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('team-photos', 'public');
            $data['photo_path'] = $path;
        }

        TeamMember::create($data);

        return redirect()->route('admin.team-members.index')->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function edit(TeamMember $teamMember)
    {
        $positions = $this->getPositions();
        return view('admin.team.edit', compact('teamMember', 'positions'));
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        // Tambahkan validasi untuk field baru
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|in:' . implode(',', $this->getPositions()),
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:2048',
            'linkedin_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'whatsapp_url' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $data = $validated;
        unset($data['photo']);

        if ($request->filled('remove_photo')) {
            if ($teamMember->photo_path && Storage::disk('public')->exists($teamMember->photo_path)) {
                Storage::disk('public')->delete($teamMember->photo_path);
            }
            $data['photo_path'] = null;
        }

        if ($request->hasFile('photo')) {
            if ($teamMember->photo_path) {
                Storage::disk('public')->delete($teamMember->photo_path);
            }
            $path = $request->file('photo')->store('team-photos', 'public');
            $data['photo_path'] = $path;
        }

        $teamMember->update($data);

        return redirect()->route('admin.team-members.index')->with('success', 'Data anggota tim berhasil diperbarui.');
    }

    public function destroy(TeamMember $teamMember)
    {
        if ($teamMember->photo_path) {
            Storage::disk('public')->delete($teamMember->photo_path);
        }
        $teamMember->delete();
        return redirect()->route('admin.team-members.index')->with('success', 'Anggota tim berhasil dihapus.');
    }
}