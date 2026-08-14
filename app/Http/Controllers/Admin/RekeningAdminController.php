<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RekeningAdminController extends Controller
{
    public function index()
    {
        $rekening = Rekening::latest()->paginate(4);
        return view('admin.rekening.rekening', compact(['rekening']));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_rek' => 'required|string|max:255',
            'no_rek' => 'required|numeric',
            'atas_nama' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        $data = $request->only(['nama_rek', 'no_rek', 'atas_nama']);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            // SECURITY FIRST: Safe File Naming to prevent path traversal
            $nama_file = time() . "-" . Str::random(10) . "." . $file->extension();
            $file->move(public_path('images/bank'), $nama_file);
            $data['logo'] = $nama_file;
        }

        Rekening::create($data);

        return back()->with('success', 'Berhasil Menambahkan Rekening Baru');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $edit_rek = Rekening::findOrFail($id);
        $rekening = Rekening::latest()->paginate(4);
        return view('admin.rekening.rekening_edit', compact(['edit_rek', 'rekening']));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_rek' => 'required|string|max:255',
            'no_rek' => 'required|numeric',
            'atas_nama' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        $rekening = Rekening::findOrFail($id);
        $data = $request->only(['nama_rek', 'no_rek', 'atas_nama']);

        if ($request->hasFile('logo')) {
            if ($rekening->logo && File::exists(public_path('images/bank/' . $rekening->logo))) {
                File::delete(public_path('images/bank/' . $rekening->logo));
            }

            $file = $request->file('logo');
            // SECURITY FIRST: Safe File Naming to prevent path traversal
            $nama_file = time() . "-" . Str::random(10) . "." . $file->extension();
            $file->move(public_path('images/bank'), $nama_file);
            $data['logo'] = $nama_file;
        }

        $rekening->update($data);

        return to_route('rekening.index')->with('success', 'Berhasil Memperbarui Rekening');
    }

    public function destroy($id)
    {
        $rekening = Rekening::findOrFail($id);

        if ($rekening->logo && File::exists(public_path('images/bank/' . $rekening->logo))) {
            File::delete(public_path('images/bank/' . $rekening->logo));
        }

        $rekening->delete();

        return back()->with('delete', 'Berhasil Menghapus Rekening');
    }
}