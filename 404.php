<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package hellonote
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php _e( 'Sorry! That page can&rsquo;t be found.', 'hellonote' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php _e( 'It looks like nothing was found at this location. Let&rsquo;s try to have a search. Or you can <a href="mailto:'.get_bloginfo('admin_email').'" target="_blank">contact admin<a/>.', 'hellonote' ); ?></p>


				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
