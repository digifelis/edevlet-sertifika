@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Kurs Listesi</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Kurs Listesi</li>
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
                <h3 class="card-title">Kurs Bilgileri</h3>

                    @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif

              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" action="{{route('admin.kurslar.edit', ['id'=>$kurslar->id])}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Kurs Adı</label>
                    <div class="col-sm-10">
                      <input name="kursAdi" type="text" class="form-control" id="inputEmail3" value="{{$kurslar->kursAdi}}" >
                    </div>
                  </div>



                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Sertifika Adı</label>
                    <div class="col-sm-10">
                      <input name="sertifikaAdi" type="text" class="form-control" id="input1" value="{{$kurslar->sertifikaAdi}}" >
                    </div>
                  </div>


                  
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Başlık</label>
                    <div class="col-sm-10">
                      <input name="baslik" type="text" class="form-control" id="input1" value="{{$kurslar->baslik}}" >
                    </div>
                  </div>




                <div class="row">
                    <div class="col-sm-6">
                          <div class="form-group row">

                          <label for="inputPassword3" class="col-sm-2 col-form-label">Şablon dosyası</label>
                          <a href="{{ URL::to('/uploads/templates/')}}/{{$kurslar->kursKurumId}}/{{$kurslar->sablonDosyasi}}" target="_blank">Dosyayı görüntüle</a> <br> <br>
                          <div class="col-sm-10">
                              <input name="sablonDosyasi" type="file" class="form-control" id="input1" value="{{$kurslar->sablonDosyasi}}" >
                          </div>

                        </div>
                    </div>

                  

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Sertifika Türü</label>
                            <select class="form-control" name="tur">
                                <option value="none">seçiniz</option>
                                <option value="katılım belgesi" {{$kurslar->tur == 'katılım belgesi'? 'selected':''}} >katılım belgesi</option>
                                <option value="kurs belgesi" {{$kurslar->tur == 'kurs belgesi'? 'selected':''}} >kurs belgesi</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Açıklama</label>
                    <div class="col-sm-10">
                        <input name="aciklama" type="text" class="form-control" id="input1" value="{{$kurslar->aciklama}}" >
                    </div>
                </div>




                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Sertifika Geçerlilik Tarihi</label>
                            <div class="col-sm-10">
                                <input name="sertifikaGecerlilikTarihi" type="text" class="form-control sertifikaGecerlilikTarihi" id="sertifikaGecerlilikTarihi"
                                value="{{$kurslar->sertifikaGecerlilikTarihi}}" >
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-4">
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Kurs Başlangıç Tarihi</label>
                            <div class="col-sm-10">
                                <input name="baslangicTarihi" type="text" class="form-control sertifikaGecerlilikTarihi" id="sertifikaGecerlilikTarihi"
                                value="{{$kurslar->baslangicTarihi}}" >
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-4">
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Kurs bitiş Tarihi</label>
                            <div class="col-sm-10">
                                <input name="bitisTarihi" type="text" class="form-control sertifikaGecerlilikTarihi" id="sertifikaGecerlilikTarihi" 
                                value="{{$kurslar->bitisTarihi}}">
                            </div>
                        </div>
                    </div>


                </div>


                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info">Kaydet</button>

                </div>
                <!-- /.card-footer -->
                <input name="dilKey" type="hidden" class="form-control" id="input1" value="tr">
              </form>
            </div>

        </div>
    </div>
    </div>
</section>



</div>




<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    /*
    config = {
        enableTime: true,
        dateFormat: 'Y-m-d H:i',
    }
    */
    flatpickr("input[id=sertifikaGecerlilikTarihi]");
</script>
@endsection