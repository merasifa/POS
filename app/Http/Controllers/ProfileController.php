<?php
 
 namespace App\Http\Controllers;
 
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Facades\Storage;
 
 class ProfileController extends Controller
 {
     public function index()
     {
         $breadcrumb = (object) [
             'title' => 'Profil User',
             'list'  => ['Home', 'Profil']
         ];
 
         $page = (object) [
             'title' => 'Profil user yang sedang login'
         ];
 
         $activeMenu = 'profile'; // untuk menandai menu aktif di sidebar
 
         $user = Auth::user();
 
         return view('profile.index', [
             'breadcrumb' => $breadcrumb,
             'page'       => $page,
             'user'       => $user,
             'activeMenu' => $activeMenu
         ]);
     }
 
     public function updatePhoto(Request $request)
{
    $request->validate([
        'photo' => 'required|image|mimes:jpeg,jpg,png|max:2048',
    ]);

    $user = auth()->user();
    /** @var \App\Models\UserModel $user */

    // Hapus foto lama kalau ada
    if ($user->foto && Storage::exists('public/profile/' . $user->foto)) {
        Storage::delete('public/profile/' . $user->foto);
    }

    // Simpan foto baru
    $file = $request->file('photo');
    $filename = time() . '.' . $file->getClientOriginalExtension();
    $file->storeAs('public/profile', $filename);

    // Simpan nama file ke user
    $user->foto = $filename;
    $user->save();

    // Balikin responsenya — ini yang kamu tanyakan
    return response()->json([
        'message' => 'Foto profil berhasil diubah!',
        'photo_url' => asset('storage/profile/' . $filename) . '?' . time()
    ]);
}

 }