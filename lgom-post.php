<?php
    require_once('../../../wp-blog-header.php');
    include_once"../../../wp-load.php";
    include_once"../../../wp-includes/wp-db.php";
    require_once('../../../wp-admin/includes/image.php');
    if (!empty($_POST)) {

      $my_post = array(
         'post_title' => $_POST['title'],
         'post_content' => $_POST['description'],
         'post_status' => 'draft',
      );

       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $_POST['image']);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       $contents = curl_exec ($ch);
       curl_close ($ch);
       $upload = wp_upload_bits(basename($_POST['image']), null, $contents);

      // Insert the post into the database
      $my_post_id = wp_insert_post( $my_post );
      add_post_meta($my_post_id, 'thumbnail', $_POST['image']);
      add_post_meta($my_post_id, 'title_url', $_POST['url']);

      //upload and attach if any image is suppies
      if(!empty($_POST['image']))
      {  
          $wp_filetype = wp_check_filetype(basename($upload['file']), null );
          $wp_upload_dir = wp_upload_dir();
          $attachment = array(
             'guid' => $wp_upload_dir['baseurl'] . $upload['file'], 
             'post_mime_type' => $wp_filetype['type'],
             'post_title' => preg_replace('/\.[^.]+$/', '', $_POST['title']),
             'post_content' => '',
             'post_status' => 'inherit'
          );
          $attach_id = wp_insert_attachment( $attachment, $upload['file'], $my_post_id );
  
          $attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
          wp_update_attachment_metadata( $attach_id, $attach_data );
          add_post_meta($my_post_id, '_thumbnail_id', $attach_id);
      }
      //redirect to post
      $adminurl = admin_url();
      $editpath = $adminurl . "post.php?post=" . $my_post_id . "&action=edit";
      header( 'Location: ' . $editpath ) ;
      //echo '{ "status" : "' . $my_post_id . '"}';

    }
    else
    { 
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json'); 
        echo '{ "status" : "failed" }';
    }
?>

