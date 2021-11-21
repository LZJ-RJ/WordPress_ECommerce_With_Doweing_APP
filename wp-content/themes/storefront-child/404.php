<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

			<div class="error-404 not-found">

				<div class="page-content">
                    <?php
                        echo '<h3>' . sprintf ( __( 'Search results for &ldquo;%s&rdquo;', 'woocommerce' ),  get_search_query()) . '</h3>';
                    ?>
                    <header class="page-header">
                        <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'storefront' ); ?></h1>
                    </header><!-- .page-header -->

                    <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'storefront' ); ?></p>
				</div><!-- .page-content -->
			</div><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
