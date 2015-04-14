<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Penscratch
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */

function penscratch_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container'      => 'main',
		'footer'         => 'page',
	) );

    /**
     * Add theme support for Responsive Videos.
     */
    add_theme_support( 'jetpack-responsive-videos' );

    /**
     * Add theme support for site logos
     */
     add_theme_support( 'site-logo', array( 'size' => 'penscratch-site-logo' ) );
}
add_action( 'after_setup_theme', 'penscratch_jetpack_setup' );

// Turn off infinite scroll if mobile + sidebar, or if social menu is active
if ( function_exists( 'jetpack_is_mobile' ) && ! function_exists( 'penscratch_has_footer_widgets' ) ) {

    function penscratch_has_footer_widgets() {
        if ( has_nav_menu( 'social' ) || ( jetpack_is_mobile( '', true ) && is_active_sidebar( 'sidebar-1' ) ) )
            return true;

        return false;
    }

} //endif
add_filter( 'infinite_scroll_has_footer_widgets', 'penscratch_has_footer_widgets' );