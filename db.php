<?php
/**
 * Database
 *
 * @package Super Buttons
 */

/**
 * Create buttons table
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.0
 */
function super_buttons_create_db() {
	global $wpdb;
	$version         = get_option( '_sb_db', 0 );
	$charset_collate = $wpdb->get_charset_collate();
	$table_name      = SUPER_BUTTONS__TABLE_NAME;

	$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
			title varchar(255) DEFAULT 'New Button',
			views smallint(5) NOT NULL,
			clicks smallint(5) NOT NULL,
			value LONGTEXT DEFAULT NULL,
			UNIQUE KEY id (id)
		) $charset_collate";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}

/**
 * Create buttons meta table
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0
 */
function super_buttons_create_meta_db() {
	global $wpdb;
	$version         = get_option( '_sb_meta_db', 0 );
	$charset_collate = $wpdb->get_charset_collate();
	$table_name      = SUPER_BUTTONS__TABLE_NAME . '_meta';

	$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			button_id mediumint(9) NOT NULL,
			meta_key VARCHAR(100) NOT NULL,
			property VARCHAR(100) DEFAULT NULL,
			state VARCHAR(100) NOT NULL,
			value LONGTEXT DEFAULT NULL,
			UNIQUE KEY id (id),
			PRIMARY KEY (button_id, meta_key)
		) $charset_collate";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	update_option( '_sb_meta_db', $version );
}

/**
 * Create the required database tables
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.0
 */
function super_buttons_install() {
	super_buttons_create_db();
	super_buttons_create_meta_db();
}
