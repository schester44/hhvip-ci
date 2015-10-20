<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Images {

        private $CI;

      function __construct()
    {
        $this->CI =& get_instance();
    }

    function uploadLocalFile($uploads, $sfname, $urlslug, $user_id=NULL) {
          $this->CI->output->set_header('Content-Type: application/json; charset=utf-8');

          $user_name = (isset($user_id)) ? username($user_id) : $this->CI->ion_auth->user()->row()->username;

                    $allowedExts = array("gif", "jpeg", "jpg", "png");
                    $temp = explode(".", $uploads["name"]);
                    $extension = end($temp);
                    if ((($uploads["type"] == "image/gif")
                    || ($uploads["type"] == "image/jpeg")
                    || ($uploads["type"] == "image/jpg")
                    || ($uploads["type"] == "image/pjpeg")
                    || ($uploads["type"] == "image/x-png")
                    || ($uploads["type"] == "image/png"))
                    && ($uploads["size"] < 2000000)
                    && in_array($extension, $allowedExts)) {
                      if ($uploads["error"] == 0) {
                                if (!file_exists(FCPATH . '/asset_uploads/' . $user_name.'/'.$urlslug)) {
                                   mkdir(FCPATH . '/asset_uploads/' . $user_name.'/'.$urlslug, 0755, true);
                                   file_put_contents(FCPATH . '/asset_uploads/' . $user_name.'/'.$urlslug.'/index.html', 'index.html');
                                    }
                                move_uploaded_file($uploads['tmp_name'], FCPATH . '/asset_uploads/'.$user_name.'/'.$urlslug.'/'.$sfname.'.'.$extension);
                      } else {
                    $output_array = array('validation' => 'error', 'response'=>'error', 'message' => 'Unable to upload file.');
                    $this->CI->output->set_output(json_encode($output_array));
                     
                      }
                  } else {
                    $output_array = array('validation' => 'error', 'response'=>'error', 'extension'=>$extension, 'message' => 'Please upload an image of 1024kb or less.');
                    $this->CI->output->set_output(json_encode($output_array));
                     }
        }

       function uploadRemoteFile($urlimage, $filename, $urlslug,$user_id=NULL) {  
        
        $this->CI->output->set_header('Content-Type: application/json; charset=utf-8');

        $user_name = (isset($user_id)) ? username($user_id) : $this->CI->ion_auth->user()->row()->username;

        //get file info so we can check for allowed extensions
         $file_parts = pathinfo($urlimage);
         $exts = array('jpg','gif','png','jpeg');

        if (isset($file_parts['extension']) && in_array($file_parts['extension'], $exts)) {
            //check the exif data to ensure its a valid image type

            $image_exists = @fopen($urlimage, "r");
                 if ($image_exists === false) {

                $output_array = array('validation' => 'error', 'response'=>'error', 'message' => 'Check image URL. Supplied URL does not appear to be an image.');
                $this->CI->output->set_output(json_encode($output_array));
                

                 } else {
                 fclose($image_exists);

                    if(exif_imagetype($urlimage)) { 
                        //send back json error for modal
                        //if folder for song does not  exist, make the folder
                        if (!file_exists(FCPATH . 'asset_uploads/'.$user_name.'/'.$urlslug)) {
                                 mkdir(FCPATH . 'asset_uploads/'.$user_name.'/'.$urlslug, 0755, true);
                                file_put_contents(FCPATH . 'asset_uploads/'.$user_name.'/'.$urlslug.'/index.html', 'index.html');  
                            }
                                //get the image
                            $image = file_get_contents($urlimage);
                        //save the image
                            file_put_contents(FCPATH . 'asset_uploads/'.$user_name.'/'.$urlslug.'/'.$filename.'.'.$file_parts['extension'], $image);
                         return true;
                    }
                }
        } else {  
            //send back json error for modal
              $output_array = array('validation' => 'error', 'response'=>'error', 'message' => 'Image filetype not supported. JPG or PNG only please!'); 
              $this->CI->output->set_output(json_encode($output_array));
      
        }
    }


        function resizeImage($dir, $filename, $extension, $size) {

                if (file_exists($dir)) {

               $this->CI->load->library('image_lib'); 
                    $config['image_library'] = 'gd2';
                    $config['upload_path'] = $dir;
                    $config['source_image'] = $dir . $filename . '.' . $extension;
                    $config['new_image']    = $dir . $size . '_'.  $filename . '.' . $extension;
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['create_thumb'] = FALSE;
                    $config['maintain_ratio'] = TRUE;
                    $config['width']     = $size;
                    $config['height']   = $size;
                $this->CI->image_lib->initialize($config);
                $this->CI->image_lib->resize();
                $this->CI->image_lib->clear();
                    return true;
                } else {
                return false;
            }
        }
}

/* End of file Remote_images.php */