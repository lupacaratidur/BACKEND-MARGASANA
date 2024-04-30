<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PengajuanSuratController extends Controller
{

    public function index()
    {

        if (Auth::user()->level == 'masyarakat') {
            $pengajuan_saya = PengajuanSurat::with('masyarakat')->whereMasyarakatId(Auth::user()->id)->get();
        } else {
            $pengajuan_saya = PengajuanSurat::with('masyarakat')->get();
        }

        return response()->json([
            'title' => 'Pengajuan Surat Online',
            'pengajuan_saya' => $pengajuan_saya
        ]);
    }

    public function create(Request $request)
    {

        if ($request->surat == 'keterangan') {
            return view('pengajuan_surat.form_surat_keterangan', [
                'title' => 'Pengajuan Surat Keterangan',
            ]);
        } elseif ($request->surat === 'kelahiran') {
            return view('pengajuan_surat.form_surat_kelahiran', [
                'title' => 'Pengajuan Surat Keterangan Kelahiran',
            ]);
        } elseif ($request->surat === 'kematian') {
            return view('pengajuan_surat.form_surat_kematian', [
                'title' => 'Pengajuan Surat Keterangan Kematian',
            ]);
        } elseif ($request->surat === 'keterangan_usaha') {
            return view('pengajuan_surat.form_surat_keterangan_usaha', [
                'title' => 'Pengajuan Surat Keterangan Usaha',
            ]);
        } elseif ($request->surat === 'keterangan_domisili') {
            return view('pengajuan_surat.form_surat_keterangan_domisili', [
                'title' => 'Pengajuan Surat Keterangan Domisili',
            ]);
        } elseif ($request->surat === 'keterangan_tidak_mampu') {
            return view('pengajuan_surat.form_surat_keterangan_tidak_mampu', [
                'title' => 'Pengajuan Surat Keterangan Tidak Mampu',
            ]);
        } elseif ($request->surat === 'keterangan_izin_penelitian') {
            return view('pengajuan_surat.form_surat_keterangan_izin_penelitian', [
                'title' => 'Pengajuan Surat Keterangan Izin Penelitian',
            ]);
        } else {
            return redirect()->route('pengajuan-surat.index');
        }
    }


    public function store(Request $request)
    {
        $data = [];
        $response = [];

        // Validasi jenis surat dan data sesuai dengan jenis surat yang dipilih
        switch ($request->jenis_surat) {
            case 'keterangan':
                $request->validate([
                    'nama' => 'required',
                    'tempat_lahir' => 'required',
                    'ttl' => 'required',
                    'nik' => 'required',
                    'status' => 'required',
                    'negara_agama' => 'required',
                    'pekerjaan' => 'required',
                    'alamat' => 'required',
                    'keperluan' => 'required',
                    'keterangan_surat' => 'required',
                    'foto_ktp' => 'nullable|image|max:2048'
                ]);
                $data = $request->except('_token');
                $data['jenis_surat'] = 'Surat Keterangan';
                break;

            case 'kelahiran':
                $request->validate([
                    'kepala_keluarga' => 'required',
                    'no_kk' => 'required',
                    //DATA ANAK
                    'nama_bayi' => 'required',
                    'kelamin_bayi' => 'required',
                    'tempat_lahir' => 'required',
                    'lokasi_lahir' => 'required',
                    'hari_lahir' => 'required',
                    'tgl_lahir_bayi' => 'required',
                    'bln_lahir_bayi' => 'required',
                    'thn_lahir_bayi' => 'required',
                    'lahir_pukul' => 'required',
                    // 'anak_ke' => 'required',
                    'jenis_lahir' => 'required',
                    'lahiran_ke' => 'required',
                    'penolong_lahir' => 'required',
                    'berat_bayi' => 'required',
                    'panjang_bayi' => 'required',

                    // //DATA IBU
                    'nik_ibu' => 'required',
                    'nama_ibu' => 'required',
                    'tgl_lahir_ibu' => 'required',
                    'bln_lahir_ibu' => 'required',
                    'thn_lahir_ibu' => 'required',
                    'umur_ibu' => 'required',
                    'pekerjaan_ibu' => 'required',
                    'alamat_ibu' => 'required',
                    'kewarganegaraan_ibu' => 'required',
                    'kebangsaan_ibu' => 'required',
                    'tgl_nikah' => 'required',
                    'bln_nikah' => 'required',
                    'thn_nikah' => 'required',

                    // // //DATA AYAH
                    'nik_ayah' => 'required',
                    'nama_ayah' => 'required',
                    'tgl_lahir_ayah' => 'required',
                    'bln_lahir_ayah' => 'required',
                    'thn_lahir_ayah' => 'required',
                    'umur_ayah' => 'required',
                    'alamat_ayah' => 'required',
                    'kewarganegaraan_ayah' => 'required',
                    'kebangsaan_ayah' => 'required',

                    // // //DATA PELAPOR
                    'nik_pelapor' => 'required',
                    'nama_pelapor' => 'required',
                    'umur_pelapor' => 'required',
                    'kelamin_pelapor' => 'required',
                    'pekerjaan_pelapor' => 'required',
                    'alamat_pelapor' => 'required',

                    // // //DATA SAKSI I
                    'nik_saksi1' => 'required',
                    'nama_saksi1' => 'required',
                    'umur_saksi1' => 'required',
                    // 'kelamin_saksi1' => 'required',
                    'pekerjaan_saksi1' => 'required',
                    'alamat_saksi1' => 'required',

                    // //DATA SAKSI II
                    'nik_saksi2' => 'required',
                    'nama_saksi2' => 'required',
                    'umur_saksi2' => 'required',
                    // 'kelamin_saksi2' => 'required',
                    'pekerjaan_saksi2' => 'required',
                    'alamat_saksi2' => 'required',
                ]);
                $data = $request->except('_token');
                $data['jenis_surat'] = 'Surat Kelahiran';
                break;

            case 'kematian':
                $request->validate([
                    'kepala_keluarga_jenazah' => 'required',
                    'no_kk_jenazah' => 'required',
                    // //DATA JENAZAH
                    'nik_jenazah' => 'required',
                    'nama_jenazah' => 'required',
                    'kelamin_jenazah' => 'required',
                    'tgl_lahir_jenazah' => 'required',
                    'bln_lahir_jenazah' => 'required',
                    'thn_lahir_jenazah' => 'required',
                    'umur_jenazah' => 'required',
                    'tempat_lahir_jenazah' => 'required',
                    'agama_jenazah' => 'required',
                    'pekerjaan_jenazah' => 'required',
                    'alamat_jenazah' => 'required',
                    'anak_ke_jenazah' => 'required',
                    'tgl_mati_jenazah' => 'required',
                    'bln_mati_jenazah' => 'required',
                    'thn_mati_jenazah' => 'required',
                    'pukul_jenazah' => 'required',
                    'sebab_mati_jenazah' => 'required',
                    'tempat_mati_jenazah' => 'required',
                    'yang_menerangkan_jenazah' => 'required',

                    // // // //DATA AYAH
                    'nik_ayah_jenazah' => 'required',
                    'nama_ayah_jenazah' => 'required',
                    'tgl_lahir_ayah_jenazah' => 'required',
                    'bln_lahir_ayah_jenazah' => 'required',
                    'thn_lahir_ayah_jenazah' => 'required',
                    'umur_ayah_jenazah' => 'required',
                    'pekerjaan_ayah_jenazah' => 'required',
                    'alamat_ayah_jenazah' => 'required',

                    // // //DATA IBU
                    'nik_ibu_jenazah' => 'required',
                    'nama_ibu_jenazah' => 'required',
                    'tgl_lahir_ibu_jenazah' => 'required',
                    'bln_lahir_ibu_jenazah' => 'required',
                    'thn_lahir_ibu_jenazah' => 'required',
                    'umur_ibu_jenazah' => 'required',
                    'pekerjaan_ibu_jenazah' => 'required',
                    'alamat_ibu_jenazah' => 'required',

                    // // // //DATA PELAPOR
                    'nik_pelapor_jenazah' => 'required',
                    'nama_pelapor_jenazah' => 'required',
                    'tgl_lahir_pelapor_jenazah' => 'required',
                    'bln_lahir_pelapor_jenazah' => 'required',
                    'thn_lahir_pelapor_jenazah' => 'required',
                    'umur_pelapor_jenazah' => 'required',
                    'kelamin_pelapor_jenazah' => 'required',
                    'pekerjaan_pelapor_jenazah' => 'required',
                    'alamat_pelapor_jenazah' => 'required',


                    // // // //DATA SAKSI I
                    'nik_saksi1_jenazah' => 'required',
                    'nama_saksi1_jenazah' => 'required',
                    'tgl_lahir_saksi1_jenazah' => 'required',
                    'bln_lahir_saksi1_jenazah' => 'required',
                    'thn_lahir_saksi1_jenazah' => 'required',
                    'umur_saksi1_jenazah' => 'required',
                    'pekerjaan_saksi1_jenazah' => 'required',
                    'alamat_saksi1_jenazah' => 'required',

                    // // //DATA SAKSI II
                    'nik_saksi2_jenazah' => 'required',
                    'nama_saksi2_jenazah' => 'required',
                    'tgl_lahir_saksi2_jenazah' => 'required',
                    'bln_lahir_saksi2_jenazah' => 'required',
                    'thn_lahir_saksi2_jenazah' => 'required',
                    'umur_saksi2_jenazah' => 'required',
                    'pekerjaan_saksi2_jenazah' => 'required',
                    'alamat_saksi2_jenazah' => 'required',
                ]);
                $data = $request->except('_token');
                $data['jenis_surat'] = 'Surat Kematian';
                break;

            case 'keterangan_usaha':
                $request->validate([
                    'nama' => 'required',
                    'tempat_lahir' => 'required',
                    'ttl' => 'required',
                    'negara_agama' => 'required',
                    'alamat' => 'required',
                    'nik' => 'required',
                    'lama_usaha' => 'required',
                    'nama_usaha' => 'required',
                    'foto_ktp' => 'nullable|image|max:2048'
                ]);
                $data = $request->except('_token');
                $data['jenis_surat'] = 'Surat Keterangan Usaha';
                break;

            case 'keterangan_domisili':
                $request->validate([
                    'nama' => 'required',
                    'tempat_lahir' => 'required',
                    'ttl' => 'required',
                    'kelamin' => 'required',
                    'pekerjaan' => 'required',
                    'status' => 'required',
                    'agama' => 'required',
                    'foto_ktp' => 'nullable|image|max:2048'
                ]);
                $data = $request->except('_token');
                $data['jenis_surat'] = 'Surat Keterangan Domisili';
                break;

            case 'keterangan_tidak_mampu':
                $request->validate([
                    'nama' => 'required',
                    'tempat_lahir' => 'required',
                    'ttl' => 'required',
                    'alamat' => 'required',
                    'nik' => 'required',
                    'foto_ktp' => 'nullable|image|max:2048'
                ]);
                $data = $request->except('_token');
                $data['jenis_surat'] = 'Surat Keterangan Tidak Mampu';
                break;

            case 'keterangan_izin_penelitian':
                $request->validate([
                    'nama' => 'required',
                    'nim' => 'required',
                    'univ' => 'required',
                    'prodi' => 'required',
                    'judul_penelitian' => 'required',
                    'awal_penelitian' => 'required',
                    'akhir_penelitian' => 'required',
                    'foto_ktp' => 'nullable|image|max:2048'
                ]);
                $data = $request->except('_token');
                $data['jenis_surat'] = 'Surat Keterangan Izin Penelitian';
                break;

            default:
                return response()->json(['error' => 'Jenis surat tidak valid'], 400);
        }

        // Lakukan penyimpanan data jika validasi berhasil
        try {
            $foto_ktp = $request->file('foto_ktp')->store('pengajuan_surat', 'public');

            PengajuanSurat::create([
                'masyarakat_id' => Auth::user()->id,
                'jenis_surat' => $data['jenis_surat'],
                'surat' => json_encode($data),
                'foto_ktp' => $foto_ktp,
                'status' => 'Pending'
            ]);

            $response['message'] = 'Berhasil membuat pengajuan surat';
            $response['status'] = 'success';
        } catch (\Exception $e) {
            $response['message'] = 'Gagal membuat pengajuan surat';
            $response['status'] = 'error';
        }

        return response()->json($response);
    }



    public function show(PengajuanSurat $pengajuanSurat)
    {
        if (Auth::user()->level == 'masyarakat') {
            if (Auth::user()->id == $pengajuanSurat->masyarakat_id) {
                return response()->json([
                    'title' => 'Detail Pengajuan Surat',
                    'pengajuan_surat' => $pengajuanSurat
                ]);
            } else {
                return redirect('/');
            }
        } else {
            return response()->json([
                'title' => 'Detail Pengajuan Surat',
                'pengajuan_surat' => $pengajuanSurat
            ]);
        }
    }


    public function approve($id)
    {
        $pengajuanSurat = PengajuanSurat::findOrFail($id);

        if (Auth::user()->level != 'masyarakat') {
            $pengajuanSurat->update(['status' => 'Diproses']);
            return response()->json([
                'message' => 'Berhasil diproses pengajuan surat!',
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'message' => 'Tidak diizinkan melakukan aksi ini.',
                'status' => 'error'
            ], 403);
        }
    }

    public function reject($id)
    {
        $pengajuanSurat = PengajuanSurat::findOrFail($id);

        if (Auth::user()->level != 'masyarakat') {
            $pengajuanSurat->update(['status' => 'Ditolak']);
            return response()->json([
                'message' => 'Berhasil menolak pengajuan surat!',
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'message' => 'Tidak diizinkan melakukan aksi ini.',
                'status' => 'error'
            ], 403);
        }
    }

    public function edit(PengajuanSurat $pengajuanSurat)
    {
        if (Auth::user()->level == 'masyarakat') {
            return redirect('/');
        } else {
            if ($pengajuanSurat->status == 'Diproses') {
                if ($pengajuanSurat->jenis_surat == 'Surat Keterangan') {
                    return view('pengajuan_surat.proses_surat_keterangan', [
                        'title' => 'Proses Surat Keterangan',
                        'pengajuan_surat' => $pengajuanSurat
                    ]);
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Kelahiran') {
                    return view('pengajuan_surat.proses_surat_kelahiran', [
                        'title' => 'Proses Surat Keterangan Kelahiran',
                        'pengajuan_surat' => $pengajuanSurat
                    ]);
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Kematian') {
                    return view('pengajuan_surat.proses_surat_kematian', [
                        'title' => 'Proses Surat Keterangan Kematian',
                        'pengajuan_surat' => $pengajuanSurat
                    ]);
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Usaha') {
                    return view('pengajuan_surat.proses_surat_keterangan_usaha', [
                        'title' => 'Proses Surat Keterangan Usaha',
                        'pengajuan_surat' => $pengajuanSurat
                    ]);
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Domisili') {
                    return view('pengajuan_surat.proses_surat_keterangan_domisili', [
                        'title' => 'Proses Surat Keterangan Domisili',
                        'pengajuan_surat' => $pengajuanSurat
                    ]);
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Tidak Mampu') {
                    return view('pengajuan_surat.proses_surat_keterangan_tidak_mampu', [
                        'title' => 'Proses Surat Keterangan Tidak Mampu',
                        'pengajuan_surat' => $pengajuanSurat
                    ]);
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Izin Penelitian') {
                    return view('pengajuan_surat.proses_surat_keterangan_izin_penelitian', [
                        'title' => 'Proses Surat Keterangan Izin Penelitian',
                        'pengajuan_surat' => $pengajuanSurat
                    ]);
                } else {
                    return redirect()->route('pengajuan-surat.index');
                }
            } else {
                return redirect('/');
            }
        }
    }



    public function update(Request $request, PengajuanSurat $pengajuanSurat)
    {
        if (Auth::user()->level === 'masyarakat') {
            return response()->json([
                'message' => 'Tidak diizinkan melakukan aksi ini.',
                'status' => 'error'
            ], 403);
        }

        if ($pengajuanSurat->jenis_surat == 'Surat Keterangan') {
            $request->validate([
                'nomor_surat' => 'required',
            ]);

            $data = $request->except('_token');
        } elseif ($pengajuanSurat->jenis_surat === 'Surat Kelahiran') {
            $request->validate([
                'kepala_keluarga' => 'required',
                'no_kk' => 'required',
                //DATA ANAK
                'nama_bayi' => 'required',
                'kelamin_bayi' => 'required',
                'tempat_lahir' => 'required',
                'lokasi_lahir' => 'required',
                'hari_lahir' => 'required',
                'tgl_lahir_bayi' => 'required',
                'bln_lahir_bayi' => 'required',
                'thn_lahir_bayi' => 'required',
                'lahir_pukul' => 'required',
                // 'anak_ke' => 'required',
                'jenis_lahir' => 'required',
                'lahiran_ke' => 'required',
                'penolong_lahir' => 'required',
                'berat_bayi' => 'required',
                'panjang_bayi' => 'required',

                // //DATA IBU
                'nik_ibu' => 'required',
                'nama_ibu' => 'required',
                'tgl_lahir_ibu' => 'required',
                'bln_lahir_ibu' => 'required',
                'thn_lahir_ibu' => 'required',
                'umur_ibu' => 'required',
                'pekerjaan_ibu' => 'required',
                'alamat_ibu' => 'required',
                'kewarganegaraan_ibu' => 'required',
                'kebangsaan_ibu' => 'required',
                'tgl_nikah' => 'required',
                'bln_nikah' => 'required',
                'thn_nikah' => 'required',

                // // //DATA AYAH
                'nik_ayah' => 'required',
                'nama_ayah' => 'required',
                'tgl_lahir_ayah' => 'required',
                'bln_lahir_ayah' => 'required',
                'thn_lahir_ayah' => 'required',
                'umur_ayah' => 'required',
                'alamat_ayah' => 'required',
                'kewarganegaraan_ayah' => 'required',
                'kebangsaan_ayah' => 'required',

                // // //DATA PELAPOR
                'nik_pelapor' => 'required',
                'nama_pelapor' => 'required',
                'umur_pelapor' => 'required',
                'kelamin_pelapor' => 'required',
                'pekerjaan_pelapor' => 'required',
                'alamat_pelapor' => 'required',

                // // //DATA SAKSI I
                'nik_saksi1' => 'required',
                'nama_saksi1' => 'required',
                'umur_saksi1' => 'required',
                // 'kelamin_saksi1' => 'required',
                'pekerjaan_saksi1' => 'required',
                'alamat_saksi1' => 'required',

                // //DATA SAKSI II
                'nik_saksi2' => 'required',
                'nama_saksi2' => 'required',
                'umur_saksi2' => 'required',
                // 'kelamin_saksi2' => 'required',
                'pekerjaan_saksi2' => 'required',
                'alamat_saksi2' => 'required',

                //DATA DARI ADMIN
                'nomor_surat' => 'required',

            ]);

            $data = $request->except('_token');
        } elseif ($pengajuanSurat->jenis_surat === 'Surat Kematian') {
            $request->validate([
                'kepala_keluarga_jenazah' => 'required',
                'no_kk_jenazah' => 'required',
                //DATA JENAZAH
                'nik_jenazah' => 'required',
                'nama_jenazah' => 'required',
                'kelamin_jenazah' => 'required',
                'tgl_lahir_jenazah' => 'required',
                'bln_lahir_jenazah' => 'required',
                'thn_lahir_jenazah' => 'required',
                'umur_jenazah' => 'required',
                'tempat_lahir_jenazah' => 'required',
                'agama_jenazah' => 'required',
                'pekerjaan_jenazah' => 'required',
                'alamat_jenazah' => 'required',
                'anak_ke_jenazah' => 'required',
                'tgl_mati_jenazah' => 'required',
                'bln_mati_jenazah' => 'required',
                'thn_mati_jenazah' => 'required',
                'pukul_jenazah' => 'required',
                'sebab_mati_jenazah' => 'required',
                'tempat_mati_jenazah' => 'required',
                'yang_menerangkan_jenazah' => 'required',

                // // // //DATA AYAH
                'nik_ayah_jenazah' => 'required',
                'nama_ayah_jenazah' => 'required',
                'tgl_lahir_ayah_jenazah' => 'required',
                'bln_lahir_ayah_jenazah' => 'required',
                'thn_lahir_ayah_jenazah' => 'required',
                'umur_ayah_jenazah' => 'required',
                'pekerjaan_ayah_jenazah' => 'required',
                'alamat_ayah_jenazah' => 'required',

                // // //DATA IBU
                'nik_ibu_jenazah' => 'required',
                'nama_ibu_jenazah' => 'required',
                'tgl_lahir_ibu_jenazah' => 'required',
                'bln_lahir_ibu_jenazah' => 'required',
                'thn_lahir_ibu_jenazah' => 'required',
                'umur_ibu_jenazah' => 'required',
                'pekerjaan_ibu_jenazah' => 'required',
                'alamat_ibu_jenazah' => 'required',

                // // // //DATA PELAPOR
                'nik_pelapor_jenazah' => 'required',
                'nama_pelapor_jenazah' => 'required',
                'tgl_lahir_pelapor_jenazah' => 'required',
                'bln_lahir_pelapor_jenazah' => 'required',
                'thn_lahir_pelapor_jenazah' => 'required',
                'umur_pelapor_jenazah' => 'required',
                'kelamin_pelapor_jenazah' => 'required',
                'pekerjaan_pelapor_jenazah' => 'required',
                'alamat_pelapor_jenazah' => 'required',


                // // // //DATA SAKSI I
                'nik_saksi1_jenazah' => 'required',
                'nama_saksi1_jenazah' => 'required',
                'tgl_lahir_saksi1_jenazah' => 'required',
                'bln_lahir_saksi1_jenazah' => 'required',
                'thn_lahir_saksi1_jenazah' => 'required',
                'umur_saksi1_jenazah' => 'required',
                'pekerjaan_saksi1_jenazah' => 'required',
                'alamat_saksi1_jenazah' => 'required',

                // // //DATA SAKSI II
                'nik_saksi2_jenazah' => 'required',
                'nama_saksi2_jenazah' => 'required',
                'tgl_lahir_saksi2_jenazah' => 'required',
                'bln_lahir_saksi2_jenazah' => 'required',
                'thn_lahir_saksi2_jenazah' => 'required',
                'umur_saksi2_jenazah' => 'required',
                'pekerjaan_saksi2_jenazah' => 'required',
                'alamat_saksi2_jenazah' => 'required',

                //DATA DARI ADMIN
                'nomor_surat_kematian' => 'required',

            ]);

            $data = $request->except('_token');
        } elseif ($pengajuanSurat->jenis_surat == 'Surat Keterangan Usaha') {
            $request->validate([
                'nama' => 'required',
                'tempat_lahir' => 'required',
                'ttl' => 'required',
                'negara_agama' => 'required',
                'alamat' => 'required',
                'nik' => 'required',
                'lama_usaha' => 'required',
                'nama_usaha' => 'required',
                'nomor_surat' => 'required',
                'foto_ktp' => 'nullable|image|max:2048'

            ]);

            $data = $request->except('_token');
        } elseif ($pengajuanSurat->jenis_surat == 'Surat Keterangan Domisili') {
            $request->validate([
                'nama' => 'required',
                'tempat_lahir' => 'required',
                'ttl' => 'required',
                'kelamin' => 'required',
                'pekerjaan' => 'required',
                'status' => 'required',
                'agama' => 'required',
                'nomor_surat' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'foto_ktp' => 'nullable|image|max:2048'
            ]);

            $data = $request->except('_token');
        } elseif ($pengajuanSurat->jenis_surat == 'Surat Keterangan Tidak Mampu') {
            $request->validate([
                'nama' => 'required',
                'tempat_lahir' => 'required',
                'ttl' => 'required',
                'alamat' => 'required',
                'nik' => 'required',
                'nomor_surat' => 'required',
                'foto_ktp' => 'nullable|image|max:2048'

            ]);

            $data = $request->except('_token');
        } elseif ($pengajuanSurat->jenis_surat == 'Surat Keterangan Izin Penelitian') {
            $request->validate([
                'nama' => 'required',
                'nim' => 'required',
                'univ' => 'required',
                'prodi' => 'required',
                'judul_penelitian' => 'required',
                'awal_penelitian' => 'required',
                'akhir_penelitian' => 'required',
                'nomor_surat' => 'required',
                'foto_ktp' => 'nullable|image|max:2048'
            ]);

            $data = $request->except('_token');
        } else {
            return response()->json([
                'message' => 'Jenis surat tidak valid.',
                'status' => 'error'
            ], 400);
        }

        $pengajuanSurat->update([
            'surat' => json_encode($data),
            'status' => 'Selesai'
        ]);

        return response()->json([
            'message' => 'Berhasil membuat surat!',
            'status' => 'success'
        ]);
    }



    public function destroy(PengajuanSurat $pengajuanSurat)
    {
        //
    }


    public function preview(PengajuanSurat $pengajuanSurat)
    {
        if (Auth::user()->level == 'masyarakat') {
            if ($pengajuanSurat->status == 'Selesai' && $pengajuanSurat->masyarakat_id == Auth::user()->id) {
                if ($pengajuanSurat->jenis_surat == 'Surat Keterangan') {
                    $html = 'pengajuan_surat.templates.surat_keterangan';
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Kelahiran') {
                    $html = 'pengajuan_surat.templates.surat_kelahiran';
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Kematian') {
                    $html = 'pengajuan_surat.templates.surat_kematian';
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Usaha') {
                    $html = 'pengajuan_surat.templates.surat_keterangan_usaha';
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Domisili') {
                    $html = 'pengajuan_surat.templates.surat_keterangan_domisili';
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Tidak Mampu') {
                    $html = 'pengajuan_surat.templates.surat_keterangan_tidak_mampu';
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Izin Penelitian') {
                    $html = 'pengajuan_surat.templates.surat_keterangan_izin_penelitian';
                } else {
                    return redirect()->route('pengajuan-surat.index');
                }
            } else {
                return redirect('/');
            }
        } else {
            if ($pengajuanSurat->jenis_surat == 'Surat Keterangan') {
                $html = 'pengajuan_surat.templates.surat_keterangan';
            } elseif ($pengajuanSurat->jenis_surat === 'Surat Kelahiran') {
                $html = 'pengajuan_surat.templates.surat_kelahiran';
            } elseif ($pengajuanSurat->jenis_surat === 'Surat Kematian') {
                $html = 'pengajuan_surat.templates.surat_kematian';
            } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Usaha') {
                $html = 'pengajuan_surat.templates.surat_keterangan_usaha';
            } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Domisili') {
                $html = 'pengajuan_surat.templates.surat_keterangan_domisili';
            } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Tidak Mampu') {
                $html = 'pengajuan_surat.templates.surat_keterangan_tidak_mampu';
            } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Izin Penelitian') {
                $html = 'pengajuan_surat.templates.surat_keterangan_izin_penelitian';
            } else {
                return redirect()->route('pengajuan-surat.index');
            }
        }

        return Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView($html, [
            'pengajuan_surat' => $pengajuanSurat,
            'surat' => json_decode($pengajuanSurat->surat),
        ])->stream();
    }


    public function download(PengajuanSurat $pengajuanSurat)
    {
        if (Auth::user()->level == 'masyarakat') {
            if ($pengajuanSurat->status == 'Selesai' && $pengajuanSurat->masyarakat_id == Auth::user()->id) {
                if ($pengajuanSurat->jenis_surat == 'Surat Keterangan') {
                    $html = 'pengajuan_surat.templates.surat_keterangan';
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Kelahiran') {
                    $html = 'pengajuan_surat.templates.surat_kelahiran';
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Kematian') {
                    $html = 'pengajuan_surat.templates.surat_kematian';
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Usaha') {
                    $html = 'pengajuan_surat.templates.surat_keterangan_usaha';
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Domisili') {
                    $html = 'pengajuan_surat.templates.surat_keterangan_domisili';
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Tidak Mampu') {
                    $html = 'pengajuan_surat.templates.surat_keterangan_tidak_mampu';
                } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Izin Penelitian') {
                    $html = 'pengajuan_surat.templates.surat_keterangan_izin_penelitian';
                } else {
                    return redirect()->route('pengajuan-surat.index');
                }
            } else {
                return redirect('/');
            }
        } else {
            if ($pengajuanSurat->jenis_surat == 'Surat Keterangan') {
                $html = 'pengajuan_surat.templates.surat_keterangan';
            } elseif ($pengajuanSurat->jenis_surat === 'Surat Kelahiran') {
                $html = 'pengajuan_surat.templates.surat_kelahiran';
            } elseif ($pengajuanSurat->jenis_surat === 'Surat Kematian') {
                $html = 'pengajuan_surat.templates.surat_kematian';
            } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Usaha') {
                $html = 'pengajuan_surat.templates.surat_keterangan_usaha';
            } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Domisili') {
                $html = 'pengajuan_surat.templates.surat_keterangan_domisili';
            } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Tidak Mampu') {
                $html = 'pengajuan_surat.templates.surat_keterangan_tidak_mampu';
            } elseif ($pengajuanSurat->jenis_surat === 'Surat Keterangan Izin Penelitian') {
                $html = 'pengajuan_surat.templates.surat_keterangan_izin_penelitian';
            } else {
                return redirect()->route('pengajuan-surat.index');
            }
        }

        return Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView($html, [
            'pengajuan_surat' => $pengajuanSurat,
            'surat' => json_decode($pengajuanSurat->surat),
        ])->download($pengajuanSurat->jenis_surat . '.pdf');
    }
}
