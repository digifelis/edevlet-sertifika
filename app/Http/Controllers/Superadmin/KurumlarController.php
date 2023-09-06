<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\superadmin\KurumModal;
class KurumlarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $kurumlar = KurumModal::all();
        return view('superadmin.kurumlar.index', ['kurumlar' => $kurumlar]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('superadmin.kurumlar.add');
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
        $kurum = new KurumModal();
        $kurum->kurumAdi = $request->kurumAdi;
        $kurum->save();
        return redirect()->route('superadmin.kurumlar.index');
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
        $kurum = KurumModal::find($id);
        return view('superadmin.kurumlar.show', ['kurum' => $kurum]);
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
        $kurum = KurumModal::find($id);
        return view('superadmin.kurumlar.edit', ['kurum' => $kurum]);
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
        $kurum = KurumModal::find($id);
        $kurum->kurumAdi = $request->kurumAdi;
        $kurum->save();
        return redirect()->route('superadmin.kurumlar.index');
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
        $user = KurumModal::find($id);
        $user->delete();
        return redirect()->route('superadmin.kurumlar.index');
    }
}
