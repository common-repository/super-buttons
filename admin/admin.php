<?php
/**
 * Register Admin Page
 *
 * @package Super Buttons
 */

/**
 * Base view for admin page
 *
 * @return void
 *
 * @author Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @since 1.0.0
 */
function super_buttons_admin_view() {
	?>
	<div class="uk-section-muted uk-preserve-color uk-box-shadow-small" style="background: linear-gradient(150deg,#53f 15%,#05d5ff 70%,#a6ffcb 94%);">
		<div class="uk-container">
			<div class="uk-position-relative">
				<nav class="uk-navbar-container uk-navbar-transparent uk-navbar" uk-navbar>
					<div class="uk-navbar-left">
						<a class="uk-navbar-item uk-logo uk-padding-remove" href="#" style="color:#fff;text-shadow:1px 1px #6d6d6d;">
							Super Buttons &nbsp;
							<span class="uk-text-small" style="color:#ababab;font-size:0.6rem;line-height:0.6;text-shadow:none;margin-top:9px;">
								<?php
									// translators: plugin version.
									echo wp_kses_post( apply_filters( 'super_buttons_version', sprintf( __( 'Free v%s', 'super-buttons' ), SUPER_BUTTONS__VERSION ) ) );
								?>
							</span>
						</a>
					</div>
					<div class="uk-navbar-right">
						<ul class="uk-navbar-nav">
							<li>
								<a target="_blank" href="https://help.thewebsitedev.com/category/6-super-buttons" style="color:#fff;text-shadow:1px 1px #6d6d6d;"><?php esc_html_e( 'Documentation', 'super-buttons' ); ?></a>
							</li>
							<li>
								<a target="_blank" href="https://thewebsitedev.com/account/?tab=support" style="color:#fff;text-shadow:1px 1px #6d6d6d;"><?php esc_html_e( 'Support', 'super-buttons' ); ?></a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</div>
	</div>
	<div class="uk-section" style="padding-top:40px;padding-bottom:40px;" uk-height-viewport>
		<div class="uk-container">
			<div id="root">
				<div class="uk-text-center">
					<span uk-icon="lock" ratio="5"></span>
					<p class="uk-text-large"><?php esc_html_e( 'You are not authorized to view this page.', 'super-buttons' ); ?></p>
				</div>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Create admin page
 *
 * @return void
 *
 * @author Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @since 1.0.0
 */
function super_buttons_admin_page() {
	$title = apply_filters( 'super_buttons_menu_label', __( 'Buttons', 'super-buttons' ) );
	add_menu_page(
		$title,
		$title,
		'read',
		'super-buttons',
		'super_buttons_admin_view',
		SUPER_BUTTONS__PLUGIN_URL . 'assets/images/superman.png',
		49
	);
}
add_action( 'admin_menu', 'super_buttons_admin_page' );

/**
 * Add a rewrite rule for admin pages
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.0
 */
function super_buttons_rewrite_rule() {
	if ( is_admin() ) {
		add_rewrite_rule(
			'/super-buttons/',
			'admin.php?page=super-buttons',
			'top'
		);
	}
}
add_action( 'init', 'super_buttons_rewrite_rule' );

/**
 * Include required scripts
 *
 * @param String $hook admin page slug.
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.0
 */
function super_buttons_admin_scripts( $hook ) {
	// Load only on /wp-admin/admin.php?page=super-buttons.
	if ( 'toplevel_page_super-buttons' !== $hook ) {
		return;
	}
	wp_enqueue_style( 'super_buttons_admin', SUPER_BUTTONS__PLUGIN_URL . 'assets/admin/dist/css/admin.min.css', '', '2.0.0' );
	wp_enqueue_style( 'super_buttons_style', SUPER_BUTTONS__PLUGIN_URL . 'assets/style.min.css', '', '2.0.0' );
	wp_enqueue_script( 'uikit', SUPER_BUTTONS__PLUGIN_URL . 'assets/admin/dist/js/uikit.min.js', '', '3.0.0-beta.32', true );
	wp_enqueue_script( 'uikit-icons', SUPER_BUTTONS__PLUGIN_URL . 'assets/admin/dist/js/uikit-icons.min.js', '', '3.0.0-beta.32', true );

	$user     = wp_get_current_user();
	$settings = maybe_unserialize( get_option( 'super_buttons', $GLOBALS['SUPER_BUTTONS_DEFAULT_SETTINGS'] ) );
	$settings = isset( $settings['rolesAllowed'] ) ? $settings['rolesAllowed'] : array( 'administrator' );
	$allowed  = false;
	foreach ( $settings as $setting ) {
		if ( in_array( $setting, (array) $user->roles, true ) ) {
			// The user has the "author" role.
			$allowed = true;
		}
	}
	if ( $allowed ) {
		wp_enqueue_script( 'super_buttons_script', SUPER_BUTTONS__PLUGIN_URL . 'assets/admin/dist/js/bundle.min.js', array( 'jquery' ), '2.0.0', true );
		$admin_path = wp_unslash( strtok( $_SERVER['REQUEST_URI'], '?' ) );
		wp_localize_script(
			'super_buttons_script',
			'super_buttons',
			array(
				'admin_path'           => $admin_path,
				'ajax_url'             => admin_url( 'admin-ajax.php' ),
				'ajax_nonce'           => wp_create_nonce( 'super-buttons-admin' ),
				'errorText'            => __( 'ERROR:', 'super-buttons' ),
				'buttonsText'          => __( 'Buttons', 'super-buttons' ),
				'settingsText'         => __( 'Settings', 'super-buttons' ),
				'importText'           => __( 'Import', 'super-buttons' ),
				'getProText'           => __( 'Get PRO', 'super-buttons' ),
				'addNewText'           => __( 'Add New', 'super-buttons' ),
				'noMoreButtonsText'    => __( 'No more buttons', 'super-buttons' ),
				'loadMoreText'         => __( 'Load more', 'super-buttons' ),
				'addNew2Text'          => __( '+ Add New', 'super-buttons' ),
				'copiedText'           => __( 'Copied', 'super-buttons' ),
				'clicksText'           => __( 'Clicks', 'super-buttons' ),
				'clickText'            => __( 'Click', 'super-buttons' ),
				'previewText'          => __( 'Preview', 'super-buttons' ),
				'desktopText'          => __( 'Desktop', 'super-buttons' ),
				'tabletText'           => __( 'Tablet', 'super-buttons' ),
				'mobileText'           => __( 'Mobile', 'super-buttons' ),
				'copyDesktopText'      => __( 'Copy from Desktop', 'super-buttons' ),
				'optionsText'          => __( 'Options', 'super-buttons' ),
				'addMoreText'          => __( 'Add More', 'super-buttons' ),
				'colorText'            => __( 'Color', 'super-buttons' ),
				'sidesText'            => __( 'Sides', 'super-buttons' ),
				'statesText'           => __( 'States', 'super-buttons' ),
				'uploadImageText'      => __( 'Select or Upload Image', 'super-buttons' ),
				'addImageText'         => __( 'Add Image', 'super-buttons' ),
				'titleText'            => __( 'Title', 'super-buttons' ),
				'shortCodeText'        => __( 'Short Code', 'super-buttons' ),
				'createdText'          => __( 'Created', 'super-buttons' ),
				'actionsText'          => __( 'Actions', 'super-buttons' ),
				'saveChangesText'      => __( 'Please save changes.', 'super-buttons' ),
				'updatedText'          => __( 'Updated', 'super-buttons' ),
				'saveText'             => __( 'Save', 'super-buttons' ),
				'fullscreenText'       => __( 'Fullscreen', 'super-buttons' ),
				'redoText'             => __( 'Redo', 'super-buttons' ),
				'undoText'             => __( 'Undo', 'super-buttons' ),
				'buttonTitleText'      => __( 'Enter Button Title', 'super-buttons' ),
				'automaticUpdatesText' => __( 'Automatic Updates', 'super-buttons' ),
				'licenseKeyText'       => __( 'License Key', 'super-buttons' ),
				'licenseKeyHolderText' => __( 'Please enter license key', 'super-buttons' ),
				'licenseStatusText'    => __( 'License Status', 'super-buttons' ),
				'activateText'         => __( 'Activate', 'super-buttons' ),
				'validText'            => __( 'Valid', 'super-buttons' ),
				'invalidText'          => __( 'Invalid', 'super-buttons' ),
				'fileUploadHolderText' => __( 'Select file', 'super-buttons' ),
				'buttonsHolderText'    => __( 'Create your first button', 'super-buttons' ),
				'sizeText'             => __( 'Size', 'super-buttons' ),
				'styleText'            => __( 'Style', 'super-buttons' ),
				'copyText'             => __( 'Copy', 'super-buttons' ),
				'editText'             => __( 'Edit', 'super-buttons' ),
				'duplicateText'        => __( 'Duplicate', 'super-buttons' ),
				'downloadText'         => __( 'Download', 'super-buttons' ),
				'deleteText'           => __( 'Delete', 'super-buttons' ),
			)
		);
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'super_buttons_admin_scripts' );

/**
 * Add Super Buttons Policy to "Privacy Policy" page during creation.
 *
 * @param String $content policy content.
 *
 * @return String
 *
 * @author Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @since 1.0.7
 */
function super_buttons_add_policy( $content ) {
	$content .= '<h3>' . __( 'Plugin: Super Buttons', 'super-buttons' ) . '</h3>';
	$content .=
		'<p>' . __( 'IP address of every user who clicks a button (created with Super Buttons) is stored in the database for analytics. So, please update your privacy policy to reflect the same.
		', 'super-buttons' ) . '</p>';
	return $content;
}
add_filter( 'wp_get_default_privacy_policy_content', 'super_buttons_add_policy' );
