<?php
/**
 * Minimal contract test for the anonymized route-aware asset example.
 */

define('ABSPATH', __DIR__);

$studio_test_route = '';
$studio_test_admin = false;
$studio_test_enqueued = array();
$studio_test_dequeued = array();
$studio_test_hooks = array();

function add_action(string $hook, string $callback, int $priority = 10): void {
	global $studio_test_hooks;
	$studio_test_hooks[] = array($hook, $callback, $priority);
}

function is_admin(): bool {
	global $studio_test_admin;
	return $studio_test_admin;
}

function is_page($expected = null): bool {
	global $studio_test_route;

	if (null === $expected) {
		return '' !== $studio_test_route;
	}

	return in_array($studio_test_route, (array) $expected, true);
}

function get_theme_file_path(string $relative_path): string {
	return __FILE__;
}

function get_theme_file_uri(string $relative_path): string {
	return 'https://example.test/theme/' . ltrim($relative_path, '/');
}

function wp_enqueue_style(string $handle, string $src, array $dependencies, string $version): void {
	global $studio_test_enqueued;
	$studio_test_enqueued[$handle] = array($src, $dependencies, $version);
}

function wp_dequeue_style(string $handle): void {
	global $studio_test_dequeued;
	$studio_test_dequeued[] = $handle;
}

require __DIR__ . '/route-aware-assets-example.php';

function studio_test_case(string $route, bool $admin, array $expected_enqueued, array $expected_dequeued): void {
	global $studio_test_route, $studio_test_admin, $studio_test_enqueued, $studio_test_dequeued;

	$studio_test_route = $route;
	$studio_test_admin = $admin;
	$studio_test_enqueued = array();
	$studio_test_dequeued = array();

	studio_enqueue_route_styles();
	studio_dequeue_unused_block_styles();

	$actual_enqueued = array_keys($studio_test_enqueued);
	if ($actual_enqueued !== $expected_enqueued || $studio_test_dequeued !== $expected_dequeued) {
		throw new RuntimeException(
			$route . ' failed: ' . json_encode(array($actual_enqueued, $studio_test_dequeued), JSON_UNESCAPED_SLASHES)
		);
	}
}

$block_styles = array('wp-block-library', 'wp-block-library-theme', 'classic-theme-styles', 'global-styles');

studio_test_case('service-a', false, array('studio-base', 'studio-service-a'), $block_styles);
studio_test_case('service-b', false, array('studio-base'), $block_styles);
studio_test_case('blog', false, array('studio-base'), array());
studio_test_case('checkout', false, array('studio-base'), array());
studio_test_case('service-a', true, array(), array());

echo "route-aware asset contract: PASS\n";
