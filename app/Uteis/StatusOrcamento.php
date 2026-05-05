<?php


namespace App\Uteis;

class StatusOrcamento
{

    public static $ANALISANDO_CANDIDATOS = 1;
    public static $ANALISANDO_ORCAMENTOS = 2;
    public static $AGUARDANDO_CONTRATO = 3;
    public static $EM_EXECUCAO = 4;
    public static $FINALIZADO = 5;
    public static $CANCELADO_PELO_ADMIN = 6;
    public static $CANCELADO_PELO_FRANQUEADO = 7;
    public static $CANCELADO_PELO_SINDICO = 8;
    public static $CANCELADO_PELO_AFILIADO = 9;
    public static $CONTRATO_ASSINADO = 10;

    public static function getAllStatus()
    {
        return    [
            self::$ANALISANDO_CANDIDATOS,
            self::$ANALISANDO_ORCAMENTOS,
            self::$AGUARDANDO_CONTRATO,
            self::$CONTRATO_ASSINADO,
            self::$EM_EXECUCAO,
            self::$FINALIZADO,
            self::$CANCELADO_PELO_ADMIN,
            self::$CANCELADO_PELO_FRANQUEADO,
            self::$CANCELADO_PELO_SINDICO,
            self::$CANCELADO_PELO_AFILIADO
        ];
    }

    public static function getAllStatusAfiliado()
    {
        return    [
            self::$AGUARDANDO_CONTRATO,
            self::$CONTRATO_ASSINADO,
            self::$EM_EXECUCAO,
            self::$FINALIZADO,
            self::$CANCELADO_PELO_AFILIADO
        ];
    }

    public static function getAllStatusSindico()
    {
        return    [
            self::$ANALISANDO_CANDIDATOS,
            self::$ANALISANDO_ORCAMENTOS,
            self::$AGUARDANDO_CONTRATO,
            self::$CONTRATO_ASSINADO,
            self::$EM_EXECUCAO,
            self::$FINALIZADO,
            self::$CANCELADO_PELO_SINDICO
        ];
    }

    public static function getAllStatusFranqueado()
    {
        return    [
            self::$ANALISANDO_CANDIDATOS,
            self::$ANALISANDO_ORCAMENTOS,
            self::$AGUARDANDO_CONTRATO,
            self::$CONTRATO_ASSINADO,
            self::$EM_EXECUCAO,
            self::$FINALIZADO,
            self::$CANCELADO_PELO_FRANQUEADO
        ];
    }

    public static function getSelectAllStatusFranqueadoSearch($name, $id, $texto_entrada = "Selecione um status", $selected = null, $function_change = null)
    {
        $mstatus = self::getAllStatusFranqueado();
        $html = "<select class='form-control' id='$id' name='$name' onchange='$function_change'>";
        $html .= "<option value=''>$texto_entrada</option>";
        foreach ($mstatus as $status) {
            $texto = self::getLabel($status);
            $cor = self::getCor($status);
            if ($selected == $status)
                $html .= "<option value='$texto' selected >" . $texto . "</option>";
            else
                $html .= "<option value='$texto' >" . $texto . "</option>";
        }
        $html .= "</select>";
        return $html;
    }

    public static function getSelectAllStatusFranqueado($name, $id, $texto_entrada = "Selecione um status", $selected = null, $function_change = null)
    {
        $mstatus = self::getAllStatusFranqueado();
        $html = "<select class='form-control' id='$id' name='$name' onchange='$function_change'>";
        $html .= "<option value=''>$texto_entrada</option>";
        foreach ($mstatus as $status) {
            $texto = self::getLabel($status);
            $cor = self::getCor($status);
            if ($selected == $status)
                $html .= "<option value='$status' selected >" . $texto . "</option>";
            else
                $html .= "<option value='$status' >" . $texto . "</option>";
        }
        $html .= "</select>";
        return $html;
    }

    public static function getSelectAllStatus($name, $id, $texto_entrada = "Selecione um status", $selected = null, $onChange = null)
    {
        $mstatus = self::getAllStatus();
        $html = "<select class='form-control' id='$id' name='$name' onchange='$onChange' required>";
        if ($texto_entrada) $html .= "<option value=''>$texto_entrada</option>";
        foreach ($mstatus as $status) {
            $texto = self::getLabel($status);
            $cor = self::getCor($status);
            if ($selected == $status)
                $html .= "<option value='$status' selected >" . $texto . "</option>";
            else
                $html .= "<option value='$status' >" . $texto . "</option>";
        }
        $html .= "</select>";
        return $html;
    }

