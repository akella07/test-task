<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.2
 */

?>

<?php 

echo 'PRICE IS: '.movies_product_get_price($price, $product).'';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	if ( is_sticky() && is_home() ) :
		echo twentyseventeen_get_svg( array( 'icon' => 'thumb-tack' ) );
	endif;
	?>
	<header class="entry-header">
		<?php
		if ( 'post' === get_post_type() ) {
			echo '<div class="entry-meta">';
				if ( is_single() ) {
					twentyseventeen_posted_on();
				} else {
					echo twentyseventeen_time_link();
					twentyseventeen_edit_link();
				};
			echo '</div><!-- .entry-meta -->';
		};

		if ( is_single() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		} elseif ( is_front_page() && is_home() ) {
			the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
		?>
	</header><!-- .entry-header -->

	<?php if ( '' !== get_the_post_thumbnail() && ! is_single() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'twentyseventeen-featured-image' ); ?>
			</a>
		</div><!-- .post-thumbnail -->
	<?php endif; ?>

	<div class="entry-content">
<h4 class="entry-title"><?php echo get_post_meta( get_the_ID(), 'movie_info_subtitle', true ); ?></h4>	
<div><img style="float:left; padding:0 5px 5px 0;" src="<?php echo get_post_meta( get_the_ID(), 'movie_info_image', true ); ?>"><?php echo get_post_meta( get_the_ID(), 'movie_info_content_meta', true ); ?></div>
<div>Category: <?php echo get_post_meta( get_the_ID(), 'movie_info_category', true ); ?></div>
<div>Price: <?php echo get_post_meta( get_the_ID(), 'movie_info_movie_price', true ); ?></div>

		<?php
		
		
		/* translators: %s: Name of current post */
		the_content( sprintf(
			__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
			get_the_title()
		) );

		wp_link_pages( array(
			'before'      => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
			'after'       => '</div>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>',
		) );
		?>
	</div><!-- .entry-content -->

	<?php
	if ( is_single() ) {
		twentyseventeen_entry_footer();
	}
	if ( is_singular('movies') ) {
	
	echo '<form class="cart" action="'.get_the_permalink().'" method="post" enctype="multipart/form-data">
			<div class="quantity">
		<label class="screen-reader-text" for="quantity_5ac1c5326be8a">Количество</label>
		<input type="number" id="quantity_5ac1c5326be8a" class="input-text qty text" step="1" min="1" max="" name="quantity" value="1" title="Кол-во" size="4" pattern="[0-9]*" inputmode="numeric" aria-labelledby="">
	</div>
	
		<button type="submit" name="add-to-cart" value="'.$post->ID.'" class="single_add_to_cart_button button alt">Оформление заказа</button>

			</form>';
	}
	wc_print_notices();
	?>

</article><!-- #post-## -->
