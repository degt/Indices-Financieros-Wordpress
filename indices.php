<?php
/*
Plugin Name: Indices bursatiles
Plugin URI:
Description:  Lee y muestra los indices bursatiles
Version: 1.0
Author: Daniel Gutierrez
Author URI: http://www.degt.cl



*/



class Indices{
	var $indices = array(
		array(
			"nombre" => "Dólar :",
			"id" => "dolar",
			"url" => "http://www.terra.cl/valores/",
			"palabra" => "DOLAR OBSERVADO",
			"prefix" => "$"
		),
		array(
			"id" => "uf",
			"url" => "http://www.terra.cl/valores/",
			"palabra" =>"UF :",
			"prefix" => "$"
		),
		array(
			"id" => "utm",
			"url" => "http://www.terra.cl/valores/",
			"palabra" => "UTM :",
			"prefix" => "$"
		),
		array(
			"id" => "ipsa",
			"url" => "http://www.terra.cl/valores/",
			"palabra" =>  "IPSA :",
			"sufix" => "%"
		),
		array(
			"id" => "igpa",
			"url" => "http://www.terra.cl/valores/",
			"palabra" => "IGPA :"	,
			"sufix" => "%"
		),
		array(
			"id" => "ipc",
			"url" =>  "http://www.terra.cl/valores/",
			"palabra" => "IPC :",
			"sufix" => "%"
		),
		array(
			"id" => "euro",
			"url" =>  "http://www.terra.cl/valores/",
			"palabra" => "EURO :",
			"sufix" => "$"
		)
	);
	
	function Indices(){
		//Scripts
		if(!is_admin()){
			wp_enqueue_script( "indices", get_bloginfo('url').'/wp-content/plugins/indices/indices.js', array( 'jquery'), "1.0", false );
		}

		//Hooks
		add_action('init', array($this, 'hooks'));
	}
	
	function hooks(){
		if($_GET['action'] == 'indices'){
			$this->obtener_indices();
			exit;
		}
	}
	
	function obtener_indice($url, $palabra, $prefix ="", $sufix="" ){

		$curl_handler = curl_init();
		curl_setopt($curl_handler, CURLOPT_URL, $url);
		curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handler, CURLOPT_BINARYTRANSFER, 1); 
		$f = curl_exec($curl_handler);
		
		$fclean = strip_tags(trim($f));
		$pos = strpos ($fclean, $palabra);
		
		if ($pos){
			$glosa = " "; 
			
			$monto_ = explode(":", trim(substr($fclean, $pos, 50)));
			$monto = trim($monto_[1]);
			$valor =  $prefix.$monto.$sufix;
		}
		curl_close($curl_handler);
		return $valor;
	}
	
	function obtener_indices(){
	
		$indices = $this->indices;
		
		$data = unserialize(get_option("indices_bursatiles"));
		
		
		$data_stamp = $data[0];
		$actual = mktime();
		$day_in_seconds = 60*60;
		
		
		if(($actual - $data_stamp) >= $day_in_seconds){
		
			
			//read data
			foreach($indices as $var){
				$valores[$var['id']] = $this->obtener_indice($var['url'], $var['palabra'], $var['prefix'], $var['sufix']);
			}
			
			$stamp = mktime();
			
			$save = array($stamp, $valores);
			
			$save = serialize($save);
			
			update_option( "indices_bursatiles", $save ); 
			
		}else{
			$valores = $data[1];
		}
		
		echo json_encode($valores);
	}

}

$indices = new Indices();



?>