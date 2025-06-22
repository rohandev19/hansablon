<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Tambahkan ini di bagian atas

class RekeningAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rekening = Rekening::latest()->paginate(4);
        return view('admin.rekening.rekening', compact(['rekening']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Biasanya tidak digunakan jika form ada di halaman index
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Validasi semua input dari form
        $request->validate([
            'nama_rek' => 'required|string|max:255',
            'no_rek' => 'required|numeric',
            'atas_nama' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048', // Logo boleh kosong, tapi jika ada harus gambar
        ]);

        // 2. Siapkan data untuk disimpan
        $data = $request->only(['nama_rek', 'no_rek', 'atas_nama']);

        // 3. Handle upload file logo jika ada
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $nama_file = time() . "_" . $file->getClientOriginalName();

            // Pindahkan file ke folder public/images/bank
            $file->move(public_path('images/bank'), $nama_file);

            // Simpan nama file ke dalam data
            $data['logo'] = $nama_file;
        }

        // 4. Simpan data ke database
        Rekening::create($data);

        return back()->with('success', 'Berhasil Menambahkan Rekening Baru');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit_rek = Rekening::findOrFail($id); // Gunakan findOrFail untuk keamanan
        $rekening = Rekening::latest()->paginate(4);
        return view('admin.rekening.rekening_edit', compact(['edit_rek', 'rekening']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi semua input
        $request->validate([
            'nama_rek' => 'required|string|max:255',
            'no_rek' => 'required|numeric',
            'atas_nama' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        // 2. Cari data rekening yang akan diupdate
        $rekening = Rekening::findOrFail($id);
        $data = $request->only(['nama_rek', 'no_rek', 'atas_nama']);

        // 3. Handle upload logo baru jika ada
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($rekening->logo && File::exists(public_path('images/bank/' . $rekening->logo))) {
                File::delete(public_path('images/bank/' . $rekening->logo));
            }

            // Upload logo baru
            $file = $request->file('logo');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('images/bank'), $nama_file);
            $data['logo'] = $nama_file;
        }

        // 4. Update data di database
        $rekening->update($data);

        return to_route('rekening.index')->with('success', 'Berhasil Memperbarui Rekening');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rekening = Rekening::findOrFail($id);

        // Hapus file logo dari server jika ada
        if ($rekening->logo && File::exists(public_path('images/bank/' . $rekening->logo))) {
            File::delete(public_path('images/bank/' . $rekening->logo));
        }

        // Hapus data dari database
        $rekening->delete();

        return back()->with('delete', 'Berhasil Menghapus Rekening');
    }
}