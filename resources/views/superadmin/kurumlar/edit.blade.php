@extends('layouts.superadmin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Kurum Bilgileri</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Kurum Bilgileri</li>
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
                <h3 class="card-title">kurum Bilgileri</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" action="{{route('superadmin.kurumlar.update', ['id'=>$kurum->id])}}" method="post">
                @csrf
                <div class="card-body">


                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Kurum Adı</label>
                    <div class="col-sm-10">
                      <input name="kurumAdi" type="text" class="form-control" id="input1" value="{{$kurum->kurumAdi}}">
                    </div>
                  </div>






                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info">Güncelle</button>

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