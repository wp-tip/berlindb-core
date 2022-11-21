<?php
/**
 * Cache Class.
 *
 * @package     Database
 * @subpackage  Cache
 * @copyright   Copyright (c) 2021
 * @license     https://opensource.org/licenses/MIT MIT
 * @since       2.0.2
 */
namespace BerlinDB\Database;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

class Cache {

	public function __construct( $cache_group ) {

	}

	private function can_cache() {
		return ! wp_suspend_cache_addition();
	}

	/**
	 * Add a cache value for a key and group.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key    Cache key.
	 * @param mixed  $value  Cache value.
	 * @param string $group  Cache group. Defaults to $this->cache_group
	 * @param int    $expire Expiration.
	 */
	public function add( $key = '', $value = '', $group = '', $expire = 0 ) {

		// Bail if cache invalidation is suspended
		if ( ! $this->can_cache() ) {
			return false;
		}

		// Bail if no cache key
		if ( empty( $key ) ) {
			return false;
		}

		// Add to the cache
		return wp_cache_add( $key, $value, $group, $expire );
	}

	/**
	 * Get a cache value for a key and group.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $key   Cache key.
	 * @param string  $group Cache group. Defaults to $this->cache_group
	 * @param bool    $force
	 */
	public function get( $key = '', $group = '', $force = false ) {
		if ( empty( $key ) ) {
			return false;
		}

		// Return from the cache
		return wp_cache_get( $key, $group, $force );
	}

	/**
	 * Set a cache value for a key and group.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key    Cache key.
	 * @param mixed  $value  Cache value.
	 * @param string $group  Cache group. Defaults to $this->cache_group
	 * @param int    $expire Expiration.
	 */
	public function set( $key = '', $value = '', $group = '', $expire = 0 ) {
		// Bail if no cache key
		if ( empty( $key ) ) {
			return;
		}

		// Bail if cache invalidation is suspended
		if ( ! $this->can_cache() ) {
			return;
		}

		// Update the cache
		wp_cache_set( $key, $value, $group, $expire );
	}

	/**
	 * Delete a cache key for a group.
	 *
	 * @since 1.0.0
	 *
	 * @global bool $_wp_suspend_cache_invalidation
	 *
	 * @param string $key   Cache key.
	 * @param string $group Cache group. Defaults to $this->cache_group
	 */
	public function delete( $key = '', $group = '' ) {
		// Bail if no cache key
		if ( empty( $key ) ) {
			return;
		}

		global $_wp_suspend_cache_invalidation;

		// Bail if cache invalidation is suspended
		if ( ! empty( $_wp_suspend_cache_invalidation ) ) {
			return;
		}

		// Delete the cache
		wp_cache_delete( $key, $group );
	}

}
