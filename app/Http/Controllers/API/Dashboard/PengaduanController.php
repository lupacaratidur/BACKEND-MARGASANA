<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Pengaduan;
use App\Models\Tanggapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;


class PengaduanController extends Controller
{
    public function index()
    {
        $pengaduan = Pengaduan::orderBy('created_at', 'desc')->paginate(10);
        return response()->json([
            'title' => 'Semua Pengaduan',
            'pengaduan' => $pengaduan,
        ]);
    }

    public function belum(Request $request)
    {
        if (Auth::user()->level == 'masyarakat') {
            $pengaduan = Pengaduan::where('masyarakat_id', Auth::user()->id)->where('status', '0')->get();
        } elseif (Auth::user()->level == 'petugas') {
            $pengaduan = Pengaduan::where('status', '0')->get();
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'title' => 'Pengaduan Belum Ditanggapi',
            'pengaduan' => $pengaduan,
        ]);
    }

    public function proses()
    {
        if (auth()->user()->level == 'masyarakat') {
            $pengaduan = Pengaduan::where('masyarakat_id', auth()->user()->id)->where('status', 'proses')->get();
        } elseif (auth()->user()->level == 'petugas') {
            $pengaduan = Pengaduan::where('status', 'proses')->get();
        } else {
            return response()->json(['Unauthorized'], 403);
        }
        return response()->json([
            'title' => 'Pengaduan Diproses',
            'pengaduan' => $pengaduan
        ]);
    }

    public function selesai()
    {
        if (auth()->user()->level == 'masyarakat') {
            $pengaduan = Pengaduan::where('masyarakat_id', auth()->user()->id)->where('status', 'selesai')->get();
        } elseif (auth()->user()->level == 'petugas') {
            $pengaduan = Pengaduan::where('status', 'selesai')->get();
        } else {
            return response()->json(['Unauthorized'], 403);
        }
        return response()->json([
            'title' => 'Pengaduan Selesai',
            'pengaduan' => $pengaduan
        ]);
    }

    public function show(Pengaduan $pengaduan)
    {
        return view('pengaduan/detail', [
            'title' => 'Detil Pengaduan',
            'pengaduan' => $pengaduan
        ]);
    }

    public function create()
    {
        $this->authorize('masyarakat');
        return view('pengaduan/create', [
            'title' => 'Buat Pengaduan',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'isi_laporan' => 'required',
            'lampiran' => 'image|file|max:1024',
        ]);

        if ($request->file('lampiran')) {
            $validated['lampiran'] = $request->file('lampiran')->store('public/lampiran-laporan');
        }

        $validated['masyarakat_id'] = Auth::user()->id;

        if (Pengaduan::create($validated)) {
            return response()->json(['message' => 'Berhasil mengirim aduan!'], 200);
        } else {
            return response()->json(['message' => 'Gagal mengirim aduan!'], 500);
        }
    }

    public function edit($id)
    {
        $this->authorize('masyarakat');
        return view('pengaduan/edit', [
            'title' => 'Ubah Pengaduan',
            'pengaduan' => Pengaduan::findOrFail($id)
        ]);
    }

    public function update(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'isi_laporan' => 'required',
            'lampiran' => 'image|file|max:1024',
        ]);

        if ($request->file('lampiran')) {
            if ($request->oldLampiran) {
                Storage::delete($request->oldLampiran);
            }
            $validated['lampiran'] = $request->file('lampiran')->store('public/lampiran-laporan');
        }

        try {
            $pengaduan->update($validated);
            return response()->json(['message' => 'Berhasil mengubah aduan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengubah aduan!'], 500);
        }
    }


    public function destroy(Pengaduan $pengaduan)
    {
        try {
            if ($pengaduan->lampiran) {
                Storage::delete($pengaduan->lampiran);
            }
            $pengaduan->delete();
            return response()->json(['message' => 'Berhasil menghapus aduan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus aduan!'], 500);
        }
    }

    public function response(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'status' => 'required',
            'tanggapan' => 'required'
        ]);

        try {
            if ($validated['status'] !== $pengaduan->status) {
                Pengaduan::where('id', $pengaduan->id)->update(['status' => $validated['status']]);
                Tanggapan::create([
                    'pengaduan_id' => $pengaduan->id,
                    'tanggapan' => $request->tanggapan,
                    'status' => $validated['status'],
                    'petugas_id' => Auth::user()->id,
                ]);
                return response()->json(['message' => 'Berhasil memberi tanggapan!'], 200);
            } else {
                return response()->json(['message' => 'Gagal memberi tanggapan!'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memberi tanggapan!'], 500);
        }
    }
}
