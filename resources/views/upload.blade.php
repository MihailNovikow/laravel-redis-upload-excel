<!DOCTYPE html>

<html>

<head>

   <title>Загрузка Excel в Laravel</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" />

   <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" />




</head>

<body>

<div class="container">

   <div class="card-header bg-secondary dark bgsize-darken-4 white card-header">

       <h4 class="text-white">Загрузка Excel в Laravel</h4>

   </div>

   <div class="row justify-content-centre" style="margin-top: 4%">

       <div class="col-md-8">

           <div class="card">

               <div class="card-header bgsize-primary-4 white card-header">

                   <h4 class="card-title">Импорт Excel</h4>

               </div>

               <div class="card-body">

                   @if ($message = Session::get('success'))




                       <div class="alert alert-success alert-block">




                           <button type="button" class="close" data-dismiss="alert">×</button>




                           <strong>{{ $message }}</strong>




                       </div>

                       <br>

                   @endif

                   <form action="{{url("import")}}" method="post" enctype="multipart/form-data">

                       @csrf

                       <fieldset>

                           <label>Выберите файл  <small class="warning text-muted">{{__('Можно загрузить только файлы с расширениями (.xlsx или .xls)')}}</small></label>

                           <div class="input-group">

                               <input type="file" required class="form-control" name="uploaded_file" id="uploaded_file">

                               @if ($errors->has('uploaded_file'))

                                   <p class="text-right mb-0">

                                       <small class="danger text-muted" id="file-error">{{ $errors->first('uploaded_file') }}</small>

                                   </p>

                               @endif

                               <div class="input-group-append" id="button-addon2">

                                   <button class="btn btn-primary square" type="submit"><i class="ft-upload mr-1"></i> Upload</button>

                               </div>

                           </div>

                       </fieldset>

                   </form>

               </div>

           </div>

       </div>

   </div>




   <div class="row justify-content-left">

       <div class="col-md-12">

           <br />

           <div class="card">

               <div class="card-header bgsize-primary-4 white card-header">

                   <h4 class="card-title">Таблица</h4>

               </div>

               <div class="card-body">

                   <div class="pull-right">

                       <a href="{{url("export")}}" class="btn btn-primary" style="margin-left:85%">Экспорт Excel</a>

                   </div>

                   <div class=" card-content table-responsive">

                       <table id="example" class="table table-striped table-bordered" style="width:100%">

                           <thead>

                           <th>ID</th>

                           <th>name</th>

                           <th>date</th>



                           </thead>

                           <tbody>

                           @if(!empty($data) && $data->count())

                               @foreach($data as $row)

                                   <tr>

                                       <td>{{ $row->id }}</td>

                                       <td>{{ $row->name }}</td>

                                       <td>{{ $row->date }}</td>


                                   </tr>

                               @endforeach

                           @else

                               <tr>

                                   <td colspan="10">Нет данных.</td>

                               </tr>

                           @endif




                           </tbody>




                       </table>



                   </div>

               </div>

           </div>

       </div>




   </div>

   <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

   <script>

       $(document).ready(function() {

           $('#example').DataTable();

       } );

   </script>

</body>




</html>
