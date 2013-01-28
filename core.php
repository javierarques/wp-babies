<?php


function wp_babies_create_post ($nombre_id){

	global $wpdb;
	$wpdb->show_errors;
	$table_name = $wpdb->prefix . WP_BABIES_TABLE;
	
	if (! isset($nombre_id) || ! $data = wp_babies_get_name ($nombre_id))
		return false;

	$contenido = wp_babies_post_content($data);
	
	$titulo = ucfirst($data->nombre);
	
	$descripcion = "$data->nombre es un nombre de $data->sexo de origen $data->origen. ";
	if (! empty($data->significado)) $descripcion .= "Significado de $data->nombre: $data->significado";
	
	$my_post = array(
	 'post_title' => $titulo,
	 'post_content' => $contenido,
	 'post_status' => 'publish',
	 'post_author' => 1,
	 'post_excerpt' => $descripcion
	);
	
	$id_post = false;
	
	// Insert the post into the database
	if ($id_post = wp_insert_post( $my_post )){
	
		$wpdb->update($table_name, array('post_id' => $id_post, 'status' => 1), array('id' => $nombre_id));
		
		$letra = substr(replace_accents($titulo), 0, 1);
		$sexo = $data->sexo;
		$origen = $data->origen;
		
		
		//dump($data);
		// Creamos y añadimos las etiquetas al post
		
		if ($sexo == 'niño') {
			wp_set_post_categories($id_post, array(3));
		}else {
			wp_set_post_categories($id_post, array(13));
		}
		
		
	  	$post_tags = array("Nombres de $sexo", "Nombres con la letra $letra", "Nombres de $sexo con la letra $letra");
	  	
	  	if (! empty($origen))	{
	  		$post_tags = array_merge($post_tags, array("Nombres de origen $origen", "Nombres de $sexo de origen $origen", "Nombres de $sexo de origen $origen con la letra $letra"));
	  	} 
	  		
	  	wp_set_post_tags($id_post, $post_tags);
		
		
		// Seteamos las taxonomías
		$origen = array();
		if (! empty($data->origen)) $origen[] = $data->origen;
		if (! empty($data->otro_origen)) $origen[] = $data->otro_origen;
		
		// Sexo
		if (! empty($data->sexo)) 
			wp_set_post_terms($id_post, $data->sexo, 'sexo', false);

		// Origen	
		if (! empty($origen)) 
			wp_set_post_terms($id_post, $origen, 'origen', false);
			
		// Letra de inicio
		wp_set_post_terms($id_post, $letra, 'letra', false);
		
		
		// Añadimos los campos personalizados para el SEO
		
		$seo_title = "$data->nombre | Nombres de $data->sexo | Nombres de bebe";
		$seo_description = "Toda de información del nombre $data->nombre: significado, historia, personajes famosos, fechas de celebración y otros muchos nombres de $data->sexo";
		$seo_keywords = "$data->nombre, nombres de bebe, nombres de $data->sexo, significado del nombre $data->nombre, historia del nombre $data->nombre, ";
		
		add_post_meta($id_post, '_aioseop_title', $seo_title);
		add_post_meta($id_post, '_aioseop_description', $seo_description);
		add_post_meta($id_post, '_aioseop_keywords', $seo_keywords);
	
	}
	  
	return $id_post;
  
}

function wp_babies_get_name ($id) {
	
	global $wpdb;
	$wpdb->show_errors;
	$table_name = $wpdb->prefix . WP_BABIES_TABLE;
	
	return $wpdb->get_row("SELECT * FROM $table_name WHERE id = $id");
}

function wp_babies_create () {

	global $wpdb;
	$wpdb->show_errors;
	$table_name = $wpdb->prefix . WP_BABIES_TABLE;
	
	$ids_nombres =  $wpdb->get_col("SELECT id FROM $table_name WHERE status = 0 ORDER BY RAND() ");
	
	if ($ids_nombres) {
		foreach ($ids_nombres as $id_nombre) {
			if (wp_babies_create_post($id_nombre)) {
				
				echo '<div id="message" class="updated fade below-h2"><p>Post ' . $id_nombre .' creado correctamente</p></div>';
				
			}else {
				
				echo '<div id="message" class="error fade below-h2"><p>Error creando el post ' . $id_nombre .'</p></div>';
				
			}
		}
	}
	
}

function wp_babies_post_content ($data) {
	
	if (! $data || ! is_object($data)) return false;
	
	$html = '<dl>';
	
	if (! empty($data->significado)) {
		$html .= "<dt>Significado</dt>";
		$html .= "<dd>$data->significado</dd>";
	}
	
	if (! empty($data->onomastica)) {
		$html .= "<dt>Fecha</dt>";
		$html .= "<dd>$data->onomastica</dd>";
	}
	
	if (! empty($data->historia)) {
		$html .= "<dt>Historia</dt>";
		$html .= "<dd>$data->historia</dd>";
	}
	
	if (! empty($data->personajes_celebres)) {
		$html .= "<dt>Personajes célebres</dt>";
		$html .= "<dd>$data->personajes_celebres</dd>";
	}
	
	if (! empty($data->variante)) {
		$html .= "<dt>Variante</dt>";
		$html .= "<dd>$data->variante</dd>";
	}
	
	$html .= '</dl>';
	
	return $html;
}

if (! function_exists('unregister_taxonomy')) {
	
	 /**
	 * Reverse the effects of register_taxonomy()
	 *
	 * @package WordPress
	 * @subpackage Taxonomy
	 * @since 3.0
	 * @uses $wp_taxonomies Modifies taxonomy object
	 *
	 * @param string $taxonomy Name of taxonomy object
	 * @param array|string $object_type Name of the object type
	 * @return bool True if successful, false if not
	 */
	 
	function unregister_taxonomy( $taxonomy, $object_type = '') {
	        global $wp_taxonomies;
	
	        if ( !isset($wp_taxonomies[$taxonomy]) )
	                return false;
	
	        if ( !empty( $object_type ) ) {
	                $i = array_search($object_type, $wp_taxonomies[$taxonomy]->object_type);
	
	                if ( false !== $i )
	                        unset($wp_taxonomies[$taxonomy]->object_type[$i]);
	
	                if ( empty($wp_taxonomies[$taxonomy]->object_type) )
	                        unset($wp_taxonomies[$taxonomy]);
	        } else {
	                unset($wp_taxonomies[$taxonomy]);
	        }
	        
	        return true;
	}
}

function replace_accents($string)
{
  return str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I','O','O','O','O','O', 'U','U','U','U', 'Y'), $string);
} 
