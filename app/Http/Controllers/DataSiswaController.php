<?php

namespace App\Http\Controllers;

use App\Models\DataSiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DataSiswaController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('tingkat')->get();

        $siswas = DataSiswa::with('kelasRelasi')
            ->when($request->kelas_id, function ($q) use ($request) {

    if ($request->kelas_id === 'belum') {
        $q->whereNull('kelas_id');
    } else {
        $q->where('kelas_id', $request->kelas_id);
    }

})

            ->get();

        return view('siswa.data', compact('siswas', 'kelasList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nisn'     => 'required',
            'nama'     => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        $data = DataSiswa::findOrFail($id);

        $data->update([
            'nisn'     => $request->nisn,
            'nama'     => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas_id' => $request->kelas_id,
        ]);

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil diupdate');
    }

    public function destroy($id)
    {
        DataSiswa::findOrFail($id)->delete();

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil dihapus');
    }
    
    public function bulkUpdate(Request $request)
{
    $request->validate([
        'kelas_id' => 'required|exists:kelas,id',
        'filter_kelas_id' => 'required'
    ]);

    $filter = $request->filter_kelas_id;

    $query = DataSiswa::query();

    if ($filter === 'belum') {
        $query->whereNull('kelas_id');
    } else {
        $query->where('kelas_id', $filter);
    }

    $updated = $query->update([
        'kelas_id' => $request->kelas_id
    ]);

    return back()->with('success', $updated . ' siswa berhasil diupdate.');
}

}
