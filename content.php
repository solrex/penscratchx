<?php
/**
 * @package Penscratch
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
	<?php if ( 'link' == get_post_format() ) : ?>
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( penscratch_get_link_url() ) ), '</a></h1>' ); ?>
	<?php else : ?>
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
	<?php endif; ?>
	</header><!-- .entry-header -->
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumbnail">
			<?php the_post_thumbnail( 'penscratch-featured' ); ?>
		</div>
	<?php endif; ?>
	<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php penscratch_post_format(); ?>
			<?php penscratch_posted_on(); ?>
			<?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
				echo '<span class="sep"> ~ </span><span class="comments-link">';
				comments_popup_link( __( 'Leave a comment', 'penscratch' ), __( '1 Comment', 'penscratch' ), __( '% Comments', 'penscratch' ) );
				echo '</span>';
			} ?>
			<?php edit_post_link( __( 'Edit', 'penscratch' ), '<span class="sep"> ~ </span><span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'penscratch' ) ); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'penscratch' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>
</article><!-- #post-## -->
