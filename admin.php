<?php

function wp_babies_admin () {
		
	echo '<div class="wrap"><div class="icon32" id="icon-edit-pages"><br></div><h2 id="main-title">WP Babies!</h2><br />';
		

	//wp_babies_create();
	
	
	
	if (! empty($_POST) && isset($_POST['nombre_id'])) {
		
		
		if (wp_babies_create_post($_POST['nombre_id'])) {
			
			echo '<div id="message" class="updated fade below-h2"><p>Post creado correctamente</p></div>';
			
		}else {
			
			echo '<div id="message" class="error fade below-h2"><p>Error creando el post</p></div>';
			
		}
		
	}
	
	global $wpdb; 
	$wpdb->show_errors(); 
	$table_name = $wpdb->prefix . WP_BABIES_TABLE;
	$terminos = $wpdb->get_results("SELECT * FROM $table_name ORDER BY nombre");
		

	?>


	
	<form method="post" action="" id="wp-babies-form">	

	<table cellspacing="0" class="widefat fixed" id="terms_table">
		<thead>
			<tr class="thead">
				<th style="" class="manage-column"  scope="col">Nombre</th>
				<th style="" class="manage-column"  scope="col">Origen</th>
				<th style="" class="manage-column"  scope="col">Otro origen</th>
				<th style="" class="manage-column"  scope="col">Significado</th>
				<th style="" class="manage-column"  scope="col">Historia</th>
				<th style="" class="manage-column"  scope="col">Personajes célebres</th>
				<th style="" class="manage-column"  scope="col">Onomástica</th>
				<th style="" class="manage-column"  scope="col">Variantes</th>
				<th style="" class="manage-column"  scope="col">Sexo</th>
				<th style="" class="manage-column"  scope="col">Crear</th>
				<th style="" class="manage-column"  scope="col">Estado</th>
			</tr>
		</thead>
	
		<tbody>
	<?php 
	
	//dump($terminos); 
	
	foreach ($terminos as $termino) {

		/*
		$data = json_decode($termino->data, true);
		

		$wpdb->update($table_name, array(
			'origen' => $data['Origen:'], 
			'otro_origen' => $data['Otro origen:'],
			'significado' => $data['Significado:'],
			'historia' => $data['Historia:'],
			'personajes_celebres' => $data['Personajes célebres:'],
			'onomastica' => $data['Onomástica:'],
			'variante' => $data['Variante:'],
			'sexo' => ($termino->id < 8760) ? 'niño' : 'niña'
			), array('id' => $termino->id));
			
		
		
		echo '<tr>';
			echo "<td>".$termino->nombre."</td>";
			echo "<td>".$data['Origen:']."</td>";
			echo "<td>".$data['Otro origen:']."</td>";
			echo "<td>".$data['Significado:']."</td>";
			echo "<td>".$data['Historia:']."</td>";
			echo "<td>".$data['Personajes célebres:']."</td>";
			echo "<td>".$data['Onomástica:']."</td>";
			echo "<td>".$data['Variante:']."</td>";
		echo '</tr>';
		
		*/
		
		
		echo '<tr>';
			echo "<td>".$termino->nombre."</td>";
			echo "<td>".$termino->origen."</td>";
			echo "<td>".$termino->otro_origen."</td>";
			echo "<td>".$termino->significado."</td>";
			echo "<td>".$termino->historia."</td>";
			echo "<td>".$termino->personajes_celebres."</td>";
			echo "<td>".$termino->onomastica."</td>";
			echo "<td>".$termino->variante."</td>";
			echo "<td>".$termino->sexo."</td>";
			echo '<td><input type="image" src="' . WP_BABIES_URL  . '/images/add.png" value="' . $termino->id . '" alt="Crear" name="nombre_id" /></td>';
			echo '<td>';
			if ($termino->status == 1) echo "creado"; 
			echo '</td>';
		echo '</tr>';
		
	}
	
	
	
	?>
			
		</tbody> 
	</table>
	</form>
	
</div>

<?php

	

}