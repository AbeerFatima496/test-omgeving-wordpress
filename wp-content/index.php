<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <?php get_header(); ?>

    <main id="content" class="site-content">
        <?php
        if ( have_posts() ) {
            while ( have_posts() ) {
                the_post();
                the_content(); // Elementor renders its content here
            }
        }
        ?>
    </main>

    <?php get_footer(); ?>
    <?php wp_footer(); ?>
</body>
</html>
