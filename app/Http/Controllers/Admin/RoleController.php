<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VaiTro;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(Request $request)
    {
        $query = VaiTro::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('ten_vai_tro', 'LIKE', "%{$search}%");
        }

        $roles = $query->orderBy('ma_vai_tro', 'desc')->paginate(15)->appends($request->query());

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten_vai_tro' => 'required|string|max:100|unique:vai_tro,ten_vai_tro',
        ], [
            'ten_vai_tro.required' => 'Tên vai trò là bắt buộc.',
            'ten_vai_tro.unique' => 'Tên vai trò đã tồn tại.',
            'ten_vai_tro.max' => 'Tên vai trò không được quá 100 ký tự.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        VaiTro::create([
            'ten_vai_tro' => $request->ten_vai_tro,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Tạo vai trò mới thành công!');
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit($id)
    {
        $role = VaiTro::findOrFail($id);
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, $id)
    {
        $role = VaiTro::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'ten_vai_tro' => 'required|string|max:100|unique:vai_tro,ten_vai_tro,' . $id . ',ma_vai_tro',
        ], [
            'ten_vai_tro.required' => 'Tên vai trò là bắt buộc.',
            'ten_vai_tro.unique' => 'Tên vai trò đã tồn tại.',
            'ten_vai_tro.max' => 'Tên vai trò không được quá 100 ký tự.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $role->update(['ten_vai_tro' => $request->ten_vai_tro]);

        return redirect()->route('admin.roles.index')->with('success', 'Cập nhật vai trò thành công!');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy($id)
    {
        $role = VaiTro::findOrFail($id);

        // Prevent deleting role if associated with users
        if ($role->taiKhoans()->count() > 0) {
            return back()->with('error', 'Không thể xóa vai trò đang được sử dụng bởi người dùng.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Xóa vai trò thành công!');
    }
}
