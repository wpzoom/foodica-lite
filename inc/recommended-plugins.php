<?php
/**
 * Recommended plugins: the list, a one-click installer and the "Recommended Plugins" page
 * under the Foodica admin menu.
 *
 * @package Foodica
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugins recommended for Foodica, all from WordPress.org.
 *
 * Ratings (0-100) and active installs are shown on the cards; icons are the plugins'
 * own icons on WordPress.org.
 *
 * @return array
 */
function foodica_recommended_plugins() {
	$plugins = array(
		array(
			'name'            => 'Recipe Card Blocks',
			'slug'            => 'recipe-card-blocks-by-wpzoom',
			'description'     => __( 'Beautiful recipe cards for your posts, with ingredients, steps, nutrition, ratings and the Schema markup Google needs for recipe results.', 'foodica' ),
			'thumbnail'       => 'https://ps.w.org/recipe-card-blocks-by-wpzoom/assets/icon-256x256.png?rev=2661403',
			'category'        => __( 'Recipes', 'foodica' ),
			'rating'          => 96,
			'active_installs' => 9000,
		),
		array(
			'name'            => 'Inspiro Starter Sites',
			'slug'            => 'inspiro-starter-sites',
			'description'     => __( 'Imports the Foodica Lite demo (posts, pages, menus and widgets) so you can start from a finished site.', 'foodica' ),
			'thumbnail'       => 'https://ps.w.org/inspiro-starter-sites/assets/icon-256x256.png?rev=3243832',
			'category'        => __( 'Demo Import', 'foodica' ),
			'rating'          => 100,
			'active_installs' => 10000,
		),
		array(
			'name'            => 'Social Icons & Share Buttons',
			'slug'            => 'social-icons-widget-by-wpzoom',
			'description'     => __( 'Social media icons for the header and sidebar, and share buttons for your recipes. 400+ icons.', 'foodica' ),
			'thumbnail'       => 'https://ps.w.org/social-icons-widget-by-wpzoom/assets/icon-256x256.gif?rev=3706100',
			'category'        => __( 'Social Media', 'foodica' ),
			'rating'          => 98,
			'active_installs' => 90000,
		),
		array(
			'name'            => 'Instagram Feeds',
			'slug'            => 'instagram-widget-by-wpzoom',
			'description'     => __( 'Show your Instagram feed in the sidebar or anywhere on your site.', 'foodica' ),
			'thumbnail'       => 'https://ps.w.org/instagram-widget-by-wpzoom/assets/icon-256x256.gif?rev=3710657',
			'category'        => __( 'Social Media', 'foodica' ),
			'rating'          => 86,
			'active_installs' => 50000,
		),
		array(
			'name'            => 'WPZOOM Forms',
			'slug'            => 'wpzoom-forms',
			'description'     => __( 'Simple contact forms built with blocks, for your Contact page.', 'foodica' ),
			'thumbnail'       => 'https://ps.w.org/wpzoom-forms/assets/icon-256x256.png?rev=2938926',
			'category'        => __( 'Contact Form', 'foodica' ),
			'rating'          => 100,
			'active_installs' => 10000,
		),
		array(
			'name'            => 'Block Patterns for Food Bloggers',
			'slug'            => 'block-patterns-for-food-bloggers',
			'description'     => __( 'Ready-made block layouts for food blogs: recipe roundups, about sections, newsletter boxes and more.', 'foodica' ),
			'thumbnail'       => 'https://ps.w.org/block-patterns-for-food-bloggers/assets/icon-256x256.png?rev=2735603',
			'category'        => __( 'Patterns', 'foodica' ),
			'rating'          => 100,
			'active_installs' => 700,
		),
		array(
			'name'            => 'Jetpack',
			'slug'            => 'jetpack',
			'description'     => __( 'Powers the homepage slider: Foodica shows the posts tagged "featured" with Jetpack\'s Featured Content.', 'foodica' ),
			'thumbnail'       => 'https://ps.w.org/jetpack/assets/icon.svg?rev=2819237',
			'category'        => __( 'Slider', 'foodica' ),
			'rating'          => 74,
			'active_installs' => 3000000,
			'optional'        => true,
		),
	);

	// WPZOOM AI Connect needs the Abilities API (WordPress 6.9+), so it is only offered where it can run.
	if ( function_exists( 'wp_register_ability' ) ) {
		$plugins[] = array(
			'name'            => 'WPZOOM AI Connect',
			'slug'            => 'wpzoom-ai-connect',
			'description'     => __( 'Edit your site by chatting with Claude or ChatGPT: change pages, colors and layouts, or turn the demo into your own site.', 'foodica' ),
			'thumbnail'       => 'https://ps.w.org/wpzoom-ai-connect/assets/icon.svg?rev=3787541',
			'category'        => __( 'AI', 'foodica' ),
			'rating'          => 0,
			'active_installs' => 0,
			'optional'        => true,
		);
	}

	return $plugins;
}

