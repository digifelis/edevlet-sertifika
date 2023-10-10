<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\superadmin\KursModal;
use App\Models\superadmin\KurumModal;
use Illuminate\Support\Str;

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
        $kurslar = KursModal::orderBy('id', 'desc')->get();
        $kurumlar = KurumModal::all();
        return view('superadmin.kurslar.index', ['kurslar' => $kurslar, 'kurumlar' => $kurumlar]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $kurumlar = KurumModal::all();
        return view('superadmin.kurslar.add', ['kurumlar' => $kurumlar]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $kurs = new KursModal();
        $kurs->kursAdi = $request->kursAdi;
        $kurs->kursKurumId = $request->kursKurumId;
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
            return redirect()->route('superadmin.kurslar.create');
        }
        $fileName = $request->file('sablonDosyasi')->getClientOriginalName();
        $fileExtension = $request->file('sablonDosyasi')->getClientOriginalExtension();

        if($fileExtension != 'docx'){
            $request->session()->flash('message', 'Sadece docx uzantılı dosyalar yükleyebilirsiniz.');
            return redirect()->route('superadmin.kurslar.create');
        }

        $fileName = time().'_'.Str::slug($fileName).".docx";
        $request->file('sablonDosyasi')->move(public_path('uploads/templates/'.$request->kursKurumId), $fileName);
        $kurs->sablonDosyasi = $fileName;
        /* file upload */

        $kurs->save();
        return redirect()->route('superadmin.kurslar.index');
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
        $kurslar = KursModal::all();
        $kurumlar = KurumModal::all();
        return view('superadmin.kurslar.show', ['kurslar' => $kurslar, 'kurumlar' => $kurumlar]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $kurslar = KursModal::find($id);
        $kurumlar = KurumModal::all();
        return view('superadmin.kurslar.edit', ['kurslar' => $kurslar, 'kurumlar' => $kurumlar]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $kurs = KursModal::find($id);
        $kurs->kursAdi = $request->kursAdi;
        $kurs->kursKurumId = $request->kursKurumId;
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
                return redirect()->route('superadmin.kurslar.edit', $id);
            }

            $fileName = time().'_'.Str::slug($fileName).".docx";
            $request->file('sablonDosyasi')->move(public_path('uploads/templates/'.$request->kursKurumId), $fileName);
            $kurs->sablonDosyasi = $fileName;
        }
        /* file upload */


        $kurs->save();
        return redirect()->route('superadmin.kurslar.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = KursModal::find($id);
        $user->delete();
        return redirect()->route('superadmin.kurslar.index');
    }
}
