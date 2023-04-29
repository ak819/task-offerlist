<!DOCTYPE html>
<html>
    <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Offers</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">

     <link href="<?php echo base_url('assets/countdown/jquery.countdownTimer.css')?>" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url('assets/countdown/jquery.countdownTimer.min.js')?>"></script>


        <style>
            @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");
            body {
                font-family: "Poppins", sans-serif;
            }
            img {
                max-width: 100%;
            }
            .card {
                width: 100%;
                float: left;
                background: #fff;
                box-shadow: 0px 0px 6px #00000057;
                border-radius: 10px;
                overflow: hidden;
                transition: all 0.3s ease-in-out;
                margin-top: 20px;
            }
            .card:hover img {
                transform: scale(1.1);
            }
            .card img {
                transition: all 0.3s ease-in-out;
            }
            .img-part {
                overflow: hidden;
            }
            .text-part {
                width: 100%;
                float: left;
                padding: 15px;
            }
            .text-part strong {
                font-size: 20px;
                margin: 0px 0px 10px 0px;
                display: block;
                font-weight: 600;
            }
            .text-part p {
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1><center>Offers</center></h1>
            <div class="row" id="offerlist">


               
                
              
               
               
            </div>
             <div  id="pagination"></div>
             <div id="countdowntimer"><span id="future_date"><span></div>
        </div>

        <script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
        <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>


        <script type="text/javascript">
            
      

    loadoffers(1); // default call to get offer from ajax call

      $('#pagination').on('click','a',function(e){ // on click pagination load offer by page number
       e.preventDefault(); 
      
       var pageno = $(this).attr('data-ci-pagination-page');
       $('#offerlist').empty();
      
       loadoffers(pageno);
       

     });

        function loadoffers(pagno){ // ajax call to load offer
       
        $.ajax({
        url:'<?=base_url()?>display-offer/'+pagno,
        type:'post',
        dataType:'json',
        data:{ },

      success: function(response){
       
      $('#pagination').html(response.pagination); // adding pagination

       if(response.offers!="")
       {
        
       $('#offerlist').append(response.offers); // appending offer list

       }
      

        }

       });
     }




        </script>
    </body>
</html>