/**
 * Main file of an installed plugin (e.g. "wpzoom-forms/wpzoom-forms.php"), or '' when it is not installed.
 *
 * @param string $slug Plugin folder name.
 * @return string
 */
function foodica_get_plugin_file( $slug ) {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	foreach ( array_keys( get_plugins() ) as $file ) {
		if ( 0 === strpos( $file, $slug . '/' ) ) {
			return $file;
		}
	}

	return '';
}

/**
 * Whether a recommended plugin is active. Recipe Card Blocks counts as active when its PRO version is.
 *
 * @param string $slug Plugin folder name.
 * @return bool
 */
function foodica_is_plugin_active( $slug ) {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$file = foodica_get_plugin_file( $slug );

	if ( $file && is_plugin_active( $file ) ) {
		return true;
	}

	if ( 'recipe-card-blocks-by-wpzoom' === $slug ) {
		$pro = foodica_get_plugin_file( 'recipe-card-blocks-by-wpzoom-pro' );
		return $pro && is_plugin_active( $pro );
	}

	return false;
}

/**
 * Install (from WordPress.org) and activate a plugin.
 *
 * @param string $slug Plugin folder name on WordPress.org.
 * @return true|WP_Error
 */
function foodica_install_and_activate_plugin( $slug ) {
	if ( ! current_user_can( 'install_plugins' ) || ! current_user_can( 'activate_plugins' ) ) {
		return new WP_Error( 'foodica_no_permission', __( 'You do not have permission to install plugins.', 'foodica' ) );
	}

	$file = foodica_get_plugin_file( $slug );

	if ( ! $file ) {
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/misc.php';

		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => $slug,
				'fields' => array( 'sections' => false ),
			)
		);

		if ( is_wp_error( $api ) ) {
			return $api;
		}

		// Translations would be fetched after the install and print output into the AJAX response.
		remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );

		$upgrader = new Plugin_Upgrader( new WP_Ajax_Upgrader_Skin() );
		$result   = $upgrader->install( $api->download_link );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		if ( true !== $result ) {
			return new WP_Error( 'foodica_install_failed', __( 'The plugin could not be installed. Please install it from Plugins > Add New.', 'foodica' ) );
		}

		wp_clean_plugins_cache();
		$file = foodica_get_plugin_file( $slug );

		if ( ! $file ) {
			return new WP_Error( 'foodica_install_failed', __( 'The plugin was installed but could not be found. Please activate it from the Plugins page.', 'foodica' ) );
		}
	}

	if ( is_plugin_active( $file ) ) {
		return true;
	}

	$activated = activate_plugin( $file );

	return is_wp_error( $activated ) ? $activated : true;
}

/**
 * AJAX: install and activate one recommended plugin.
 *
 * @return void
 */
