@extends('layouts.superadmin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sertifika Listesi</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Sertifika Listesi</li>
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
                <h3 class="card-title">Sertifika Bilgileri</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" action="{{route('superadmin.sertifikalar.edit', ['id' => $sertifikalar->id])}}" method="post">
                @csrf
           
                <div class="card-body">


                  <div class="row">
                    <div class="col-sm-6">
                      <!-- select -->
                      <div class="form-group">
                        <label>Öğrenci Adı</label>
                        <select class="custom-select rounded-0" id="exampleSelectRounded0" name="ogrenciBilgisi">
                            <option value="none">seçiniz</option>
                            @foreach ($ogrenciler as $ogrenci)
                            <option value="{{$ogrenci->id}}" 
                            {{$ogrenci->id == $sertifikalar->ogrenciId? 'selected':''}}
                            >{{$ogrenci->ogrenciAdi}} {{$ogrenci->ogrenciSoyadi}}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Kurs Adı</label>
                        <select class="custom-select rounded-0" id="exampleSelectRounded0" name="kursBilgisi">
                            <option value="none">seçiniz</option>
                            @foreach ($kurslar as $kurs)
                            <option value="{{$kurs->id}}" 
                            {{$kurs->id == $sertifikalar->kursId? 'selected':''}}
                            >{{$kurs->kursAdi}}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                  <label for="exampleSelectRounded0">Kurum Adı</label>
                  <select class="custom-select rounded-0" id="exampleSelectRounded0" name="kurumbilgisi">
                    <option value="none">seçiniz</option>
                    @foreach ($kurumlar as $kurum)
                    <option value="{{$kurum->id}}" 
                    {{$kurum->id == $sertifikalar->kurumId? 'selected':''}}
                    >{{$kurum->kurumAdi}}</option>
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