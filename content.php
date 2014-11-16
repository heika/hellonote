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
	<?php the_title( sprintf( '<div class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a><div class="clear"></div></div>' ); ?>
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
			the_content( sprintf(
				__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'hellonote' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
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