<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return view('setting.index', [
            'setting' => Setting::first()
        ]);
    }

    public function show()
    {
        //
    }

    public function update(Request $request)
    {
        //Validasi request
        $request->validate([
            'nama_perusahaan' => 'required',
            'telepon' => 'required',
            'alamat' => 'required',
            'diskon' => 'required|numeric',
            'path_logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048' //Tambahkan validasi
        ]);

        $setting = Setting::first();

        $setting->nama_perusahaan = $request->nama_perusahaan;
        $setting->telepon = $request->telepon;
        $setting->alamat = $request->alamat;
        $setting->diskon = $request->diskon;

        if ($request->hasFile('path_logo')) {
            //Hapus logo lama jika ada
            if ($setting->path_logo && Storage::disk('public')->exists(str_replace('/storage', '', $setting->path_logo))) {
                Storage::disk('public')->delete(str_replace('/storage', '', $setting->path_logo));
            }

            //Simpan gambar baru menggunakan storage facade
            $path = $request->file('path_logo')->store('logo', 'public');

            //Simpan path yang dapat diakses oleh helper asset()
            $setting->path_logo = Storage::url($path);
        }

        if ($request->hasFile('path_kartu_member')) {
            $file = $request->file('path_kartu_member');
            $nama = 'logo-' . date('Y-m-dHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/img'), $nama);

            $setting->path_kartu_member = "/img/$nama";
        }

        $setting->update();

        return redirect()->route('setting.index')->with('success', 'Pengaturan berhasil diperbarui');
    }
}
