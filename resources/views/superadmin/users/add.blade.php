@extends('layouts.superadmin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Kullanıcı Listesi</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Kullanıcı Listesi</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
<section class="content">
    <div class="container-fluid">
    <div class="row">
        <div class="col-12">
<div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">Kullanıcı Bilgileri</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" action="{{route('superadmin.users.store')}}" method="post">
                @csrf
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                      <input name="email" type="email" class="form-control" id="inputEmail3" >
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                      <input name="password" type="password" class="form-control" id="inputPassword3" placeholder="Password">
                    </div>
                  </div>




                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Kullanıcı Adı</label>
                    <div class="col-sm-10">
                      <input name="name" type="text" class="form-control" id="input1" >
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <!-- select -->
                      <div class="form-group">
                        <label>Kullanıcı Türü</label>
                        <select class="form-control" name="userType">
                          <option value="none">seçiniz</option>
                          <option value="user" >user</option>
                          <option value="admin" >admin</option>
                          <option value="superadmin" >superadmin</option>

                        </select>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Kullanıcı Durumu</label>
                        <select class="form-control" name="userStatus">
                        <option value="none">seçiniz</option>
                          <option value="active" >aktif</option>
                          <option value="passive" >pasif</option>

                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                  <label for="exampleSelectRounded0">Kullanıcı Kurumu</label>
                  <select class="custom-select rounded-0" id="exampleSelectRounded0" name="userInstitution">
                    <option value="none">seçiniz</option>
                    @foreach ($kurumlar as $kurum)
                    <option value="{{$kurum->id}}" >{{$kurum->kurumAdi}}</option>
                    @endforeach
                  </select>
                </div>




                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info">Kaydet</button>

                </div>
                <!-- /.card-footer -->
              </form>
            </div>

        </div>
    </div>
    </div>
</section>



</div>
@endsection