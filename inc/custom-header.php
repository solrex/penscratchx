<?php
/**
 *
 * @package Penscratch
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @uses penscratch_header_style()
 * @uses penscratch_admin_header_style()
 * @uses penscratch_admin_header_image()
 */
function penscratch_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'penscratch_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => '666666',
		'width'                  => 937,
		'height'                 => 300,
		'flex-height'            => true,
		'wp-head-callback'       => 'penscratch_header_style',
		'admin-head-callback'    => 'penscratch_admin_header_style',
		'admin-preview-callback' => 'penscratch_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'penscratch_custom_header_setup' );

if ( ! function_exists( 'penscratch_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see penscratch_custom_header_setup().
 */
function penscratch_header_style() {
	$header_text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == $header_text_color ) {
		return;
	}

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $header_text_color ) :
	?>
		.site-title,
		.site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		.site-title a {
			color: #<?php echo $header_text_color; ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // penscratch_header_style

if ( ! function_exists( 'penscratch_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see penscratch_custom_header_setup().
 */
function penscratch_admin_header_style() {
?>
	<style type="text/css">
		.appearance_page_custom-header #headimg {
			background: white;
			border: none;
			font-family: "Roboto Slab", Georgia, Times, serif;
			font-size: 15px;
			max-width: 1092px;
		}
		#headimg .site-branding-wrapper {
			border-bottom: 3px solid #eeeeee;
			margin: 0 0 27px;
			padding: 0 0 24px;
		}
		#headimg .site-branding-wrapper:before,
		#headimg .site-branding-wrapper:after {
			content: "";
			display: table;
		}
		#headimg .site-branding-wrapper:after {
			clear: both;
		}
		#headimg .site-branding {
			clear: both;
			margin-top: 54px;
			margin-bottom: 14px;
			text-align: center;
		}
		#headimg h1 {
			clear: none;
			display: inline-block;
			font-size: 1.75em;
			font-weight: normal;
			letter-spacing: 1px;
			line-height: 1;
			margin: 0;
		}
		#headimg a {
			text-decoration: none;
		}
		#desc {
			color: #999 !important;
			font-size: 16px;
			font-weight: 300;
			margin: 13px auto;
		}
		#headimg .site-logo {
			max-height: 150px;
			width: auto;
			display: block;
			margin: 0 auto 27px;
		}
		#headimg .custom-header {
			border-radius: 5px;
			display: block;
			margin: 0 auto;
			margin-bottom: 27px;
			max-width: 100%;
			height: auto;
		}
	</style>
<?php
}
endif; // penscratch_admin_header_style

if ( ! function_exists( 'penscratch_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see penscratch_custom_header_setup().
 */
function penscratch_admin_header_image() {
	$style = sprintf( ' style="color:#%s;"', get_header_textcolor() );
?>
	<div id="headimg">
		<div class="site-branding-wrapper">
			<div class="site-branding">
				<?php if ( function_exists( 'jetpack_the_site_logo' ) ) jetpack_the_site_logo(); ?>
				<h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
				<div class="displaying-header-text" id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
			</div>
		</div>
		<?php if ( get_header_image() ) : ?>
		<img src="<?php header_image(); ?>" alt="" class="custom-header">
		<?php endif; ?>
	</div>
<?php
}
endif; // penscratch_admin_header_image