function foodica_ajax_install_plugin() {
	check_ajax_referer( 'foodica-install-plugin', 'security' );

	$slug    = isset( $_POST['slug'] ) ? sanitize_key( wp_unslash( $_POST['slug'] ) ) : '';
	$allowed = wp_list_pluck( foodica_recommended_plugins(), 'slug' );

	if ( ! in_array( $slug, $allowed, true ) ) {
		wp_send_json_error( array( 'message' => __( 'This plugin is not on the recommended list.', 'foodica' ) ) );
	}

	$result = foodica_install_and_activate_plugin( $slug );

	if ( is_wp_error( $result ) ) {
		wp_send_json_error( array( 'message' => wp_strip_all_tags( $result->get_error_message() ) ) );
	}

	wp_send_json_success( array( 'slug' => $slug ) );
}
add_action( 'wp_ajax_foodica_install_plugin', 'foodica_ajax_install_plugin' );

/**
 * Number of active installs, shortened (e.g. "50K+").
 *
 * @param int $installs Active installs.
 * @return string
 */
function foodica_format_active_installs( $installs ) {
	if ( $installs >= 1000000 ) {
		return number_format_i18n( $installs / 1000000 ) . 'M+';
	}
	if ( $installs >= 1000 ) {
		return number_format_i18n( $installs / 1000 ) . 'K+';
	}
	return number_format_i18n( $installs ) . '+';
}

/**
 * Cards for a list of plugins.
 *
 * @param array $plugins Plugins from foodica_recommended_plugins().
 * @return void
 */
function foodica_plugin_cards( $plugins ) {
	foreach ( $plugins as $plugin ) {
		$slug      = $plugin['slug'];
		$active    = foodica_is_plugin_active( $slug );
		$installed = (bool) foodica_get_plugin_file( $slug );

		if ( $active ) {
			$label = __( 'Active', 'foodica' );
		} elseif ( $installed ) {
			$label = __( 'Activate', 'foodica' );
		} else {
			$label = __( 'Install & Activate', 'foodica' );
		}
		?>
		<div class="section foodica-plugin-item plugin-item-<?php echo esc_attr( $slug ); ?><?php echo $active ? ' plugin-item--active' : ''; ?>" data-slug="<?php echo esc_attr( $slug ); ?>">
			<div class="plugin-item-icon"><img width="80" height="80" src="<?php echo esc_url( $plugin['thumbnail'] ); ?>" alt="" loading="lazy" /></div>
			<div class="plugin-item-info">
				<h4><?php echo esc_html( $plugin['name'] ); ?></h4>
				<div class="plugin-badges">
					<span class="plugin-badge category"><?php echo esc_html( $plugin['category'] ); ?></span>
					<?php if ( empty( $plugin['optional'] ) ) : ?>
						<span class="plugin-badge recommended"><?php esc_html_e( 'Recommended', 'foodica' ); ?></span>
					<?php endif; ?>
				</div>
				<a href="<?php echo esc_url( 'https://wordpress.org/plugins/' . $slug . '/' ); ?>" target="_blank" rel="noopener" class="plugin-view-details"><?php esc_html_e( 'View on wordpress.org', 'foodica' ); ?></a>
				<?php if ( $plugin['rating'] > 0 || $plugin['active_installs'] > 0 ) : ?>
					<div class="plugin-stats">
						<?php if ( $plugin['rating'] > 0 ) : ?>
							<?php /* translators: %s: rating out of 5 */ ?>
							<span class="plugin-rating" title="<?php echo esc_attr( sprintf( __( '%s out of 5 stars', 'foodica' ), number_format_i18n( $plugin['rating'] / 20, 1 ) ) ); ?>">
								<span class="dashicons dashicons-star-filled" aria-hidden="true"></span>
								<?php echo esc_html( number_format_i18n( $plugin['rating'] / 20, 1 ) ); ?>
							</span>
						<?php endif; ?>
						<?php if ( $plugin['active_installs'] > 0 ) : ?>
							<span class="plugin-installs" title="<?php esc_attr_e( 'Active installations', 'foodica' ); ?>">
								<span class="dashicons dashicons-download" aria-hidden="true"></span>
								<?php echo esc_html( foodica_format_active_installs( $plugin['active_installs'] ) ); ?>
							</span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<p class="about"><?php echo esc_html( $plugin['description'] ); ?></p>
				<p class="section_footer">
					<button type="button" class="button button-secondary js-foodica-install-plugin"<?php disabled( $active ); ?>><?php echo esc_html( $label ); ?></button>
				</p>
				<p class="plugin-item-error" role="alert" hidden></p>
			</div>
		</div>
		<?php
	}
}

