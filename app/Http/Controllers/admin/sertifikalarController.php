<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Superadmin\SertifikalarModal;
use App\Models\Superadmin\KursModal;
use App\Models\Superadmin\OgrencilerModal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
class SertifikalarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    
    public function index()
    {
        //
        $sertifikalar = DB::table('sertifikalar_modals')
        ->join('kurum_modals', 'kurum_modals.id', '=', 'sertifikalar_modals.kurumId')
        ->join('kurs_modals', 'kurs_modals.id', '=', 'sertifikalar_modals.kursId')
        ->join('ogrenciler_modals', 'ogrenciler_modals.id', '=', 'sertifikalar_modals.ogrenciId')
        ->select('sertifikalar_modals.*', 'kurum_modals.kurumAdi','kurs_modals.kursAdi', 'ogrenciler_modals.ogrenciAdi', 'ogrenciler_modals.ogrenciSoyadi')
        ->where('sertifikalar_modals.kurumId', '=', Auth::user()->userInstitution)
        ->get();
        return view('admin.sertifikalar.index', ['sertifikalar' => $sertifikalar]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $kurslar = KursModal::where('kursKurumId', Auth::user()->userInstitution)->get();
        $ogrenciler = OgrencilerModal::where('kurumId', Auth::user()->userInstitution)->get();
        return view('admin.sertifikalar.add', ['kurslar' => $kurslar, 'ogrenciler' => $ogrenciler]);
    }
    
    /**
     * ogrenciEkle
     * add ogrenci for selected kurs
     * @param  mixed $id
     * @return void
     */
    public function ogrenciEkle(int $id){
        $kurslar = KursModal::where('kursKurumId', Auth::user()->userInstitution)->where('id', $id)->get();
        $ogrenciler = OgrencilerModal::where('kurumId', Auth::user()->userInstitution)->get();
        return view('admin.sertifikalar.add', ['kurslar' => $kurslar, 'ogrenciler' => $ogrenciler, 'action' => 'ogrenciEkle']); 
    }
    
    /**
     * ogrenciListe
     * get list of ogrenci for selected kurs
     * @param  mixed $id
     * @return void
     */
    public function ogrenciListe(int $id){

        $sertifikalar = DB::table('sertifikalar_modals')
        ->join('kurum_modals', 'kurum_modals.id', '=', 'sertifikalar_modals.kurumId')
        ->join('kurs_modals', 'kurs_modals.id', '=', 'sertifikalar_modals.kursId')
        ->join('ogrenciler_modals', 'ogrenciler_modals.id', '=', 'sertifikalar_modals.ogrenciId')
        ->select('sertifikalar_modals.*', 'kurum_modals.kurumAdi','kurs_modals.kursAdi', 'ogrenciler_modals.ogrenciAdi', 'ogrenciler_modals.ogrenciSoyadi')
        ->where('sertifikalar_modals.kurumId', '=', Auth::user()->userInstitution)
        ->where('sertifikalar_modals.kursId', '=', $id)
        ->get();

        return view('admin.sertifikalar.index', ['sertifikalar' => $sertifikalar]);
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
        $sertifikalar = new SertifikalarModal;
        $sertifikalar->kurumId = Auth::user()->userInstitution;
        /* check kursBilgisi belongs to userInstitution */
        $kurs = KursModal::where('id', $request->kursBilgisi)->where('kursKurumId', Auth::user()->userInstitution)->first();
        if(!$kurs){
            return redirect()->route('admin.sertifikalar.index')->with('message', 'Kurs bilgisi bulunamadı.')->with('message-type', 'error');
        }
        $sertifikalar->kursId = $request->kursBilgisi;
        /* check ogrenciBilgisi belongs to userInstitution */
        $ogrenci = OgrencilerModal::where('id', $request->ogrenciBilgisi)->where('kurumId', Auth::user()->userInstitution)->first();
        if(!$ogrenci){
            return redirect()->route('admin.sertifikalar.index')->with('message', 'Öğrenci bilgisi bulunamadı.')->with('message-type', 'error');
        }
        $sertifikalar->ogrenciId = $request->ogrenciBilgisi;
        $sertifikalar->save();
        return redirect()->route('admin.sertifikalar.index')->with('message', 'Sertifika eklendi.')->with('message-type', 'success');;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
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
        $sertifikalar = SertifikalarModal::where('id',$id)->where('kurumId', Auth::user()->userInstitution)->first();
        $kurslar = KursModal::where('kursKurumId', Auth::user()->userInstitution)->get();
        $ogrenciler = OgrencilerModal::where('kurumId', Auth::user()->userInstitution)->get();
        return view('admin.sertifikalar.edit', ['sertifikalar' => $sertifikalar, 'kurslar' => $kurslar, 'ogrenciler' => $ogrenciler]);
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
        $sertifikalar = SertifikalarModal::where('id', $id)->where('kurumId', Auth::user()->userInstitution)->first();
        $sertifikalar->kurumId = Auth::user()->userInstitution;
        /* check kursBilgisi belongs to userInstitution */
        $kurs = KursModal::where('id', $request->kursBilgisi)->where('kursKurumId', Auth::user()->userInstitution)->first();
        if(!$kurs){
            return redirect()->route('admin.sertifikalar.index')->with('message', 'Kurs bilgisi bulunamadı.')->with('message-type', 'error');
        }
        $sertifikalar->kursId = $request->kursBilgisi;
        /* check ogrenciBilgisi belongs to userInstitution */
        $ogrenci = OgrencilerModal::where('id', $request->ogrenciBilgisi)->where('kurumId', Auth::user()->userInstitution)->first();
        if(!$ogrenci){
            return redirect()->route('admin.sertifikalar.index')->with('message', 'Öğrenci bilgisi bulunamadı.')->with('message-type', 'error');
        }
        $sertifikalar->ogrenciId = $request->ogrenciBilgisi;
        $sertifikalar->save();
        return redirect()->route('admin.sertifikalar.index')->with('message', 'Sertifika güncellendi.')->with('message-type', 'success');;
        
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
            $sertifikalar = SertifikalarModal::where('id', $id)->where('kurumId', Auth::user()->userInstitution)->first();
            if($sertifikalar == null)
                throw new Exception("Yetkisiz erişim");
            $sertifikalar->delete();
            return redirect()->route('admin.sertifikalar.index')->with('message', 'Sertifika silindi.')->with('message-type', 'success');
        } catch (Exception $e)
        {
            return redirect()->route('admin.sertifikalar.index')->with('message', 'Sertifika silinemedi.')->with('message-type', 'error');
        }

    }
}
