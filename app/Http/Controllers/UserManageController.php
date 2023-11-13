<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class UserManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-management', ['only' => ['index', 'store']]);
        $this->middleware('permission:user-management-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-management-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-management-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'User Management';
        $users = User::get();
        $roles = Role::withCount('modelHasRoles')->get();
        $categories = Permission::select('category')->distinct()->get();
        $listPermissions = [];
        foreach ($categories as $category) {
            $listPermissions[$category->category] = Permission::where('category', $category->category)->get();
        }
        return view('user_management.index', compact('title', 'users', 'roles', 'listPermissions', 'categories'));
    }

    public function store()
    {
        try {
            $data = request()->validate([
                'name' => 'required',
                'email' => 'required|unique:users',
                'role_id' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);

            $data['password'] = bcrypt($data['password']);

            $user = User::create($data);
            $user->assignRole($data['role_id']);

            alert()->success('Success', 'User berhasil ditambahkan');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Error', 'User gagal ditambahkan');
        }

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        try {
            $data = request()->validate([
                'name' => 'required',
                'email' => 'required|unique:users,email,' . $id,
                'role_id' => 'required',
                'password' => 'nullable|min:8|confirmed',
            ]);

            if ($request->password) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }

            $user = User::findOrFail($id);
            $user->update($data);
            $user->syncRoles($data['role_id']);

            alert()->success('Success', 'User berhasil diupdate');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Error', 'User gagal diupdate');
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            alert()->success('Success', 'User berhasil dihapus');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Error', 'User gagal dihapus');
        }

        return redirect()->back();
    }
}
