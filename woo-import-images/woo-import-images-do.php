<h1>WooCommerce Import Images</h1>
<p>
	Make sure you read how it works and the warnings here: <a target="_blank" href="https://github.com/Naahuel/WooCommerce-Batch-Image-Import">https://github.com/Naahuel/WooCommerce-Batch-Image-Import</a>
</p>
<p>
	<a href="<?php menu_page_url('woo-import-images/woo-import-images-do.php'); ?>&amp;do-it=go" class="button button button-primary">Run</a>
</p>
<?php

if( isset($_GET['do-it']) ){

	// Get the path to the upload directory.
	$wp_upload_dir = wp_upload_dir();

	// Get the path to the products dir, where images are
	$products_dir = $wp_upload_dir['basedir'] . '/products';

	// Get all files, alphabetically
	$files = array_diff(scandir($products_dir), array('.', '..'));

	// Need to require these files? Probably not.
	if ( !function_exists('media_handle_upload') ) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');
	}

	// Loop through the files
	foreach ($files as $_file) {

		// Set up file information
		$file_array = array();
		$filename 	= $products_dir . '/' . $_file;
		$file_info	= pathinfo($filename);
		$sku 				= explode('-',$file_info['filename']);

		// Fix file filename for query strings
		preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $filename, $matches);
		$file_array['name'] 		= basename($matches[0]);
		$file_array['tmp_name'] = $filename;

		// Show SKU
		echo '<h3>' .  $sku[0] . '</h3>';

		// Get product id, by its SKU
		$post_id = wc_get_product_id_by_sku( $sku[0] );

		// Check if product exists
		if( $post_id ){

			// It exists. Get the product.
			$the_post = get_post( $post_id );

			// Image description
			$description = $the_post->post_title;
			echo '<strong>' . $description . '</strong><br>';

			// Upload image to the library
			$id = media_handle_sideload( $file_array, $post_id, $description );

			// Attach to post
			if( has_post_thumbnail($post_id) ){

				// The post already has a thumbnail.
				echo  'The post already has a thumbnail. Adding to product gallery.' . '<br>';

				// Update product gallery
				$gallery = get_post_meta( $post_id, '_product_image_gallery', true );
				$ids = $gallery ?  $gallery . ',' . $id : $id;
				update_post_meta( $post_id, '_product_image_gallery', $ids);

			} else {

				// No thumbnail
				echo  'Setting post thumbnail.' . '<br>';

				// Set post thumbnail
				set_post_thumbnail( $post_id, $id );
			}

			// Delete file
			@unlink($file_array['tmp_name']);
		} else {

			// post/product doesn't exist.
			echo '<span style="color:red;">Product does not exist</span>';
		}

		echo '<hr>';

	} // endforeach

} //isset($_GET['do-it'])
