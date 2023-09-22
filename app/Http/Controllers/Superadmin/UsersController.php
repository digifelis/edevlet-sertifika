<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\superadmin\KurumModal;
use Illuminate\Http\Request;
use App\Models\superadmin\UsersModal;
class UsersController extends Controller
{
    //    
    /**
     * index
     *
     * @return void
     */
    public function index(){
        //$users = UsersModal::where('userType', '!=','superadmin')->get();
        $users = UsersModal::all();
        return view('superadmin.users.index', ['users' => $users]);
    }    
    /**
     * add
     *
     * @return void
     */
    public function add(){
        $kurumlar = KurumModal::all();
        return view('superadmin.users.add', ['kurumlar' => $kurumlar]);
    }
    
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request){
        $user = new UsersModal();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->userType = $request->userType;
        $user->userStatus = $request->userStatus;
        $user->userInstitution = $request->userInstitution;
        $user->save();
        return redirect()->route('superadmin.users.index');
    }
    
    /**
     * delete
     *
     * @param  mixed $id
     * @return void
     */
    public function delete($id){
        $user = UsersModal::find($id);
        $user->delete();
        return redirect()->route('superadmin.users.index');
    }
    
    /**
     * edit
     *
     * @param  mixed $id
     * @return void
     */
    public function edit($id){
        $user = UsersModal::find($id);
        $kurumlar = KurumModal::all();
        return view('superadmin.users.edit', ['user' => $user, 'kurumlar' => $kurumlar]);
    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function update(Request $request, $id){
        $user = UsersModal::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->password != null){
            $user->password = bcrypt($request->password);
        }
        $user->userType = $request->userType;
        $user->userStatus = $request->userStatus;
        $user->userInstitution = $request->userInstitution;
        $user->save();
        return redirect()->route('superadmin.users.index');
    }


}
