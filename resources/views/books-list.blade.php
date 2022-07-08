<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Books List</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('datatable/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('datatable/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">
</head>
<body>
    <div class="container">
        <div class="row" style="margin-top: 45px">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Books</div>
                    <div class="card-body">
                        <table class="table table-hover table-condensed" id="book-table">
                            <thead>
                                <th>#</th>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Actions</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Add new Book</div>
                    <div class="card-body">
                        <form action="{{ route('add.book') }}" method="post" id="add-book-form">
                            @csrf
                            <div class="form-group">
                                <label for="title">Book Title</label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="Enter Book title">
                                <span class="text-danger error-text title_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="author">Book Author</label>
                                <input type="text" class="form-control" name="author" id="author" placeholder="Enter Book author">
                                <span class="text-danger error-text author_error"></span>
                            </div>
                            <div class="form-group">
                             <button type="submit" class="btn btn-block btn-success">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('edit-book-modal')
    <script src="{{ asset('jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('toastr/toastr.min.js') }}"></script>
    <script>
         toastr.options.preventDuplicates = true;
         $.ajaxSetup({
             headers:{
                 'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
             }
         });

         $(function() {

            // Add Book
            $('#add-book-form').on('submit', function(e){
                    e.preventDefault();
                    var form = this;
                    $.ajax({
                        url:$(form).attr('action'),
                        method:$(form).attr('method'),
                        data:new FormData(form),
                        processData:false,
                        dataType:'json',
                        contentType:false,
                        beforeSend:function(){
                             $(form).find('span.error-text').text('');
                        },
                        success:function(data){
                             if(data.code == 0){
                                   $.each(data.error, function(prefix, val){
                                       $(form).find('span.'+prefix+'_error').text(val[0]);
                                   });
                             }else{
                                $(form)[0].reset();
                                //   alert(data.msg);
                                $('#book-table').DataTable().ajax.reload(null, false);
                                toastr.success(data.msg);
                             }
                        }
                    });
                });
            
                // GET ALL BOOKS
                $('#book-table').DataTable({
                     processing:true,
                     info:true,
                     ajax:"{{ route('get.books.list') }}",
                     columns:[
                         {data:'id', name:'id'},
                         {data:'title', name:'title'},
                         {data:'author', name:'author'},
                         {data:'actions', name:'actions', orderable:false, searchable:false},
                     ]
                });

                $(document).on('click', '#editBookBtn', function(){
                    var book_id = $(this).data('id');
                    $('.editBook').find('form')[0].reset();
                    $('.editBook').find('span.error-text').text('');
                    $.post('<?= route("get.book.details") ?>',{book_id:book_id}, function(data){
                        // alert(data.details.title);
                        $('.editBook').find('input[name="bid"]').val(data.details.id);
                        $('.editBook').find('input[name="title"]').val(data.details.title);
                        $('.editBook').find('input[name="author"]').val(data.details.author);
                        $('.editBook').modal('show');
                    },'json');
                });

                // UPDATE BOOK DETAILS
                $('#update-book-form').on('submit',function(e){
                    e.preventDefault();
                    var form = this;
                    $.ajax({
                        url:$(form).attr('action'),
                        method:$(form).attr('method'),
                        data:new FormData(form),
                        processData:false,
                        dataType:'json',
                        contentType:false,
                        beforeSend: function(){
                             $(form).find('span.error-text').text('');
                        },
                        success: function(data){
                              if(data.code == 0){
                                  $.each(data.error, function(prefix, val){
                                      $(form).find('span.'+prefix+'_error').text(val[0]);
                                  });
                              }else{
                                  $('#book-table').DataTable().ajax.reload(null, false);
                                  $('.editBook').modal('hide');
                                  $('.editBook').find('form')[0].reset();
                                  toastr.success(data.msg);
                              }
                        }
                    });
                });

                // DELETE BOOK RECORD
                $(document).on('click', '#deleteBookBtn', function(){
                    var book_id = $(this).data('id');
                    var url = '{{ route("delete.book") }}';
                    
                    swal.fire({
                         title:'Are you sure?',
                         html:'You want to <b>delete</b> this book',
                         showCancelButton:true,
                         showCloseButton:true,
                         cancelButtonText:'Cancel',
                         confirmButtonText:'Yes, Delete',
                         cancelButtonColor:'#d33',
                         confirmButtonColor:'#556ee6',
                         width:300,
                         allowOutsideClick:false
                    }).then(function(result){
                          if(result.value){
                              $.post(url,{book_id:book_id}, function(data){
                                   if(data.code == 1){
                                       $('#book-table').DataTable().ajax.reload(null, false);
                                       toastr.success(data.msg);
                                   }else{
                                       toastr.error(data.msg);
                                   }
                              },'json');
                          }
                    });
                });

         });
    </script>

</body>
</html>