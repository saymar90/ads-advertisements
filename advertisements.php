<?php
/**
 * @package ads-advertisements
 * @version 1.0
 *
 *
 * Plugin Name: Advertisements Sidebar Ads
 * Description: A WordPress plugin for advertisements.
 * Author: Aymar Sossou
 * Version: 1.0
 * Author URI: http://www.intside.com/
*/


/**
 * Add post thumbnails to advertissements post
 *
 * @since ads 1.1
 */
function ads_add_theme_support_ads_thumbnails() {
	add_theme_support('post-thumbnails');
}
add_action( 'after_setup_theme', 'ads_add_theme_support_ads_thumbnails', 11 );

/**
 * Register custom post types.
 *
 * @since ads 1.0
 */
add_action( 'init', 'ads_register_post_types' );

function ads_register_post_types() {
	$labels = array(
		'name' => _x('Advertisements', 'post type general name'),
		'singular_name' => _x('Advertisement', 'post type singular name'),
		'add_new' => _x('Add New', 'Advertisement'),
		'add_new_item' => __('Add New Advertisement'),
		'edit_item' => __('Edit Advertisement'),
		'new_item' => __('New Advertisement'),
		'view_item' => __('View'),
		'search_items' => __('Search Advertisements'),
		'not_found' =>  __('No Advertisements found'),
		'not_found_in_trash' => __('No Advertisements found in Trash'),
		'parent_item_colon' => ''
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'pubs' ),
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array( 'title', 'thumbnail', 'revisions' )
	);
	register_post_type( 'ads_advertisements', $args );

}

/**
 * Add filter to insure the text Sale, or sale, is displayed when user updates an sale ,
 *
 * @since ads 1.0
 */
add_filter('post_updated_messages', 'ads_post_updated_messages');

