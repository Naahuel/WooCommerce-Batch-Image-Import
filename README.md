# WooCommerce Batch Product Image Import
Import a batch of images to a WooCommerce store using the SKU as the filenames.

This WordPress plugin was made to simplify the job of uploading hundreds of images to hundreds of products in a WooCommerce store. 

It was made in a few minutes, intended for my own use, so it's not very effective or user-friendly. It can do a long list of files, as long as your PHP configuration allows it (enough `max_execution_time` and `memory_limit`).

## How it works
1. It gets all the files in `wp-contents/uploads/products` and processes one by one, using each file name as the SKU *(read step 2 in how to use)*.
2. It checks if the SKU exists.
3. If it does, it gets the product's information (ID and title).
4. If the product doesn't have a thumbnail, it sets the image as the thumbnail.
5. If the product already has a thumbnail, it will add the image to its product gallery.

## How to use
### 0.- Backup your database
Really. I'm not responsible if something breaks.
### 1.- Install and activate the plugin.
Upload the folder `woo-import-images` to the `wp-content/plugins` directory.
### 2.- Upload files.
Via FTP or SSH to `wp-contents/uploads/products`. Files should be named with the SKU (i.e. `PROD001.jpg`). 

For multiple images for one product, use this format: `PROD001.jpg`, `PROD001-1.jpg`, `PROD001-2.jpg`, etc. It doesn't really matter what you put after the dash, as long as the SKU is before the dash. 

The number after the dash should help the order in which they are imported (NOTE: PHP's `scandir` uses its default sorting method `SCANDIR_SORT_ASCENDING` which is alphabetical, so `PROD001-10.jpg` will import BEFORE `PROD001-1.jpg`. To solve this, make sure you use two digits for the number after the dash.

### 3.- Run the plugin.
In your WordPress Dashboard, go to *WooCommerce Import Images*. Click Run. After it's done, it will show some basic information of what happened.

## Warnings
* Backup your database.
* It doesn't do any checks on file types, file names, or if WooCommerce is active or anything (only checks if product exists). It doesn't do any error checks or error handling.
* It also doesn't check if the file was imported before. So if you run it twice, it will add repeated images to the product's gallery.
* After it's done, **it will delete the files** that were manually uploaded if they were imported to the WordPress library successfully. This should prevent duplicating images in the library or product galleries if the plugin is executed again.
