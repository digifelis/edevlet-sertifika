@extends('layouts.admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Öğrenci Ekle</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Ana Sayfa</a></li>
              <li class="breadcrumb-item active">Öğrenci Ekle</li>
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
                <h3 class="card-title">Öğrenci Ekle</h3>
                @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif
              </div>
              <!-- /.card-header -->

              <label for="inputEmail3" class="col-sm-2 col-form-label"><a href="{{asset('uploads/ogrenciler.xlsx')}}">Örnek Dosya İndir</a></label>
              <form class="form-horizontal" action="{{ route('admin.ogrenciler.import') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input class="form-control" type="file" name="file" accept=".xlsx, .xls">
                  <br>
                  <button type="submit" class="btn btn-info">Excel ile Aktar</button>
              </form>



              <!-- form start -->
              <form class="form-horizontal" action="{{route('admin.ogrenciler.store')}}" method="post">
                @csrf
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Öğrenci Adı</label>
                    <div class="col-sm-10">
                      <input name="ogrenciAdi" type="text" class="form-control" id="inputEmail3" placeholder="Öğrenci Adı" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Öğrenci Soyadı</label>
                    <div class="col-sm-10">
                      <input name="ogrenciSoyadi" type="text" class="form-control" id="inputPassword3" placeholder="Öğrenci Soyadı" required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Öğrenci TC Kimlik No</label>
                    <div class="col-sm-10">
                      <input name="tcKimlikNo" type="number" min="10000000000" max="99999999999" class="form-control" id="inputPassword3" placeholder="Öğrenci Tc Kimlik No" required>
                    </div>
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