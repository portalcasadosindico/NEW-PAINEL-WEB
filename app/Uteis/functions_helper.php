<?php

use App\Uteis\Formatacao;

if(!function_exists("emailValidation")) {

	function emailValido($email) {

		$pattern = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/";

		if(preg_match($pattern, $email)) {
			return true;
		}
		else {
			return false;
		}
		
	}
}

function validarCNPJ($cnpj) {
	$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
	// Valida tamanho
	if (strlen($cnpj) != 14)
        return false;
        
    $invalidos = [
        '00000000000000',
        '11111111111111',
        '22222222222222',
        '33333333333333',
        '44444444444444',
        '55555555555555',
        '66666666666666',
        '77777777777777',
        '88888888888888',
        '99999999999999'
    ];
    
    // Verifica se o CNPJ está na lista de inválidos
    if (in_array($cnpj, $invalidos)) {	
        return false;
    }        
	// Valida primeiro dígito verificador
	for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
	{
		$soma += $cnpj[$i] * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}
	$resto = $soma % 11;
	if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
		return false;
	// Valida segundo dígito verificador
	for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
	{
		$soma += $cnpj[$i] * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}
	$resto = $soma % 11;
	return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);    
}

function validarCPF($cpf) {
    $cpf = str_replace('-', '', $cpf);
    $cpf = str_replace('.', '', $cpf);

    //$cpf = preg_replace("/[^0-9]/", "", (string) $cpf);

    if ($cpf == '00000000000' || 
        $cpf == '11111111111' || 
        $cpf == '22222222222' || 
        $cpf == '33333333333' || 
        $cpf == '44444444444' || 
        $cpf == '55555555555' || 
        $cpf == '66666666666' || 
        $cpf == '77777777777' || 
        $cpf == '88888888888' || 
        $cpf == '99999999999') {
            return false;  
        }  

    // Valida tamanho
    if (strlen($cpf) != 11)
        return false;

    // Calcula e confere primeiro dígito verificador
    for ($i = 0, $j = 10, $soma = 0; $i < 9; $i++, $j--)
        $soma += $cpf[$i] * $j;

    $resto = $soma % 11;

    if ($cpf[9] != ($resto < 2 ? 0 : 11 - $resto))
        return false;

    // Calcula e confere segundo dígito verificador
    for ($i = 0, $j = 11, $soma = 0; $i < 10; $i++, $j--)
        $soma += $cpf[$i] * $j;

    $resto = $soma % 11;

    return $cpf[10] == ($resto < 2 ? 0 : 11 - $resto); 
}

function validarData($data) {
    
        if(strlen($data) < 8) {
            return false;
        }

        if(strpos($data, '/') === false) {
            return false;
        }

        $arrData = explode('/', $data);
        $dia = $arrData[0];
        $mes = $arrData[1];
        $ano = (isset($arrData[2]) ? $arrData[2] : 0);

        if(strlen($ano) < 4) {
            return false;
        }

        if($ano < (date('Y') - 100)) {
            return false;
        }

        if($ano > (date('Y') + 80)) {
            return false;
        }

        if(checkdate($mes, $dia, $ano)) {
            return true;
        }
        else {
            return false;
        }
    }

function removerMascara($dados, $tipo) {
    if($tipo == 'telefone') {
        $dados = str_replace('(', '', $dados);
        $dados = str_replace(')', '', $dados);
        $dados = str_replace('-', '', $dados);
        $dados = str_replace(' ', '', $dados);

        return $dados;
    }

    if($tipo == 'valor') {
        $dados = str_replace('R$ ', '', $dados);
        $dados = str_replace('.', '', $dados);
        $dados = str_replace(',', '.', $dados);

        return $dados;
    }

    if($tipo == 'percentual') {
        $dados = str_replace('%', '', $dados);
        $dados = str_replace('.', '', $dados);
        $dados = str_replace(',', '.', $dados);

        return $dados;
    }    

    if($tipo == 'cnpj') {
        $dados = str_replace('.', '', $dados);
        $dados = str_replace('/', '', $dados);
        $dados = str_replace('-', '', $dados);

        return $dados;        
    }

    if($tipo == 'cpf') {
        $dados = str_replace('.', '', $dados);
        $dados = str_replace('-', '', $dados);

        return $dados;        
    }  
    
    if($tipo == 'cep') {
        $dados = str_replace('-', '', $dados);

        return $dados;        
    }      
}

function formatarDataHora($data) {
    if($data != "") {
        return date("d/m/Y H:i:s", strtotime($data));    
    }
    else {
        return "";
    }
    
}

