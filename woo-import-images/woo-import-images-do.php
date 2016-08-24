<h1>Importar Imágenes</h1>

<?php

// Get the path to the upload directory.
$wp_upload_dir = wp_upload_dir();

// Directorio de productos
$products_dir = $wp_upload_dir['basedir'] . '/products';

$files = array_diff(scandir($products_dir), array('.', '..'));

// Need to require these files
if ( !function_exists('media_handle_upload') ) {
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');
}

foreach ($files as $_file) {

	$file_array = array();
	$filename = $products_dir . '/' . $_file;
	$file_info = pathinfo($filename);
	$sku = explode('-',$file_info['filename']);

	// Set variables for storage
	// fix file filename for query strings
	preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $filename, $matches);
	$file_array['name'] = basename($matches[0]);
	$file_array['tmp_name'] = $filename;

	echo '<h3>' .  $sku[0] . '</h3>' . '<br>';

	$post_id = wc_get_product_id_by_sku( $sku[0] );

	if( $post_id ){
		$the_post = get_post( $post_id );

		$description = $the_post->post_title;
		echo $description . '<br>';

		// Subir la imagen
		$id = media_handle_sideload( $file_array, $post_id, $description );

		// Adjuntar al post
		if( has_post_thumbnail($post_id) ){
			// El post ya tiene imagen
			echo  'El post ya tiene imagen' . '<br>';

			// Actualizar galería de WooCommerce
			$galeria = get_post_meta( $post_id, '_product_image_gallery', true );
			$ids = $galeria ?  $galeria . ',' . $id : $id;
			update_post_meta( $post_id, '_product_image_gallery', $ids);

		} else {
			// NO tiene imagen
			echo  'Imagen nueva' . '<br>';

			// Establecer como imagen destacada
			set_post_thumbnail( $post_id, $id );
		}

		// Borrar archivo
		@unlink($file_array['tmp_name']);
	} else {

		// EL post / producto no existe
		echo '<span style="color:red;">No existe el producto</span>';
	}

	echo '<hr>';

} // endforeach
