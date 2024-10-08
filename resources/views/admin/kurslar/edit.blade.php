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
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Ana Sayfa</a></li>
              <li class="breadcrumb-item active">Kurs Düzenle</li>
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
                      <input name="kursAdi" type="text" class="form-control" id="kursAdi" value="{{$kurslar->kursAdi}}" required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Kurs Adı (İngilizce)</label>
                    <div class="col-sm-10">
                      <input name="kursAdiIng" type="text" class="form-control" id="kursAdiIng" value="{{$kurslar->kursAdiIng}}" required>
                    </div>
                  </div>


                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Sertifika Adı</label>
                    <div class="col-sm-10">
                      <input name="sertifikaAdi" type="text" class="form-control" id="input1" value="{{$kurslar->sertifikaAdi}}" required>
                    </div>
                  </div>


                  
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Başlık</label>
                    <div class="col-sm-10">
                      <input name="baslik" type="text" class="form-control" id="input2" value="{{$kurslar->baslik}}" required>
                    </div>
                  </div>




                <div class="row">
                    <div class="col-sm-6">
                          <div class="form-group row">

                          <label for="inputPassword3" class="col-sm-2 col-form-label">Şablon dosyası</label>
                          <a href="{{ URL::to('/uploads/templates/')}}/{{$kurslar->kursKurumId}}/{{$kurslar->sablonDosyasi}}" target="_blank">Dosyayı görüntüle</a> <br> <br>
                          <div class="col-sm-10">
                              <input name="sablonDosyasi" type="file" class="form-control" id="input3" value="{{$kurslar->sablonDosyasi}}" >
                          </div>

                        </div>
                    </div>

                  

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Sertifika Türü</label>
                            <select class="form-control" name="tur" required>
                                <option value="none">seçiniz</option>
                                <option value="Katılım Belgesi" {{$kurslar->tur == 'Katılım Belgesi'? 'selected':''}} >Katılım Belgesi</option>
                                <option value="Kurs Belgesi" {{$kurslar->tur == 'Kurs Belgesi'? 'selected':''}} >Kurs Belgesi</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Açıklama</label>
                    <div class="col-sm-10">
                        <input name="aciklama" type="text" class="form-control" id="input4" value="{{$kurslar->aciklama}}" required>
                    </div>
                </div>




                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Sertifika Geçerlilik Tarihi</label>
                            <div class="col-sm-10">
                                <input name="sertifikaGecerlilikTarihi" type="text" class="form-control sertifikaGecerlilikTarihi" id="sertifikaGecerlilikTarihi"
                                value="{{$kurslar->sertifikaGecerlilikTarihi}}" required>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-4">
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Kurs Başlangıç Tarihi</label>
                            <div class="col-sm-10">
                                <input name="baslangicTarihi" type="text" class="form-control sertifikaGecerlilikTarihi" id="sertifikaGecerlilikTarihi"
                                value="{{$kurslar->baslangicTarihi}}" required>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-4">
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Kurs bitiş Tarihi</label>
                            <div class="col-sm-10">
                                <input name="bitisTarihi" type="text" class="form-control sertifikaGecerlilikTarihi" id="sertifikaGecerlilikTarihi" 
                                value="{{$kurslar->bitisTarihi}}" required>
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