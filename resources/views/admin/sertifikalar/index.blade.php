@extends('layouts.admin')

@section('content')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
  <!-- Content Wrapper. Contains page content -->
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
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Ana Sayfa</a></li>
              <li class="breadcrumb-item active">Sertifika Listesi</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Tüm Sertifika listesi</h3>
                    @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="sertifika_table" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Sertifika Id</th>
                    <th>Sertifika No</th>
                    <th>Öğrenci Adı</th>
                    <th>Öğrenci Soyadı</th>
                    <th>Kurs Adı</th>
                    <th>Kurum Adı </th>
                    <th>İşlemler</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($sertifikalar as $sertifika)
                  <tr>
                    <td>{{$sertifika->id}}</td>
                    <td>{{$sertifika->sertifikaNo}}</td>
                    <td>{{$sertifika->ogrenciAdi}}</td>
                    <td>{{$sertifika->ogrenciSoyadi}}</td>
                    <td>{{$sertifika->kursAdi}}</td>
                    <td> {{$sertifika->kurumAdi}}</td>
                    <td>
                        <a href="{{route('admin.sertifikalar.destroy', ['id'=>$sertifika->id])}}" class="btn btn-danger">Sil</a>
                        <a href="{{route('admin.sertifikalar.edit', ['id'=>$sertifika->id])}}" class="btn btn-primary">Düzenle</a>
                        <a href="{{asset('uploads/sertifikalar/'.Auth::user()->userInstitution.'/'.$sertifika->kursId.'/'.$sertifika->id. '/belge.pdf')}}" target="_blank" class="btn btn-primary">Sertifika Görüntüle</a>
                        
                        
                    </td>
                  </tr>
                  @endforeach
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>

          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- DataTables  & Plugins -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>

<!-- Page specific script -->
<script>
  new DataTable('#sertifika_table');
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
  @endsection