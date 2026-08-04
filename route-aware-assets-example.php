<?php
/**
 * Anonymized, standalone pattern for route-aware WordPress styles.
 * This is not CottonCloud production source.
 */

defined('ABSPATH') || exit;

function studio_asset_version(string $relative_path): string {
	$absolute_path = get_theme_file_path($relative_path);

	return is_readable($absolute_path)
		? (string) filemtime($absolute_path)
		: '1';
}

function studio_owns_full_landing_markup(): bool {
	if (is_admin() || ! is_page()) {
		return false;
	}

	if (is_page(array('checkout', 'account'))) {
		return false;
	}

	return is_page(array('service-a', 'service-b'));
}

function studio_enqueue_route_styles(): void {
	if (is_admin()) {
		return;
	}

	wp_enqueue_style(
		'studio-base',
		get_theme_file_uri('assets/css/base.css'),
		array(),
		studio_asset_version('assets/css/base.css')
	);

	if (! is_page('service-a')) {
		return;
	}

	wp_enqueue_style(
		'studio-service-a',
		get_theme_file_uri('assets/css/service-a.css'),
		array('studio-base'),
		studio_asset_version('assets/css/service-a.css')
	);
}
add_action('wp_enqueue_scripts', 'studio_enqueue_route_styles', 20);

function studio_dequeue_unused_block_styles(): void {
	if (! studio_owns_full_landing_markup()) {
		return;
	}

	foreach (array('wp-block-library', 'wp-block-library-theme', 'classic-theme-styles', 'global-styles') as $handle) {
		wp_dequeue_style($handle);
	}
}
add_action('wp_enqueue_scripts', 'studio_dequeue_unused_block_styles', 999);
