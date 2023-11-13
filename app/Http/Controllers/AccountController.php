<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    public function index()
    {
        $title = 'Akun Saya';

        return view('account.index', compact('title'));
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpg,jpeg,png|max:1024',
            ]);

            $user = User::findOrFail(auth()->user()->id);

            if ($request->hasFile('photo') && $request->file('photo')->isValid('photo')) {
                $user->clearMediaCollection('photo');
                $user->addMediaFromRequest('photo')->toMediaCollection('photo');
            }

            alert()->success('Berhasil mengubah data akun', 'Sukses!');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Error!', 'Terjadi kesalahan saat mengubah data akun');
        }


        return redirect()->back();
    }
}
