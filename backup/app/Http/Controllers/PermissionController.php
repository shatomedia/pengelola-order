<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permission', ['only' => ['index', 'store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Permission';
        $categories = Permission::select('category')->groupBy('category')->get();
        $permissions = Permission::orderBy('category', 'asc')
            ->paginate(10);

        return view('permission.index', compact('title', 'permissions', 'categories'));
    }

    public function store(Request $request)
    {
        try {

            $validasi_category = $request->kategori_baru ? 'nullable' : 'required';
            $validasi_kategori_baru = $request->category ? 'nullable' : 'required';

            $request->validate([
                'name' => 'required|unique:permissions,name',
                'category' => $validasi_category,
                'kategori_baru' => $validasi_kategori_baru,
            ]);

            Permission::create([
                'name' => $request->name,
                'category' => $request->category ?? $request->kategori_baru,
            ]);

            alert()->success('Success', 'Permission created successfully');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Error', 'Permission failed to create');
        }

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        try {
            $permission = Permission::findOrFail($id);

            $request->validate([
                'name' => 'required|unique:permissions,name,' . $permission->id,
                'category' => 'required',
            ]);

            $permission->update([
                'name' => $request->name,
                'category' => $request->category,
            ]);

            alert()->success('Success', 'Permission updated successfully');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Error', 'Permission failed to update');
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            alert()->success('Success', 'Permission deleted successfully');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Error', 'Permission failed to delete');
        }

        return redirect()->back();
    }
}
