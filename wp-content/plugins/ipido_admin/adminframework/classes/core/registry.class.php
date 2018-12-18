<?php
class CSFramework_Registry {
	/**
	 * _instance
	 *
	 * @var null
	 */
	private static $_instance = null;

	/**
	 * _instances
	 *
	 * @var array
	 */
	private static $_instances = array();

	/**
	 * @return \CSFramework_Registry
	 */
	public static function instance() {
		if ( null === self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * @param \CSFramework_Abstract $instance
	 */
	public function add( CSFramework_Abstract &$instance ) {
		if ( ! isset( self::$_instances[ $instance->id() ] ) ) {
			self::$_instances[ $instance->id() ] = $instance;
		}
	}

	/**
	 * @param string $plugin_id
	 *
	 * @return bool|mixed
	 */
	public function get( $plugin_id = '' ) {
		return ( isset( self::$_instances[ $plugin_id ] ) ) ? self::$_instances[ $plugin_id ] : false;
	}

	/**
	 * @return array
	 */
	public function all() {
		return self::$_instances;
	}
}

return CSFramework_Registry::instance();
