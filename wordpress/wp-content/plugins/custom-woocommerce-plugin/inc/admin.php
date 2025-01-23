<?php

$posttype = 'rotatingbanners';
/* Let's add meta box for featured and star rating */

add_action( 'load-post.php', 'bannerimages_meta_boxes_setup' );
add_action( 'load-post-new.php', 'bannerimages_meta_boxes_setup' );

function bannerimages_meta_boxes_setup() {

	add_action( 'add_meta_boxes', 'bannerimages_add_meta_boxes', 10, 2 );
	add_action( 'save_post', 'bannerimages_save_class_meta', 10, 2 );	
}

function bannerimages_add_meta_boxes( $posttype, $post) {


	add_meta_box(
		'posttype-prodimage-class',			// Unique ID
		esc_html__( 'Banner Images', 'aptwm-posttype' ),		// Title
		'bannerimages_images_meta_box',		// Callback function
		str_replace(" ","",strtolower($posttype)),					// Admin page (or post type)
		'normal',					// Context
		'default'					// Priority
	);
}

function bannerimages_images_meta_box($object, $box) {
	global $posttype;
	$prodimgids =  explode(":",get_post_meta( $object->ID, 'bannerimages_prodimg',true));
	$prodimgids = array_filter($prodimgids);
	?>
	<div id="<?php echo strtolower($posttype);?>-images" class="uploader">
	  <input class="upload_image_button" type="button" name="_unique_name_button" id="_unique_name_button" value="Set Banner Images" />
	  <input type="hidden" name="prodimage" value="<?php echo implode(":",$prodimgids);?>" id="products_image" class="prodimage"/>
	</div>
	<div id="prodimage">
		<ul id="aptsortable" class="aptsort">
			<?php
			// get the list of images
			 for($i=0;$i<count($prodimgids);$i++) {
				if($prodimgids[$i] != "") {
					echo '<li id="'. $prodimgids[$i].'">'.wp_get_attachment_image( $prodimgids[$i] ).'<input type="image" src="" value="'.$prodimgids[$i].'" alt="X"/></li>';
				}
			 }
			?>
		</ul>
		
		
	</div>
	<script type="text/javascript">
	// Uploading files
		var file_frame;
		var imgids = "<?php echo implode(":",$prodimgids);?>";

		jQuery('.upload_image_button').on('click', function( e){
			e.preventDefault();
		 
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}
		 
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
			text: jQuery( this ).data( 'uploader_button_text' ),
			},
				multiple: true // Set to true to allow multiple files to be selected
		});
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
		// We set multiple to false so only get one image from the uploader
		 var selection = file_frame.state().get('selection');
			selection.map( function( attachment ) {
				attachment = attachment.toJSON();
				console.log(attachment);
			 	alert(attachment.sizes.full.url);
			// Do something with attachment.id and/or attachment.url here
			// alert(attachment.id+' '+attachment.url);
			alert(attachment.sizes.full.url);
			//console.log(attachment);
			jQuery("#prodimage ul#aptsortable").append('<li id="'+attachment.id+'"><img src="'+attachment.sizes.full.url+'" alt="" id="image'+attachment.id+'" class="attachment-thumbnail aptsortimg"/><input type="image" src="" value="'+attachment.id+'" alt="X"/></li>');
			imgids = imgids + attachment.id + ":";
			alert(imgids);
			jQuery("#products_image").attr("value",imgids);
			// jQuery('#aptsortable').sortable({cursor: 'move', cursorAt:{top:80,left:80}});
			jQuery("#aptsortable input[type='image']").click(function(e){
				e.preventDefault();
							var ids = "";
				jQuery(this).parent().remove();
				jQuery('#aptsortable li').each(function(e) {
					var id = jQuery(this).attr('id'); 
					ids = ids+id+":";
				});
				jQuery("#products_image").attr("value",ids);
			});
		});
		 
		// Do something with attachment.id and/or attachment.url here
		});
		 
		// Finally, open the modal
			file_frame.open();
			
		});
		
		//activate sortable
		jQuery(document).ready(function() {
			// jQuery('#aptsortable').sortable({axis:'x',cursor: 'move',containment: "#prodimage",placeholder: "ui-state-highlight",forcePlaceholderSize:true, cursorAt:{top:80,left:80}}).disableSelection();
			jQuery('#aptsortable').sortable({connectWith: ".aptsort",axis:'x',cursor: 'move', cursorAt:{top:80,left:80}}).disableSelection();
			jQuery('#aptsortable').on("sortupdate",function(e,ui) {
				//alert(">>"+this);
				//grab all ids
				var ids = "";
				jQuery('#aptsortable li').each(function(e) {
					var id = jQuery(this).attr('id'); 
					ids = ids+id+":";
				});
				jQuery("#products_image").attr("value",ids);
			});
			jQuery("#aptsortable input[type='image']").click(function(e){
			
				e.preventDefault();
				var ids = "";
				jQuery(this).parent().remove();
				jQuery('#aptsortable li').each(function(e) {
					var id = jQuery(this).attr('id'); 
					ids = ids+id+":";
				});
				jQuery("#products_image").attr("value",ids);
			});
		});
		
		
		//jQuery('#aptsortable').disableSelection();
	</script>
	<style type="text/css">
		#apttrash { position:absolute;top:50px;margin:0 10px; right:0;text-align: center; font-weight: bold; line-height:20px; font-size: 16px; padding:5px;vertical-align: middle;border: 1px solid #808080; background-color:#E6E9ED;} 
		#apttrash li { margin-bottom:0 ;vertical-align:bottom;}
		#aptsortable { min-height: 200px;}
		#aptsortable li { display:inline-block; height:auto; margin:10px; } 
		#aptsortable li img { background-color:#E6E9ED;padding:10px; border: 1px solid grey; }
		#aptsortable li input[type="image"] { border: 1px solid red; color:red; position: absolute;margin:0; border: 1px solid #808080; border-left:0;}
	</style>
	<?php
}


add_action( 'save_post', 'bannerimages_save_class_meta', 10, 2 );


function bannerimages_save_class_meta( $post_id, $post ) {


	/* Verify the nonce before proceeding. */
//	if ( !isset( $_POST['bannerimages_class_nonce'] ) || !wp_verify_nonce( $_POST['bannerimages_class_nonce'], basename( __FILE__ ) ) )
//		return $post_id;

	/* Get the post type object. */
//	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
//	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
//		return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_prodimg_value = isset($_POST['prodimage']) ? strip_tags($_POST['prodimage']) : '';
	/* Get the meta key. */
	$meta_prodimg_key = 'bannerimages_prodimg';
	/* Get the meta value of the custom field key. */
	$meta_prodimg_value = get_post_meta( $post_id, $meta_prodimg_key,true);

	update_post_meta( $post_id, $meta_prodimg_key,$new_prodimg_value);
}