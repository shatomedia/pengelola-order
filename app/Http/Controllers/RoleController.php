<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role-edit', ['only' => ['update']]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required'],
                'permission' => ['nullable'],
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $role->name = $request->input('name');
            $role->save();

            $role->syncPermissions($request->input('permission'));

            // alert()->success('Success', 'Role Berhasil Tersimpan');
            return redirect()->back()->with('success', 'Role berhasil disimpan');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            // alert()->error('Oops', 'Data Error');
            return redirect()->back()->with('error', 'Error');
        }
        
    }
}
