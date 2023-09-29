@extends('layouts.superadmin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Öğrenci Listesi</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Öğrenci Bilgileri</li>
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
                <h3 class="card-title">Öğrenci Bilgileri</h3>
                @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" action="{{route('superadmin.ogrenciler.edit', ['id'=>$ogrenci->id])}}" method="post">
                @csrf
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Öğrenci Adı</label>
                    <div class="col-sm-10">
                      <input name="ogrenciAdi" type="text" class="form-control" id="inputEmail3" value="{{$ogrenci->ogrenciAdi}}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Öğrenci Soyadı</label>
                    <div class="col-sm-10">
                      <input name="ogrenciSoyadi" type="text" class="form-control" id="inputPassword3" placeholder="Öğrenci Soyadı" value="{{$ogrenci->ogrenciSoyadi}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Öğrenci TC Kimlik No</label>
                    <div class="col-sm-10">
                      <input name="tcKimlikNo" type="text" class="form-control" id="inputPassword3" placeholder="Öğrenci Tc Kimlik No" value="{{$ogrenci->tcKimlikNo}}">
                    </div>
                  </div>


                 



                  <div class="form-group">
                  <label for="exampleSelectRounded0">Öğrenci Kurumu</label>
                  <select class="custom-select rounded-0" id="exampleSelectRounded0" name="kurumId">
                    <option value="none">seçiniz</option>
                    @foreach ($kurumlar as $kurum)
                    <option value="{{$kurum->id}}" {{$ogrenci->kurumId == $kurum->id? 'selected':''}}>{{$kurum->kurumAdi}}</option>
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