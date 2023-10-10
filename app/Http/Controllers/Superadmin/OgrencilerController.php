<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\superadmin\OgrencilerModal;
use App\Models\superadmin\KurumModal;

class OgrencilerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $ogrenciler = OgrencilerModal::orderBy('ogrenciAdi', 'asc')->get();
        return view('superadmin.ogrenciler.index', ['ogrenciler' => $ogrenciler]);
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
        return view('superadmin.ogrenciler.add', ['kurumlar' => $kurumlar]);
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
        $ogrenci = new OgrencilerModal();
        $ogrenci->ogrenciAdi = $request->ogrenciAdi;
        $ogrenci->ogrenciSoyadi = $request->ogrenciSoyadi;
        $ogrenci->kurumId = $request->kurumId;
        $ogrenci->tcKimlikNo = $request->tcKimlikNo;
        $ogrenci->save();
        return redirect()->route('superadmin.ogrenciler.index')->with('message', 'Öğrenci başarıyla eklendi.');

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
        $ogrenci = OgrencilerModal::find($id);
        return view('superadmin.ogrenciler.show', ['ogrenci' => $ogrenci]);
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
        $ogrenci = OgrencilerModal::find($id);
        $kurumlar = KurumModal::all();
        return view('superadmin.ogrenciler.edit', ['ogrenci' => $ogrenci, 'kurumlar' => $kurumlar]);
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
        $ogrenci = OgrencilerModal::find($id);
        $ogrenci->ogrenciAdi = $request->ogrenciAdi;
        $ogrenci->ogrenciSoyadi = $request->ogrenciSoyadi;
        $ogrenci->kurumId = $request->kurumId;
        $ogrenci->tcKimlikNo = $request->tcKimlikNo;
        $ogrenci->save();
        return redirect()->route('superadmin.ogrenciler.index')->with('message', 'Öğrenci başarıyla güncellendi.');
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
        $ogrenci = OgrencilerModal::find($id);
        $ogrenci->delete();
        return redirect()->route('superadmin.ogrenciler.index')->with('message', 'Öğrenci başarıyla silindi.');
    }
}
