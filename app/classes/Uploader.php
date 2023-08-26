<?php 

namespace app\classes;


class Uploader{

    /**
     * $_FILES['input_name']
     * @param array 
     * @return string
     */
    public function upload($file)
    {
        move_uploaded_file($file['tmp_name'],PATH['temp'].'/'.$file['name']);
        return $file['name'];
    }

    /**
     * @param string
     * @return void
     */
    public function delete($filename)
    {
        unlink(PATH['temp'].'/'.$filename);   
    }

}