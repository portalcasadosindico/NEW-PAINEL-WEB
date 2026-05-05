<?php

use GuzzleHttp\Psr7\Request;

class UploadImagemAjaxController{

    public function upload(Request $request){
            $res = array(
                'location' => ""//"../".Formatacao::uploadArquivos($_FILES['file'], "imagens/uploads")
            );
            echo json_encode($res);
    }

}