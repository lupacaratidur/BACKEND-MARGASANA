<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'title' => 'Data Pengguna',
            'page'  => 'pengguna',
            'users' => User::all(),
        ]);
    }

    public function masyarakat()
    {
        return response()->json([
            'title' => 'Data Masyarakat',
            'page'  => 'masyarakat',
            'users' => User::where('level', 'masyarakat')->get(),
        ]);
    }

    public function petugas()
    {
        return response()->json([
            'title' => 'Data Petugas',
            'page'  => 'petugas',
            'users' => User::where('level', '!=', 'masyarakat')->get(),

        ]);
    }

    public function edit()
    {
    }

    public function create()
    {
        return view('pengguna/create', [
            'title' => 'Tambah Petugas',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:6|unique:users',
            'nama' => 'required',
            'telepon' => 'required|min:11',
            'password' => 'required|min:6',
            'level' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan pengguna!',
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);

        $berhasil = User::create($data);

        if ($berhasil) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan pengguna!',
                'data' => $berhasil,
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan pengguna!',
            ], 500);
        }
    }

    public function update(Request $request, User $pengguna)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:6|unique:users,username,' . $pengguna->id,
            'nama' => 'required',
            'telepon' => 'required|min:11',
            'level' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengedit pengguna!',
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = $request->only(['username', 'nama', 'telepon', 'level']);

        $berhasil = $pengguna->update($data);

        if ($berhasil) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengedit pengguna!',
                'data' => $pengguna,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengedit pengguna!',
            ], 500);
        }
    }


    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan!',
            ], 404);
        }

        if ($user->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus pengguna!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pengguna!',
            ], 500);
        }
    }
}