function formatarDataExtenso($data) {
    $arrData = explode('-', $data);
    $dataExtenso = $arrData[2] . ' de ';

    switch($arrData[1]) {
        case '1':
            $dataExtenso .= 'JANEIRO de ';
            break;
        case '2':
            $dataExtenso .= 'FEVEREIRO de ';
            break;            
        case '3':
            $dataExtenso .= 'MARÇO de ';
            break;            
        case '4':
            $dataExtenso .= 'ABRIL de ';
            break;
        case '5':
            $dataExtenso .= 'MAIO de ';
            break;
        case '6':
            $dataExtenso .= 'JUNHO de ';
            break;
        case '7':
            $dataExtenso .= 'JULHO de ';
            break;
        case '8':
            $dataExtenso .= 'AGOSTO de ';
            break;
        case '9':
            $dataExtenso .= 'SETEMBRO de ';
            break;
        case '10':
            $dataExtenso .= 'OUTUBRO de ';
            break;
        case '11':
            $dataExtenso .= 'NOVEMBRO de ';
            break;
        case '12':
            $dataExtenso .= 'DEZEMBRO de ';
            break;                                                                                                            
    }

    $dataExtenso .= $arrData[0];

    return $dataExtenso;
}

function formatarData($data) {
    if($data != "") {
        return date("d/m/Y", strtotime($data));    
    }
    else {
        return "";
    }
    
}

function formatarTelefone($telefone) {
    $telefone = Formatacao::somenteAlfaNumericos($telefone);
    if(strlen($telefone) == 11) {
        $tel = '(';
        $tel .= substr($telefone, 0, 2);
        $tel .= ') ';
        $tel .= substr($telefone, 2, 5);
        $tel .= '-';
        $tel .= substr($telefone, 7, 4);

        return $tel;
    }
    else {
        $tel = '(';
        $tel .= substr($telefone, 0, 2);
        $tel .= ') ';
        $tel .= substr($telefone, 2, 4);
        $tel .= '-';
        $tel .= substr($telefone, 6, 4);
        return $tel;        
    }
}

function formatarCEP($cep) {
    $cep = str_replace('-', '', $cep);
    return substr($cep, 0, 5) . '-' . substr($cep, 5, 3);
}


function formataData($data) {
    if(trim($data) != "") {
        return date("d/m/Y", strtotime($data));
    }
    elseif(trim($data == "" || is_null($data))) {
        return "";
    } 
}

function formatarNumero($valor) {
    return number_format($valor, 0, ',', '.');
}

function formatarValor($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function formatarPercentual($valor) {
    return number_format($valor, 2, ',', '.') . '%';
}

function formataDataMySql($data) {
    if(strpos($data, '-') == 0) {
        $data = explode("/", $data);
        return trim($data[2]) . "-" . trim($data[1]) . "-" . trim($data[0]); 
    }
    else {
        return $data;    
    }    
}

function formatarCPF($cpf) {
    $cpf = Formatacao::somenteAlfaNumericos($cpf);
    return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
}

function formatarCNPJ($cnpj) {
    $cnpj = Formatacao::somenteAlfaNumericos($cnpj);
    return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
}

function gerarSaltSenha() {
    $salt = '';

    for ($i = 0; $i < 8; $i++) {
        $salt .= md5(uniqid(rand(), true)) . sha1(uniqid(rand(), true));
    }

    $salt = base64_encode($salt);

    $size = rand(64, 255);

    return substr($salt, rand(0, strlen($salt) - $size - 1), $size);      
}

function criarUrlAmigavel($string) {

    $acentos = array(
        'À','Á','Ã','Â', 'à','á','ã','â',
        'Ê', 'É',
        'Í', 'í', 
        'Ó','Õ','Ô', 'ó', 'õ', 'ô',
        'Ú','Ü',
        'Ç', 'ç',
        'é','ê', 
        'ú','ü',
        );
    $remove_acentos = array(
        'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
        'e', 'e',
        'i', 'i',
        'o', 'o','o', 'o', 'o','o',
        'u', 'u',
        'c', 'c',
        'e', 'e',
        'u', 'u',
        );
    
    $string = str_replace($acentos, $remove_acentos, urldecode($string));
    
    return url_title($string, 'dash', TRUE);    
}

function criptografarSenha($senha, $salt) {
	$senhaCripto = md5($salt . $senha) . sha1($salt . $senha);
	return $senhaCripto;    
}

 
function getEnvironment() {
    // 1 - DEV
    // 2 - PRD
    $url = base_url();
    if(strpos($url, "localhost")) {
        return 1;    
    }
    else {
        return 2;
    }

    
}
        