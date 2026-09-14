<?php


namespace App\Uteis;

	/**
	 * Criado em 17/11/2011
	 * Classe responsável pelas operações com a url
	 * @author Renato PAranaguá da Silva
	 * @version 1.0
 	*/
class Url {

	/**
	 * Criado em 17/11/2011
	 * Função que desmonta a url
	 * @author Renato Paranaguá da Silva
	 * @return
	 * @version 1.0
 	*/
	 public static function baseURL() {
        $mcaminhos_url = explode("/", @$_SERVER['PATH_INFO']);
        $modulo = @$mcaminhos_url[1];
		return "http://" . $_SERVER ['SERVER_NAME'] . ":" . $_SERVER ['SERVER_PORT'] . "/" . $modulo . "/";
	}


	public static function uri() {

		//transforma URL em matriz
		$muri = explode ( "/", $_SERVER ["REQUEST_URI"] );

        $mdados ["modulo"] = @$muri [1];

		//pega controlador
		$mdados ["control"] = @$muri [2];
		//pega acao do controlador
        $mdados ["acao"] = @$muri [3];
        //pega a base URL
        $mcaminhos_url = explode("/", $_SERVER['PATH_INFO']);
        $modulo = $mcaminhos_url[1];
		$mdados["base"] = "http://" . $_SERVER ['SERVER_NAME'] . ":" . $_SERVER ['SERVER_PORT'] . "/" . $modulo . "/";

		return $mdados;
	}

	/**
	 * Criado em 17/11/2011
	 * Função para redirecionamento
	 * @author Renato Paranaguá da Silva
	 * @param [ARRAY] $mdados
	 * @return
	 * @example $mdados = array("acao" => " ", "controller" => " "); URL::redireciona($mdados);
	 * @example $mdados = array("acao" => " ", "controller" => " ", "parametros" => array("param1" => "", "param2" => "")); URL::redireciona($mdados);
	 * @version 1.0
 	*/

	//redireciona URL
	public static function redireciona( $mdados ) {

		$vURL = "http://" . $_SERVER ['SERVER_NAME'] . str_replace ( "/index.php", "", $_SERVER ['SCRIPT_NAME'] );

		$vURL .= "/$mdados";

    	//redireciona
		header ( "Location: $vURL" );

	}

	/**
	 * Criado em 17/11/2011
	 * Função para pegar valor da url
	 * @author Renato Paranaguá da Silva
	 * @param [STRING] $vvariavel
	 * @return
	 * @version 1.0
 	*/

	//pega o valor de um parametro da URL
	public static function parametroUrl( $vvariavel ) {

		$muri = explode ( "/", $_SERVER ["REQUEST_URI"] );
		foreach ( $muri as $vuri )
			if ($vuri == $vvariavel) {
				array_shift ( $muri );
				break;
			} else
				array_shift ( $muri );

		return isset ( $muri [0] ) ? $muri [0] : null;

	}

	/**
	 * Monta a URL pra abrir/exibir um arquivo salvo em `caminho_imagem`/`arquivo`,
	 * cobrindo os dois esquemas de storage que convivem no banco (ver
	 * [[saveincloud_deploy]]/migração do storage, 2026-06-18):
	 * - uploads novos, feitos via app/API .NET: caminho começa com "user-{id}/..."
	 *   e o arquivo físico vive em /var/www/webroot/dotnet-api/storage/, não no
	 *   storage do Laravel - Storage::url() nunca acha esses arquivos (sessão
	 *   2026-09-14, bug real: Contrato Social/Cartão CNPJ enviados pelo app
	 *   davam 404 no Painel).
	 * - uploads antigos, feitos direto pelo Painel: caminho sem esse prefixo,
	 *   arquivo vive no storage do próprio Laravel - usa Storage::url() normal.
	 *
	 * @param string|null $path
	 * @return string|null
	 */
	public static function documentUrl( ?string $path ) {
		if (empty($path)) {
			return null;
		}

		if (str_starts_with($path, 'user-')) {
			return '/dotnet-api/storage/' . ltrim($path, '/');
		}

		return \Illuminate\Support\Facades\Storage::url($path);
	}

}

?>
