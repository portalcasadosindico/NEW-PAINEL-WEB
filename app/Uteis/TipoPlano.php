<?php


namespace App\Uteis;

class TipoPlano
{
    public static $AFILIADO = 0;
    public static $PARCEIRO = 1;

    public static function getAllStatus(){
        return    [
            self::$AFILIADO,
            self::$PARCEIRO
        ];
    }

    public static function getSelectAllStatus($name, $id, $texto_entrada="Selecione um tipo", $selected=null, $onchange=null){
        $mstatus = self::getAllStatus();
        $html = "<select class='form-control' id='$id' name='$name' onchange='$onchange'>";
        if($texto_entrada) $html .= "<option value=''>$texto_entrada</option>";
        foreach($mstatus as $status){
            $texto = self::getLabel($status);
            $cor = self::getCor($status);
            if($selected==$status)
                $html .= "<option value='$status' selected >".$texto."</option>";
            else
                $html .= "<option value='$status' >".$texto."</option>";
        }
        $html .= "</select>";
        return $html;
    }

    static function getLabel($status)
    {
        switch ($status) {
            case self::$AFILIADO:
                return "Afiliado";
            case self::$PARCEIRO:
                return "Parceiro";
        }
    }

    static function getCor($status)
    {
        switch ($status) {
            case self::$AFILIADO: return "#ffff00";
            case self::$PARCEIRO: return "#795548";
        }
    }

    static function getColorTheme($status)
    {
        switch ($status) {
            case self::$AFILIADO: return "info";
            case self::$PARCEIRO: return "primary";
        }
    }
}