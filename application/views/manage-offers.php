<!DOCTYPE html>
<html>
    <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Offers</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
     <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.min.css')?>" rel="stylesheet">
    <style>
     #filters .form-group{
        display: flex;
        flex-direction: column;
       

      }
    </style>
   
    </head> 
<body>
    <div class="container">
        <h1 style="font-size:20pt">Manage Offers</h1>

        
        <br />
        <div class="row" id="filters">

            <div class="form-group col-md-3">
            <label class="control-label col-md-12">Start Date</label>
            <div class="col-md-12">
                 <input  placeholder="dd/mm/yyyy" class="form-control datepicker" type="text" id="start_date">
            </div>
            </div>

             <div class="form-group col-md-3">
            <label class="control-label col-md-12">end Date</label>
            <div class="col-md-12">
                  <input  id="end_date" placeholder="dd/mm/yyyy" class="form-control datepicker" type="text">
            </div>
            </div>
        
        
          <div class="form-group col-md-3">
            <label class="control-label col-md-12">Active Status</label>
             <div class="col-md-12">
         <select id="is_active" class="form-control">
             <option value="">Select status</option>
             <option value="1">Yes</option>
             <option value="0">No</option>
         </select>
          </div>
            </div>

         <button class="btn btn-info" id="btn-filter"><i class="glyphicon glyphicon-search"></i>search</button>


         <button class="btn btn-success" onclick="add_offer()"><i class="glyphicon glyphicon-plus"></i> Add Offer</button>
          <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        </div>


       
      
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Heading</th>
                    <th>Start date</th>
                    <th>End date</th>
                    <th>Is Active</th>
                    <th style="width:150px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
            
            </tfoot>
        </table>
    </div>

<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>


<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.min.js')?>"></script>




<script type="text/javascript">

var method_type; //to know method operation
var table;
var base_url = '<?=  base_url(); ?>';

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 
            
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?= base_url('mange-offer-list')?>",
            "type": "POST",
             "data": function ( data ) {
             data.start_date=$('#start_date').val();
             data.end_date=$('#end_date').val();
             data.is_active=$('#is_active').val();
            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
    { 
        "targets": [0,5], //colum by index
        "orderable": false, 
       
    },
    ],
    });
  $('#btn-filter').click(function(){ //button filter event click
      
    table.ajax.reload();  //just reload table
   });
   
   $('.datetimepicker').datetimepicker({
       format:'DD/MM/YYYY  LT',
      
   });

   $('.datepicker').datetimepicker({
       format:'DD/MM/YYYY'
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});



function add_offer()
{
    method_type = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Offer'); // Set Title to Bootstrap modal title

    $('#photo-preview').hide(); // hide photo preview modal

    $('#label-photo').text('Upload Photo'); // label photo upload
}

function edit_offer(guid)
{
    method_type = 'update';
    $('#form')[0].reset(); // reseting form on modals
    $('.form-group').removeClass('has-error'); // remove error class
    $('.help-block').empty(); // rmove error 


    //Ajax Load offer data from ajax
    $.ajax({
        url : "<?= base_url('edit-offer')?>/" + guid,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id"]').val(data.guid);
            $('[name="heading"]').val(data.heading);
            $('[name="description"]').val(data.description);
            $('[name="start_date"]').val(data.start_date_time);
            $('[name="end_date"]').val(data.end_date_time);
            if(data.is_active==1)
            {
                $('#is_active_no').prop('checked', false);
               $('#is_active_yes').prop('checked', true); 
           }else{
                $('#is_active_yes').prop('checked', false);
                 $('#is_active_no').prop('checked', true); 
           }
         
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Offer'); // Set title to Bootstrap modal title

            $('#photo-preview').show(); // show photo preview modal

            if(data.photo)
            {
              
                $('#photo-preview div').html('<img src="'+base_url+'uploads/offers/'+data.photo+'" width="250px" height="250px" class="img-responsive">');

            }
            else
            {
                $('#label-photo').text('Upload Photo'); // label photo upload
                $('#photo-preview div').text('(No photo)');
            }


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error while getting data from ajax');
        }
    });
}

function reload_table()
{  
   $('#start_date').val(''); // reseeting filters applied
    $('#end_date').val('');
    $('#is_active').val('');
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //changing button text
    $('#btnSave').attr('disabled',true); //button disable 
    var url;

    if(method_type == 'add') {
        url = "<?= base_url('add-offer')?>";
    } else {
        url = "<?= base_url('update-offer')?>";
    }

    //  adding offer to database

    var formData = new FormData($('#form')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
            }
            else // display errror
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); 
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); 
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); // button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {  
            alert('Error while submiting  data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); // button enable 

        }
    });
}

function delete_offer(id)
{
    if(confirm('Are you sure delete this offer'))
    {
        //  delete offer to database
        $.ajax({
            url : "<?= base_url('delete-offer')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error while deleting offer');
            }
        });

    }
}
 
   


</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Offer Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Heading</label>
                            <div class="col-md-9">
                                <input name="heading" placeholder="Offer heading" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Description</label>
                            <div class="col-md-9">
                                <textarea name="description" placeholder="Short description" class="form-control"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Start Date</label>
                            <div class="col-md-9">
                                <input name="start_date" placeholder="dd/mm/yyyy" class="form-control datetimepicker" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">End Date</label>
                            <div class="col-md-9">
                                <input name="end_date" placeholder="dd/mm/yyyy" class="form-control datetimepicker" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group" id="photo-preview">
                            <label class="control-label col-md-3">Photo</label>
                            <div class="col-md-9">
                                (No photo)
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" id="label-photo">Upload Photo </label>
                            <div class="col-md-9">
                                <input name="photo" type="file" id="photo">
                                <span class="help-block"></span>
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="control-label col-md-3">Is Active</label>
                            <div class="col-md-2">
                                <label class="radio-inline"><input type="radio" name="is_active" id="is_active_yes" value="1" checked>Yes</label>
                            </div>
                             <div class="col-md-2">
                                <label class="radio-inline"><input type="radio" name="is_active" id="is_active_no" value="0">No</label>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>