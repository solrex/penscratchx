<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Penscratch
 */

if ( ! function_exists( 'penscratch_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @return void
 */
function penscratch_paging_nav( $max_num_pages = '' ) {
	// Get max_num_pages if not provided
	if ( '' == $max_num_pages )
		$max_num_pages = $GLOBALS['wp_query']->max_num_pages;

	// Don't print empty markup if there's only one page.
	if ( $max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation clear" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'penscratch' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link( '', $max_num_pages ) ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">Previous</span>', 'penscratch' ), $max_num_pages ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link( '', $max_num_pages ) ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( '<span class="meta-nav">Next</span>', 'penscratch' ), $max_num_pages ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'penscratch_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function penscratch_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'penscratch' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">< Previous</span> %title', 'Previous post link', 'penscratch' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '<span class="meta-nav">Next ></span> %title', 'Next post link',     'penscratch' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'penscratch_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function penscratch_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	printf( __( '<span class="posted-on">%1$s</span><span class="byline"><span class="sep"> ~ </span>%2$s</span>', 'penscratch' ),
		sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_permalink() ),
			$time_string
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		)
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function penscratch_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'penscratch_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'penscratch_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so penscratch_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so penscratch_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in penscratch_categorized_blog.
 */
function penscratch_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'penscratch_categories' );
}
add_action( 'edit_category', 'penscratch_category_transient_flusher' );
add_action( 'save_post',     'penscratch_category_transient_flusher' );


/**
 * Returns the URL from the post.
 *
 * @uses get_the_link() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @return string URL
 */
function penscratch_get_link_url() {
	$content = get_the_content();
	$has_url = get_url_in_content( $content );

	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}

/*
 * Return the post format, linked to the post format archive
 */
function penscratch_post_format() {
	$format = get_post_format();
	$formats = get_theme_support( 'post-formats' );

	//If the post has no format, or if it's not a format supported by the theme, return
	if ( ! $format || ! has_post_format( $formats[0] ) )
		return;

	printf( '<a class="entry-format" href="%1$s" title="%2$s">%3$s</a><span class="sep"> ~ </span>',
				esc_url( get_post_format_link( $format ) ),
				esc_attr( sprintf( __( 'All %s posts', 'penscratch' ), get_post_format_string( $format ) ) ),
				get_post_format_string( $format )
			);
}
