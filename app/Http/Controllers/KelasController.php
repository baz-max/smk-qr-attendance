<?php
namespace App\Http\Controllers;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
   public function index(Request $request)
{
    $kelas = Kelas::withCount('siswa')
        ->when($request->search, function ($q) use ($request) {
            $q->where('nama_kelas', 'like', '%'.$request->search.'%');
        })
        ->when($request->tingkat, function ($q) use ($request) {
            $q->where('tingkat', $request->tingkat);
        })
        ->orderBy('tingkat')
        ->orderBy('nama_kelas')
        ->get();

    return view('kelas.index', compact('kelas'));
}


    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required',
            'tingkat' => 'required'
        ]);

        Kelas::create($request->all());

        return redirect()->back()->with('success','Kelas ditambahkan');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'nama_kelas' => 'required',
        'tingkat' => 'required'
    ]);

    Kelas::findOrFail($id)->update([
        'nama_kelas' => $request->nama_kelas,
        'tingkat' => $request->tingkat
    ]);

    return back()->with('success', 'Data kelas berhasil diupdate');
}


    public function show(Kelas $kelas)
    {
        $kelas->load('siswa');
        return view('kelas.partials.detail', compact('kelas'));
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->back();
    }
    
}
