<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  function getGUID(){
      
                $charid = strtolower(md5(uniqid(rand(), true)));
                $hyphen = chr(45);// "-"
                $uuid = chr(123)
                    .substr($charid, 0, 8).$hyphen
                    .substr($charid, 8, 4).$hyphen
                    .substr($charid,12, 4).$hyphen
                    .substr($charid,16, 4).$hyphen
                    .substr($charid,20,12)
                    .chr(125);
               $string = substr($uuid, 1, -1);
              // $s1clean = str_replace(["-", "–"], '', $string);
               //echo $string;
               return $string;
        }


function uplodeImage($gallery_path,$imageName)
     {      
            $config['upload_path']   = $gallery_path;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name']    = round(microtime(true) * 1000);
            $CI =& get_instance();
            $CI->load->library('upload', $config);
            $CI->upload->initialize($config);

            if(!$CI->upload->do_upload($imageName))
            { 

              $data['inputerror'][] = 'photo';
            $data['error_string'][] = 'Upload error: '.$CI->upload->display_errors(); //show ajax error
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
            }
             $image_data = $CI->upload->data(); 
            return  $image_data['file_name'];      
     }
 

  function datetimeformat($date)
  {
                    
  return date('d/m/Y h:i A',strtotime($date));

  }

  function dateformat($date)
  {
     return date('d/m/Y',strtotime($date));
  }

  

  function convert_datetimeto24hr($date)
  {
      $replaceslash=str_replace('/','-', $date);
      return date('Y-m-d H:i', strtotime($replaceslash));

  }

  function date_dbformate($date)
  {
       $replaceslash=str_replace('/','-', $date);
      return date('Y-m-d', strtotime($replaceslash));

  }