    public static function getSelectAllAfiliado($name, $id, $texto_entrada = "Selecione um status", $selected = null)
    {
        $mstatus = self::getAllStatusAfiliado();
        $html = "<select class='form-control' id='$id' name='$name'>";
        if ($texto_entrada) $html .= "<option value=''>$texto_entrada</option>";
        foreach ($mstatus as $status) {
            $texto = self::getLabel($status);
            $cor = self::getCor($status);
            if ($selected == $status)
                $html .= "<option value='$status' selected >" . $texto . "</option>";
            else
                $html .= "<option value='$status' >" . $texto . "</option>";
        }
        $html .= "</select>";
        return $html;
    }

    public static function getSelectAllSindico($name, $id, $texto_entrada = "Selecione um status", $selected = null)
    {
        $mstatus = self::getAllStatusSindico();
        $html = "<select class='form-control' id='$id' name='$name'>";
        if ($texto_entrada) $html .= "<option value=''>$texto_entrada</option>";
        foreach ($mstatus as $status) {
            $texto = self::getLabel($status);
            $cor = self::getCor($status);
            if ($selected == $status)
                $html .= "<option value='$status' selected >" . $texto . "</option>";
            else
                $html .= "<option value='$status' >" . $texto . "</option>";
        }
        $html .= "</select>";
        return $html;
    }


    static function getLabel($status)
    {
        switch ($status) {
            case self::$ANALISANDO_CANDIDATOS:
                return "Analisando candidatos";
            case self::$ANALISANDO_ORCAMENTOS:
                return "Em cotação";
            case self::$AGUARDANDO_CONTRATO:
                return "Aguardando Contrato/Assinaturas";
            case self::$CONTRATO_ASSINADO:
                return "Contrato assinado";
            case self::$EM_EXECUCAO:
                return "Em execução";
            case self::$FINALIZADO:
                return "Concluido";
            case self::$CANCELADO_PELO_ADMIN:
                return "Cancelado pelo administrador";
            case self::$CANCELADO_PELO_FRANQUEADO:
                return "Cancelado pelo franqueado";
            case self::$CANCELADO_PELO_SINDICO:
                return "Cancelado pelo síndico";
            case self::$CANCELADO_PELO_AFILIADO:
                return "Cancelado pelo afiliado";
            default:
                return '--';
        }
    }

    static function getLabelAfiliado($status)
    {
        switch ($status) {
            case self::$ANALISANDO_CANDIDATOS:
                return "Aberto";
            case self::$ANALISANDO_ORCAMENTOS:
                return "Em cotação";
            case self::$AGUARDANDO_CONTRATO:
                return "Aguardando contrato";
            case self::$CONTRATO_ASSINADO:
                return "Contrato assinado";
            case self::$EM_EXECUCAO:
                return "Em execução";
            case self::$FINALIZADO:
                return "Concluido";
            case self::$CANCELADO_PELO_ADMIN:
                return "Cancelado pelo administrador";
            case self::$CANCELADO_PELO_FRANQUEADO:
                return "Cancelado pelo franqueado";
            case self::$CANCELADO_PELO_SINDICO:
                return "Cancelado pelo síndico";
            case self::$CANCELADO_PELO_AFILIADO:
                return "Cancelado pelo afiliado";
        }
    }

    static function getCor($status)
    {
        switch ($status) {
            case self::$ANALISANDO_CANDIDATOS:
                return "#ffff00";
            case self::$ANALISANDO_ORCAMENTOS:
                return "#795548";
            case self::$AGUARDANDO_CONTRATO:
                return "#1976d2";
            case self::$CONTRATO_ASSINADO:
                return "#00c853";
            case self::$EM_EXECUCAO:
                return "#00c853";
            case self::$FINALIZADO:
                return "#6200ea";
            case self::$CANCELADO_PELO_ADMIN:
                return "#d32f2f";
            case self::$CANCELADO_PELO_FRANQUEADO:
                return "#d32f2f";
            case self::$CANCELADO_PELO_SINDICO:
                return "#d32f2f";
            case self::$CANCELADO_PELO_AFILIADO:
                return "#d32f2f";
        }
    }

    static function getColorTheme($status)
    {
        switch ($status) {
            case self::$ANALISANDO_CANDIDATOS:
                return "info";
            case self::$ANALISANDO_ORCAMENTOS:
                return "info";
            case self::$AGUARDANDO_CONTRATO:
                return "secondary";
            case self::$CONTRATO_ASSINADO:
                return "primary";
            case self::$EM_EXECUCAO:
                return "success";
            case self::$FINALIZADO:
                return "#6200ea";
            case self::$CANCELADO_PELO_ADMIN:
                return "danger";
            case self::$CANCELADO_PELO_FRANQUEADO:
                return "danger";
            case self::$CANCELADO_PELO_SINDICO:
                return "danger";
            case self::$CANCELADO_PELO_AFILIADO:
                return "danger";
        }
    }
}
