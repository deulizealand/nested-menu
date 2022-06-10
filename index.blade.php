<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nestable++</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('css/admin/css/nestable.css')}}">
    <link href="style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
  </head>
  <body>
  <div class="container">
    {{-- menu --}}
    <div class="row justify-content-center">
        <div class="col-md-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <div class="header-title">
                        Menu
                        <span class="float-right">
                            <a href="#newModal" class="btn btn-default pull-right" data-toggle="modal">
                                <i class="fas fa-plus"></i> Create menu item
                            </a>
                        </span>
                    </div>

                    {{-- new --}}
                    <div class="row mt-4 mb-4">
                        <div class="col-md-8">  
                            <div class="dd" id="nestable">
                                {!! $menu !!}
                            </div>
                    
                            <p id="success-indicator" style="display:none; margin-right: 10px;">
                                <i class="fas fa-check-circle"></i> Menu order has been saved
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <p>Drag items to move them in a different order <br> <span class="text-info">Supports (2) level deep</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    <!-- Create new item Modal -->
                    <div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Provide details of new menu item</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                {{ Form::open(array('route'=>'topnew','class'=>'form-horizontal'))}}
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="pertanyaan" class="col-md-3 control-label">Title</label>
                                            <div class="col-md-9">
                                            {{ Form::text('pertanyaan',null,array('class'=>'form-control'))}}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tipe" class="col-md-3 control-label">Slug</label>
                                            <div class="col-md-9">
                                            {{ Form::text('tipe',null,array('class'=>'form-control'))}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                    </div>
                                {{ Form::close()}}
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                          
                    <!-- Delete item Modal -->
                    <div class="modal border-danger fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Delete Item</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>

                                {{ Form::open(array('url'=>'/topmenudelete', 'method' => 'DELETE')) }}  
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this menu item?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        <input type="hidden" name="delete_id" id="postvalue" value="" />
                                        <input type="submit" class="btn btn-danger" value="Delete Item" />
                                    </div>
                                {{ Form::close() }}
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    {{-- new --}}
                </div>
            </div>
        </div>
    </div>

</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="./jquery.nestable.js"></script>
    
    <script>
        $(function() {
            $('.dd').nestable({
                maxDepth: 3,
                dropCallback: function(details) {
                
                var order = new Array();
                $("li[data-id='"+details.destId +"']").find('ol:first').children().each(function(index,elem) {
                    order[index] = $(elem).attr('data-id');
                });
                if (order.length === 0){
                    var rootOrder = new Array();
                    $("#nestable > ol > li").each(function(index,elem) {
                    rootOrder[index] = $(elem).attr('data-id');
                    });
                }
                var token = $('form').find( 'input[name=_token]' ).val();
                $.post('{{url("menustop/reorder/")}}', 
                    {
                        source : details.sourceId, 
                        destination: details.destId, 
                        order:JSON.stringify(order),
                        rootOrder:JSON.stringify(rootOrder),
                        _token: token 
                    },
                    function(data) {
                    // console.log('data '+data); 
                    })
                .done(function() { 
                    $( "#success-indicator" ).fadeIn(100).delay(1000).fadeOut();
                })
                .fail(function() {  })
                .always(function() {  });
                }

            });
        });
    </script>
  </body>
</html>