/**
 * Load the page script on the Recommended Plugins page.
 *
 * @param string $hook Current admin page.
 * @return void
 */
function foodica_recommended_plugins_scripts( $hook ) {
	if ( 'foodica_page_foodica-plugins' !== $hook ) {
		return;
	}

	$file = get_template_directory() . '/assets/admin/js/recommended-plugins.js';

	wp_enqueue_script( 'foodica-recommended-plugins', get_template_directory_uri() . '/assets/admin/js/recommended-plugins.js', array( 'jquery' ), file_exists( $file ) ? filemtime( $file ) : FOODICA_THEME_VERSION, true );
	wp_localize_script(
		'foodica-recommended-plugins',
		'foodicaRecommendedPlugins',
		array(
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'nonce'      => wp_create_nonce( 'foodica-install-plugin' ),
			'installing' => __( 'Installing...', 'foodica' ),
			'active'     => __( 'Active', 'foodica' ),
			'failed'     => __( 'Something went wrong. Please install the plugin from Plugins > Add New.', 'foodica' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'foodica_recommended_plugins_scripts' );

/**
 * The Recommended Plugins page.
 *
 * @return void
 */
function foodica_display_plugins_page() {
	$all         = foodica_recommended_plugins();
	$recommended = array_values( array_filter( $all, function ( $p ) { return empty( $p['optional'] ); } ) );
	$optional    = array_values( array_filter( $all, function ( $p ) { return ! empty( $p['optional'] ); } ) );
	$missing     = array_filter( $recommended, function ( $p ) { return ! foodica_is_plugin_active( $p['slug'] ); } );
	?>
	<div class="wpz-onboard_wrapper foodica-plugins-page">
		<div class="foodica-admin-tabs">

			<?php foodica_admin_page_header( 'plugins' ); ?>

			<div class="wpz-onboard_content-wrapper">
				<div class="wpz-onboard_content">
					<div class="wpz-onboard_content-main">

						<div class="theme-info-wrap">
							<div class="foodica-plugins-header">
								<div>
									<h3 class="wpz-onboard_content-main-title"><?php esc_html_e( 'Recommended Plugins', 'foodica' ); ?></h3>
									<p class="wpz-onboard_content-main-intro"><?php esc_html_e( 'Free plugins that add recipe cards, social icons, forms and the demo importer to Foodica. Install the ones you need with one click.', 'foodica' ); ?></p>
								</div>
								<?php if ( current_user_can( 'install_plugins' ) ) : ?>
									<button type="button" class="button button-primary js-foodica-install-all"<?php disabled( empty( $missing ) ); ?>><?php esc_html_e( 'Install & Activate All', 'foodica' ); ?></button>
								<?php endif; ?>
							</div>

							<div class="wpz-grid-wrap three foodica-plugins-grid foodica-plugins-recommended">
								<?php foodica_plugin_cards( $recommended ); ?>
							</div>

							<?php if ( $optional ) : ?>
								<h3 class="wpz-onboard_content-main-title foodica-plugins-optional-title"><?php esc_html_e( 'Nice to Have', 'foodica' ); ?></h3>
								<p class="wpz-onboard_content-main-intro"><?php esc_html_e( 'Not needed, but they add extra features.', 'foodica' ); ?></p>
								<div class="wpz-grid-wrap three foodica-plugins-grid">
									<?php foodica_plugin_cards( $optional ); ?>
								</div>
							<?php endif; ?>
						</div>

					</div>
				</div>
			</div>

		</div>

		<?php foodica_admin_page_footer(); ?>
	</div>
	<?php
}
