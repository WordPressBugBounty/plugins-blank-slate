<?php
/**
 * Blank Slate functions.
 *
 * @package BlankSlate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'blank_slate_bootstrap' ) ) {

	/**
	 * Initialize the plugin.
	 */
	function blank_slate_bootstrap() {

		// Register the blank slate template.
		blank_slate_add_template(
			'blank-slate-template.php',
			esc_html__( 'Blank Slate', 'blank-slate' )
		);

		// Add our template(s) to the dropdown in the admin (classic themes).
		add_filter(
			'theme_page_templates',
			function ( array $templates ) {
				return array_merge( $templates, blank_slate_get_templates() );
			}
		);

		// Ensure our template is loaded on the front end.
		add_filter(
			'template_include',
			function ( $template ) {

				if ( is_singular() ) {

					$assigned_template = get_post_meta( get_queried_object_id(), '_wp_page_template', true );

					if ( is_string( $assigned_template ) && blank_slate_get_template( $assigned_template ) ) {

						if ( file_exists( $assigned_template ) ) {
							return $assigned_template;
						}

						// Allow themes to override plugin templates.
						$file = locate_template( wp_normalize_path( '/blank-slate/' . $assigned_template ) );
						if ( ! empty( $file ) ) {
							return $file;
						}

						// Fetch template from plugin directory.
						$file = wp_normalize_path( plugin_dir_path( __FILE__ ) . '/templates/' . $assigned_template );
						if ( file_exists( $file ) ) {
							return $file;
						}
					}
				}

				return $template;

			}
		);

		// Block themes (Site Editor) pick templates from block templates, not PHP files.
		blank_slate_register_block_template();

	}
}

if ( ! function_exists( 'blank_slate_register_block_template' ) ) {

	/**
	 * Register a "Blank Slate" block template so it can be chosen in the editor on block themes.
	 *
	 * Requires WordPress 6.7+; on older versions, or classic themes, the PHP template is used.
	 */
	function blank_slate_register_block_template() {

		if ( ! function_exists( 'register_block_template' ) || ! function_exists( 'wp_is_block_theme' ) || ! wp_is_block_theme() ) {
			return;
		}

		/**
		 * Post types the block template is available for.
		 *
		 * @param string[] $post_types Post type slugs.
		 */
		$post_types = (array) apply_filters( 'blank_slate_block_template_post_types', array( 'page', 'post' ) );

		register_block_template(
			'blank-slate//blank-slate',
			array(
				'title'       => esc_html__( 'Blank Slate', 'blank-slate' ),
				'description' => esc_html__( 'A blank page: no header, no footer, only the content.', 'blank-slate' ),
				'content'     => '<!-- wp:post-content /-->',
				'post_types'  => $post_types,
			)
		);
	}
}

if ( ! function_exists( 'blank_slate_get_templates' ) ) {

	/**
	 * Get all registered templates.
	 *
	 * @return array
	 */
	function blank_slate_get_templates() {
		return (array) apply_filters( 'blank_slate_templates', array() );
	}
}

if ( ! function_exists( 'blank_slate_get_template' ) ) {

	/**
	 * Get a registered template.
	 *
	 * @param string $file Template file/path.
	 *
	 * @return string|null
	 */
	function blank_slate_get_template( $file ) {
		$templates = blank_slate_get_templates();

		return isset( $templates[ $file ] ) ? $templates[ $file ] : null;
	}
}

if ( ! function_exists( 'blank_slate_add_template' ) ) {

	/**
	 * Register a new template.
	 *
	 * @param string $file  Template file/path.
	 * @param string $label Label for the template.
	 */
	function blank_slate_add_template( $file, $label ) {
		add_filter(
			'blank_slate_templates',
			function ( array $templates ) use ( $file, $label ) {
				$templates[ $file ] = $label;

				return $templates;
			}
		);
	}
}
