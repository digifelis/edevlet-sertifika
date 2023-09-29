<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Superadmin\OgrencilerModal;

use Illuminate\Support\Facades\Auth;
use Exception;

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
        $ogrenciler = OgrencilerModal::orderBy('ogrenciAdi', 'asc')->where('kurumId', Auth::user()->userInstitution)->get();
        return view('admin.ogrenciler.index', ['ogrenciler' => $ogrenciler]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.ogrenciler.add');
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
        $ogrenci->kurumId = Auth::user()->userInstitution;
        $ogrenci->tcKimlikNo = $request->tcKimlikNo;
        $ogrenci->save();
        return redirect()->route('admin.ogrenciler.index')->with('message', 'Öğrenci başarıyla eklendi.');

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
        $ogrenci = OgrencilerModal::where('id', $id)->where('kurumId', Auth::user()->userInstitution)->first();
        return view('superadmin.ogrenciler.show', ['ogrenci' => $ogrenci]);
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
            $ogrenci = OgrencilerModal::where('id', $id)->where('kurumId', Auth::user()->userInstitution)->first();
            if($ogrenci == null)
                throw new Exception("Yetkisiz erişim");
            return view('admin.ogrenciler.edit', ['ogrenci' => $ogrenci]);
        } catch (Exception $e) {
            return redirect()->route('admin.ogrenciler.index')->with('message', 'Öğrenci güncellenemedi. Yetkisiz erişim.');
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
        try{
            $ogrenci = OgrencilerModal::find($id);
            if($ogrenci == null)
                throw new Exception("Yetkisiz erişim");
            $ogrenci->ogrenciAdi = $request->ogrenciAdi;
            $ogrenci->ogrenciSoyadi = $request->ogrenciSoyadi;
            $ogrenci->kurumId = Auth::user()->userInstitution;
            $ogrenci->tcKimlikNo = $request->tcKimlikNo;
            $ogrenci->save();
            return redirect()->route('admin.ogrenciler.index')->with('message', 'Öğrenci başarıyla güncellendi.');
        } catch (Exception $e) {
            return redirect()->route('admin.ogrenciler.index')->with('message', 'Öğrenci güncellenemedi.');
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
            $ogrenci = OgrencilerModal::where('id', $id)->where('kurumId', Auth::user()->userInstitution)->first();
            if($ogrenci == null)
                throw new Exception("Yetkisiz erişim");
            $ogrenci->delete();
            return redirect()->route('admin.ogrenciler.index')->with('message', 'Öğrenci başarıyla silindi.');
        } catch (Exception $e) {
            return redirect()->route('admin.ogrenciler.index')->with('message', 'Öğrenci silinemedi.');
        }

    }
}
