<?php
/*
Plugin Name: Indices bursatiles
Plugin URI:
Description:  Lee y muestra los indices bursatiles
Version: 2.0
Author: Daniel Gutierrez
Author URI: http://www.degt.cl


*/



class Indices{
	var $source = 'https://www.df.cl/';
	var $indices = array(
		"dolar-us",
		"uf-hoy",
		"utm",
		"cobre"
	);

	//Indices disponibles
	//"bovespa","cobre","dolar-obs","dolar-us","euro","ftse","gas-natural","ibex","imacec","ipc","ipsa","ivp","nikkei","oro","peso-arg","petr-brent","petr-wti","plata","real-bras","uf-hoy","utm","yen-jap

	function Indices(){
		add_action( 'wp_enqueue_scripts', array($this,'enqueue_scripts') );
		add_action('init', array($this, 'hooks'));
		add_shortcode( 'indices', array($this, 'shortcode') );
	}

	function get_data(){
		//Read source
		$url = $this->source;
		$curl_handler = curl_init();
		curl_setopt($curl_handler, CURLOPT_URL, $url);
		curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handler, CURLOPT_BINARYTRANSFER, 1); 
		$f = curl_exec($curl_handler);

		//parse source
		$document = new DOMDocument();
		libxml_use_internal_errors(true);
		$document->loadHTML(mb_convert_encoding($f, 'HTML-ENTITIES', "UTF-8"));
		libxml_clear_errors();

		foreach($document->getElementsByTagName('li') as $tag){
			if($tag->getAttribute('data-func') == 'indicadores'){
				$slug = sanitize_title($tag->textContent);
				$obj = new stdClass();
				$obj->name = $tag->textContent;
				$obj->value = $tag->getAttribute('data-value');
				$i[$slug] = $obj;
			}
		}


		foreach($this->indices as $var){
			$response[$var] = $i[$var];
		}

		return $response;
	}

	function get_indices(){

		//Cache
		$data = get_option("indices_bursatiles");
		$data_stamp = $data[0];
		$day_in_seconds = 3600;

		if((time() - $data_stamp) >= $day_in_seconds){
			//read and save new data
			$valores = $this->get_data();
			$save = array(time(), $valores);
			update_option( "indices_bursatiles", $save ); 
			return $valores;
		}else{
			//return from cache
			return $data[1];
		}

	}

	function hooks(){
		if(@$_GET['action'] == 'indices'){
			echo json_encode($this->get_indices());
			exit;
		}
	}

	function enqueue_scripts(){
		if(!is_admin()){
			wp_enqueue_script( "indices", get_bloginfo('url').'/wp-content/plugins/indices/indices.js', array( 'jquery'), "1.0", false );
		}
	}

	function shortcode(){
		return '<div id="indices"></div>';
	}

}

$indices = new Indices();



?>