function ads_post_updated_messages( $messages ) {
	global $post, $post_ID;

	$messages['advertisement'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Advertisement updated. <a href="%s">View</a>' ), esc_url( get_permalink( $post_ID ) ) ),
		2 => __( 'Custom field updated.' ),
		3 => __( 'Custom field deleted.' ),
		4 => __( 'Advertisement updated.' ),
		5 => isset( $_GET['revision'] ) ? sprintf( __( 'Advertisement restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __( 'Advertisement published. <a href="%s">View</a>' ), esc_url( get_permalink( $post_ID ) ) ),
		7 => __( 'Advertisement saved.' ),
		8 => sprintf( __( 'Advertisement submitted. <a target="_blank" href="%s">Preview</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
		9 => sprintf( __( 'Advertisement scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview</a>' ),
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
		10 => sprintf( __( 'Advertisement draft updated. <a target="_blank" href="%s">Preview</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
	);

	return $messages;
}

/**
 * Add filter to insure the text Sale, or sale, is displayed when user updates an sale ,
 *
 * @since ads 1.0
 */
add_action( 'contextual_help', 'ads_contextual_help', 10, 3 );

function ads_contextual_help( $contextual_help, $screen_id, $screen ) {
	if ('advertisement' == $screen->id ) {
		$contextual_help =
		'<p>' . __( 'Things to remember when adding or editing an advertisement:' ) . '</p>' .
		'<ul>' .
		'<li>' . __( 'Specify the correct genre such as Mystery, or Historic.' ) . '</li>' .
		'<li>' . __( 'Specify the correct writer of the advertisement.  Remember that the Author module refers to you, the author of this advertisement review.' ) . '</li>' .
		'</ul>' .
		'<p>' . __( 'If you want to schedule the advertisement review to be published in the future:' ) . '</p>' .
		'<ul>' .
		'<li>' . __( 'Under the Publish module, click on the Edit link next to Publish.' ) . '</li>' .
		'<li>' . __( 'Change the date to the date to actual publish this article, then click on Ok.' ) . '</li>' .
		'</ul>' .
		'<p><strong>' . __( 'For more information:' ) . '</strong></p>' .
		'<p>' . __( '<a href="http://codex.wordpress.org/Posts_Edit_SubPanel" target="_blank">Edit Posts Documentation</a>' ) . '</p>' .
		'<p>' . __( '<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>' ) . '</p>' ;
	} elseif ( 'edit-day-image' == $screen->id ) {
		$contextual_help =
		'<p>' . __( 'This is the help screen displaying the table of sales blah blah blah.' ) . '</p>' ;
	}

	return $contextual_help;
}

/**
 * Add meta boxes to the post edit screen.
 *
 * @since ads 1.0
 */
function ads_add_meta_boxes() {
	add_meta_box( 'ads_link', __( 'Advertissement Link:', 'ads' ), 'ads_advertissement_link', 'ads_advertisements', 'normal', 'high' );
	add_meta_box( 'ads_content', __( 'Paragraph Content:', 'ads' ), 'ads_paragraph_post', 'ads_advertisements', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'ads_add_meta_boxes' );

/**
 * Output Advertissement link meta box.
 *
 * @since ads 1.0
 */
function ads_advertissement_link( $post ) {

	$ads_advertissement_link = get_post_meta( $post->ID, '_ads_advertissement_link', true );

	echo '<label for="ads_advertissement_link" class="screen-reader-text">' . __('Advertissement Link :', 'ads' ) . '</label> ';
	echo '<input id="ads_advertissement_link" name="ads_advertissement_link" type="text" style="width:99%;" value="' . $ads_advertissement_link . '" />';
	echo '<p>(Add Advertissement link here.)</p>';
}

/**
 * Output Advertissement content meta box.
 *
 * @since ads 1.0
 */
function ads_paragraph_post( $post ) {

	$ads_paragraph_post = get_post_meta( $post->ID, '_ads_paragraph_post', true );

	echo '<label for="ads_paragraph_post" class="screen-reader-text">' . __('Paragraph Content:', 'ads' ) . '</label> ';
	echo '<textarea id="ads_paragraph_post" tabindex="6" name="ads_paragraph_post" cols="40" rows="1" style="height:10em; width:100%; color:#000;">' . $ads_paragraph_post. '</textarea>';
}

/**
 * Save content posted in custom meta boxes.
 *
 * @since ads 1.0
 */
add_action( 'save_post', 'ads_save_post', 10, 2 );

function ads_save_post( $post_id, $post ) {

	if ( ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || defined('DOING_AJAX') )
		return $post_id;

	if( 'ads_advertisements' == $post->post_type ) {

		if ( !current_user_can( 'edit_post', $post_id ) )
			return $post_id;

		if ( isset( $_POST['ads_advertissement_link'] ) )
			update_post_meta( $post_id, '_ads_advertissement_link', $_POST['ads_advertissement_link'] );

		if ( isset( $_POST['ads_paragraph_post'] ) )
			update_post_meta( $post_id, '_ads_paragraph_post', $_POST['ads_paragraph_post'] );

	}

	return $post_id;
}

/**
 * AdsWidget Class
 */
class AdsWidget extends WP_Widget {
	/** constructor */
	function AdsWidget() {
		parent::WP_Widget( 'adswidget', $name = 'AdsWidget' );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title; ?>
			<?php
				$args = array(
					'post_type' => 'ads_advertisements',
					'posts_per_page' => 1,
					'orderby' => 'rand',
				);
				$news_query = new WP_Query($args);
					if( $news_query->have_posts() ): while( $news_query->have_posts() ): $news_query->the_post(); ?>
						<?php if ( has_post_thumbnail() ) { ?>
							<div class="ads-image">
								<a target="_blank" href="<?php echo get_post_meta( get_the_ID(), '_ads_advertissement_link', true ); ?>" title="<?php the_title(); ?>"><?php echo the_post_thumbnail( 'ads-thumb' ); ?></a>
							</div>
						<?php } else { ?>
							<div class="ads-html">
								<div><?php echo get_post_meta( get_the_ID(), '_ads_paragraph_post', true ); ?></div>
							</div>
						<?php } ?>
					<?php endwhile; endif; ?>
		<?php echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

} // class AdsWidget

// register AdsWidget widget
add_action( 'widgets_init', create_function( '', 'return register_widget("AdsWidget");' ) );

// add code in footer
function ads_header_code() {
?>
	<style type="text/css">
		.ads-image img, .ads-html img {
			max-width: 100%;
			height: auto;
		}
	</style>
	<?php
}
add_action('wp_head', 'ads_header_code' );