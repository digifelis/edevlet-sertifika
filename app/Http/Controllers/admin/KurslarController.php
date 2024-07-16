<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\superadmin\KursModal;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class KurslarController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $kurslar = KursModal::orderBy('id', 'desc')->where('kursKurumId', Auth::user()->userInstitution)->get();
       
        return view('admin.kurslar.index', ['kurslar' => $kurslar]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.kurslar.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        /* validation */
        $request->validate([
            'kursAdi' => 'required',
            'aciklama' => 'required',
            'baslangicTarihi' => 'required',
            'bitisTarihi' => 'required',
            'sertifikaAdi' => 'required',
            'baslik' => 'required',
            'tur' => 'required',
            'sertifikaGecerlilikTarihi' => 'required',
            'sablonDosyasi' => 'required',
        ]);
        /* validation */

        $kurs = new KursModal();
        $kurs->kursAdi = $request->kursAdi;
        $kurs->kursKurumId = Auth::user()->userInstitution;
        $kurs->aciklama = $request->aciklama;
        $kurs->baslangicTarihi = $request->baslangicTarihi;
        $kurs->bitisTarihi = $request->bitisTarihi;

        $kurs->sertifikaAdi = $request->sertifikaAdi;
        $kurs->baslik = $request->baslik;
        $kurs->dilKey = $request->dilKey;
        $kurs->tur = $request->tur;
        $kurs->sertifikaGecerlilikTarihi = $request->sertifikaGecerlilikTarihi;
        //$kurs->sablonDosyasi = $request->sablonDosyasi;

        /* file upload */
        if($request->file("sablonDosyasi") == null){
            $request->session()->flash('message', 'Dosya yüklemelisiniz.');
            return redirect()->route('admin.kurslar.create');
        }
        $fileName = $request->file('sablonDosyasi')->getClientOriginalName();
        $fileExtension = $request->file('sablonDosyasi')->getClientOriginalExtension();

        if($fileExtension != 'docx'){
            $request->session()->flash('message', 'Sadece docx uzantılı dosyalar yükleyebilirsiniz.');
            return redirect()->route('admin.kurslar.create');
        }

        $fileName = time().'_'.Str::slug($fileName).".docx";
        $request->file('sablonDosyasi')->move(public_path('uploads/templates/'.$kurs->kursKurumId), $fileName);
        $kurs->sablonDosyasi = $fileName;
        /* file upload */

        $kurs->save();
        return redirect()->route('admin.kurslar.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        /*
        $kurslar = KursModal::all();
        $kurumlar = KurumModal::all();
        return view('admin.kurslar.show', ['kurslar' => $kurslar, 'kurumlar' => $kurumlar]);
        */
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        //

        try{
            $kurslar = KursModal::where('id',  $id)->where('kursKurumId',  Auth::user()->userInstitution)->first();
            if($kurslar == null)
                throw new Exception("Yetkisiz erişim");
            return view('admin.kurslar.edit', ['kurslar' => $kurslar]);
        } catch (Exception $e) {
            return redirect()->route('admin.kurslar.index');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        //
        /* validation */
        $request->validate([
            'kursAdi' => 'required',
            'aciklama' => 'required',
            'baslangicTarihi' => 'required',
            'bitisTarihi' => 'required',
            'sertifikaAdi' => 'required',
            'baslik' => 'required',
            'dilKey' => 'required',
            'tur' => 'required',
            'sertifikaGecerlilikTarihi' => 'required',
        ]);
        /* validation */

        
        $kurs = KursModal::where('id',  $id)->where('kursKurumId',  Auth::user()->userInstitution)->first();
        if($kurs == null)
            throw new Exception("Yetkisiz erişim");
        $kurs->kursAdi = $request->kursAdi;
        $kurs->kursKurumId = Auth::user()->userInstitution;
        $kurs->aciklama = $request->aciklama;
        $kurs->baslangicTarihi = $request->baslangicTarihi;
        $kurs->bitisTarihi = $request->bitisTarihi;


        $kurs->sertifikaAdi = $request->sertifikaAdi;
        $kurs->baslik = $request->baslik;
        $kurs->dilKey = $request->dilKey;
        $kurs->tur = $request->tur;
        $kurs->sertifikaGecerlilikTarihi = $request->sertifikaGecerlilikTarihi;
        //$kurs->sablonDosyasi = $request->sablonDosyasi;

        /* file upload */
        if($request->file("sablonDosyasi") != null){

            $fileName = $request->file('sablonDosyasi')->getClientOriginalName();
            $fileExtension = $request->file('sablonDosyasi')->getClientOriginalExtension();

            if($fileExtension != 'docx'){
                $request->session()->flash('message', 'Sadece docx uzantılı dosyalar yükleyebilirsiniz.');
                return redirect()->route('admin.kurslar.edit', $id);
            }

            $fileName = time().'_'.Str::slug($fileName).".docx";
            $request->file('sablonDosyasi')->move(public_path('uploads/templates/'.$kurs->kursKurumId), $fileName);
            $kurs->sablonDosyasi = $fileName;
        }
        /* file upload */

        try{
            $kurs->save();
            $request->session()->flash('message', 'Kurs güncellendi.');
            return redirect()->route('admin.kurslar.index');
        } catch (Exception $e) {
            $request->session()->flash('message', 'Kurs güncellenirken hata oluştu.');
            return redirect()->route('admin.kurslar.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
        try{
            $user = KursModal::where('id',  $id)->where('kursKurumId',  Auth::user()->userInstitution)->first();
            if($user == null)
                throw new Exception("Yetkisiz erişim");
            $user->delete();
            return redirect()->route('admin.kurslar.index')->with('message', 'Kurs silindi.');

        } catch (Exception $e) {
            throw new Exception("Kurs silinirken hata oluştu.");
            return redirect()->route('admin.kurslar.index')->with('message', 'Kurs silinirken hata oluştu.');
        }
        
    }
}
