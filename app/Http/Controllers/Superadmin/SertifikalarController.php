<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\superadmin\OgrencilerModal;
use App\Models\superadmin\KurumModal;
use App\Models\superadmin\KursModal;
use App\Models\superadmin\SertifikalarModal;

use Illuminate\Support\Facades\DB;

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
        ->get();
        return view('superadmin.sertifikalar.index', ['sertifikalar' => $sertifikalar]);
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
        $kurslar = KursModal::all();
        $ogrenciler = OgrencilerModal::all();
        return view('superadmin.sertifikalar.add', ['kurumlar' => $kurumlar, 'kurslar' => $kurslar, 'ogrenciler' => $ogrenciler]);
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
        $sertifikalar->kurumId = $request->kurumbilgisi;
        $sertifikalar->kursId = $request->kursBilgisi;
        $sertifikalar->ogrenciId = $request->ogrenciBilgisi;
        $sertifikalar->save();
        return redirect()->route('superadmin.sertifikalar.index')->with('message', 'Sertifika eklendi.')->with('message-type', 'success');;
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
        $sertifikalar = SertifikalarModal::find($id);
        $kurumlar = KurumModal::all();
        $kurslar = KursModal::all();
        $ogrenciler = OgrencilerModal::all();
        return view('superadmin.sertifikalar.edit', ['sertifikalar' => $sertifikalar, 'kurumlar' => $kurumlar, 'kurslar' => $kurslar, 'ogrenciler' => $ogrenciler]);
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
        $sertifikalar = SertifikalarModal::find($id);
        $sertifikalar->kurumId = $request->kurumbilgisi;
        $sertifikalar->kursId = $request->kursBilgisi;
        $sertifikalar->ogrenciId = $request->ogrenciBilgisi;
        $sertifikalar->save();
        return redirect()->route('superadmin.sertifikalar.index')->with('message', 'Sertifika güncellendi.')->with('message-type', 'success');;
        
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
        $sertifikalar = SertifikalarModal::find($id);
        $sertifikalar->delete();
        return redirect()->route('superadmin.sertifikalar.index')->with('message', 'Sertifika silindi.')->with('message-type', 'success');
    }
}
