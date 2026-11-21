<?php
/*
Template Name: Recipe Index (Infinite Scroll)
*/
?>
<?php get_header();

$front_page_layout = get_theme_mod( 'front-page-layout', 'right-sidebar' );

$content_classes = "content-area";
$content_classes .= 'full-width' === $front_page_layout ? " full-layout" : '';
?>

<main id="main" class="site-main food-index-main" role="main">

    <?php if ( 'left-sidebar' === $front_page_layout ): ?>
        <?php get_sidebar(); ?>
    <?php endif ?>

    <section class="<?php echo esc_attr( $content_classes ); ?>">

        <?php get_template_part( 'recipe-index-start' ); ?>

        <?php
            $foodica_paged = max( 1, absint( get_query_var( 'paged' ) ), absint( get_query_var( 'page' ) ) );

            $foodica_args = array(
                'paged'               => $foodica_paged,
                'post_type'           => 'post',
                'posts_per_page'      => 12,
                'ignore_sticky_posts' => true,
            );

            /*
             * The recipe list temporarily takes the place of the main query so
             * pagination.php can build the page links. The page's own query is
             * put back right after the list.
             */
            $foodica_page_query = $wp_query;
            $wp_query           = new WP_Query( $foodica_args ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

            if ( $wp_query->have_posts() ) :

                // Link to page N of this list, for any permalink setting ("%#%" is replaced in JS).
                $foodica_paging_url = str_replace( '999999999', '%#%', get_pagenum_link( 999999999, false ) );
            ?>

            <script type="text/javascript">
                var wpz_currPage = <?php echo absint( $foodica_paged ); ?>,
                    wpz_maxPages = <?php echo absint( $wp_query->max_num_pages ); ?>,
                    wpz_pagingURL = <?php echo wp_json_encode( esc_url_raw( $foodica_paging_url ) ); ?>;
            </script>

            <section id="recent-posts" class="foodica-index">

                <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

                    <?php get_template_part('content-index'); ?>

                <?php endwhile; ?>

            </section>

            <?php get_template_part( 'pagination' ); ?>

        <?php else: ?>

            <?php get_template_part( 'content', 'none' ); ?>

        <?php endif; ?>

        <?php
            $wp_query = $foodica_page_query; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
            wp_reset_postdata();
        ?>

    </section><!-- .content-area -->

    <?php if ( 'right-sidebar' === $front_page_layout ): ?>
        <?php get_sidebar(); ?>
    <?php endif ?>

</main><!-- .site-main -->

<?php
get_footer();
