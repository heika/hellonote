<?php
/**
 * @package hellonote
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">		
	<?php if ( 'post' == get_post_type() ) : ?>
		<?php hellonote_entry_cat(); ?>
	<?php endif; ?>
	<?php the_title( '<div class="entry-title">', '</div>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php hellonote_posted_on(); ?>
				<?php hellonote_entry_tag(); ?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
		<?php
			/* translators: %s: Name of current post */
			the_content();
		?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'hellonote' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php hellonote_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
