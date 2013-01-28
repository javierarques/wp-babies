<?php
/*
Plugin Name: WP Babies!
Plugin URI: http://cuidadoinfantil.net
Description: Crea posts a partir de una lista nombres de bebés
Version: 1.0
Author: Harvey Arquette
Author URI: http://artvisual.net
License: A "Slug" license name e.g. GPL2
*/


define('WP_BABIES_TABLE', 'babies');
define('WP_BABIES_NAME', 'WP Babies!');
define('WP_BABIES_URL', WP_PLUGIN_URL . '/wp-babies' );	


require_once( dirname( __FILE__ ) . '/admin.php' );
require_once( dirname( __FILE__ ) . '/core.php' );



function wp_babies_install(){
	
	/*
	add_option("wp_babies_schedule_time", 'twicedaily');
	
	
	if (! wp_next_scheduled('wp_babies_create_post_hook')) 
		wp_schedule_event( time(), 'twicedaily', 'wp_babies_create_post_hook' );

	*/
}

register_activation_hook( __FILE__, 'wp_babies_install' );



function wp_babies_uninstall(){

	/*
	delete_option('post_generator_schedule_time');
	delete_option('post_generator_options'); 
	*/
	unregister_taxonomy('origen', 'post');
	unregister_taxonomy('sexo', 'post');
	unregister_taxonomy('letra', 'post');
	
}

register_deactivation_hook( __FILE__, 'wp_babies_uninstall' );


function wp_babies_add_menu(){	
	
	add_options_page(WP_BABIES_NAME, WP_BABIES_NAME, 10, basename(__FILE__), 'wp_babies_admin');

}
	
add_action('admin_menu', 'wp_babies_add_menu'); 


function wp_babies_init () {
	
  // Añado la texonomía "origen" (si no existe)
  if (! taxonomy_exists('origen')) {
	  $labels = array(
	    'name' => "Orígenes",
	    'singular_name' => "Origen",
	    'search_items' =>  "Buscar orígenes",
	    'popular_items' =>  "Orígenes populares",
	    'all_items' => "Todos los orígenes",
	    'parent_item' => null,
	    'parent_item_colon' => null,
	    'edit_item' => "Editar origen", 
	    'update_item' => "Actualizar origen",
	    'add_new_item' => "Nuevo origen",
	    'new_item_name' => "Nuevo nombre de origen",
	    'separate_items_with_commas' => "Orígenes separados por comas",
	    'add_or_remove_items' => "Añadir o quitar orígenes",
	    'choose_from_most_used' => "Elige entre los orígenes más usados",
	    'menu_name' => "Orígenes",
	  ); 
	
	  register_taxonomy('origen',array( 'post' ),array(
	  	'public' => true,
	    'hierarchical' => false,
	    'labels' => $labels,
	    'show_ui' => true,
	    'query_var' => 'origen',
	    'rewrite' => array( 'slug' => 'origen', 'with_front' => false ),
	  ));

  }
  
	if (! taxonomy_exists('sexo')) {
  // Añado la texonomía "sexo" (si no existe)
	  $labels = array(
	    'name' => "Sexos",
	    'singular_name' => "Sexo",
	    'search_items' =>  "Buscar Sexos",
	    'popular_items' =>  "Sexos populares",
	    'all_items' => "Todos los Sexos",
	    'parent_item' => null,
	    'parent_item_colon' => null,
	    'edit_item' => "Editar sexo", 
	    'update_item' => "Actualizar sexo",
	    'add_new_item' => "Nuevo sexo",
	    'new_item_name' => "Nuevo nombre de sexo",
	    'separate_items_with_commas' => "Sexos separados por comas",
	    'add_or_remove_items' => "Añadir o quitar sexos",
	    'choose_from_most_used' => "Elige entre los sexos más usados",
	    'menu_name' => "Sexos",
	  ); 
	
	  register_taxonomy('sexo',array( 'post' ),array(
	  	'public' => true,
	    'hierarchical' => false,
	    'labels' => $labels,
	    'show_ui' => true,
	    'query_var' => 'sexo',
	    'rewrite' => array( 'slug' => 'sexo', 'with_front' => true ),
	  ));

	}
	
	if (! taxonomy_exists('letra')) {
  // Añado la texonomía "letra" (si no existe)
	  $labels = array(
	    'name' => "Letras de inicio",
	    'singular_name' => "Letra de inicio",
	    'search_items' =>  "Buscar letras de inicio",
	    'popular_items' =>  "Letras populares",
	    'all_items' => "Todas las letras",
	    'parent_item' => null,
	    'parent_item_colon' => null,
	    'edit_item' => "Editar letra", 
	    'update_item' => "Actualizar letra",
	    'add_new_item' => "Nueva letra",
	    'new_item_name' => "Nuevo nombre de letra",
	    'separate_items_with_commas' => "Letras separadas por comas",
	    'add_or_remove_items' => "Añadir o quitar letras",
	    'choose_from_most_used' => "Elige entre las letras más usadas",
	    'menu_name' => "Sexos",
	  ); 
	
	  register_taxonomy('letra',array( 'post' ),array(
	  	'public' => true,
	    'hierarchical' => false,
	    'labels' => $labels,
	    'show_ui' => true,
	    'query_var' => 'letra',
	    'rewrite' => array( 'slug' => 'letra', 'with_front' => true ),
	  ));

	}
	
	
	
}

add_action('init', 'wp_babies_init');



?>