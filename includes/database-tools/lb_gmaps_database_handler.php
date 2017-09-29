<?php

class LB_GMaps_Database_Handler {

	private $database;

	private $markers_table_name;

	private $maps_table_name;

	public function __construct() {
		global $wpdb;
		$this->set_database( $wpdb );
		$this->set_maps_table_name( $this->get_database()->prefix . 'lb_gmaps' );
		$this->set_markers_table_name( $this->get_database()->prefix . 'lb_gmaps_markers' );
	}

	public function create_tables() {
		$charset_collate = $this->get_database()->get_charset_collate();

		$markers_sql = "CREATE TABLE IF NOT EXISTS {$this->get_markers_table_name()} (
			uniqueness VARCHAR(150) NOT NULL,
			post_id mediumint(9) NOT NULL,
			lng VARCHAR(250) NOT NULL,
			lat VARCHAR(250) NOT NULL,
			name VARCHAR(250) NULL,
			content text NULL,
			PRIMARY KEY (uniqueness)
		) $charset_collate;";

		$maps_sql = "CREATE TABLE IF NOT EXISTS {$this->get_maps_table_name()} (
			post_id mediumint(9) NOT NULL,
			lng VARCHAR(250) NOT NULL,
			lat VARCHAR(250) NOT NULL,
			zoom varchar(250) NOT NULL,
			PRIMARY KEY (post_id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( $markers_sql );
		dbDelta( $maps_sql );
	}

	public function save_marker( $args ) {
		return $this->get_database()->replace( $this->get_markers_table_name(), $args );
	}

	public function save_map( $args ) {
		return $this->get_database()->replace( $this->get_maps_table_name(), $args );
	}

	public function get_row_by_post_id( $table, $id ) {
		return $this->get_database()->get_row( "SELECT * FROM $table WHERE post_id = $id", OBJECT );
	}

	public function get_rows_by_post_id( $table, $id ) {
		return $this->get_database()->get_results( $this->get_database()->prepare( "SELECT * FROM $table WHERE post_id = %d", $id ) );
	}

	/**
	 * @return QM_DB|wpdb
	 */
	public function get_database() {
		return $this->database;
	}

	/**
	 * @return string
	 */
	public function get_markers_table_name() {
		return $this->markers_table_name;
	}

	/**
	 * @return string
	 */
	public function get_maps_table_name() {
		return $this->maps_table_name;
	}

	/**
	 * @param QM_DB|wpdb $database
	 */
	public function set_database( $database ) {
		$this->database = $database;
	}

	/**
	 * @param string $markers_table_name
	 */
	public function set_markers_table_name( $markers_table_name ) {
		$this->markers_table_name = $markers_table_name;
	}

	/**
	 * @param string $maps_table_name
	 */
	public function set_maps_table_name( $maps_table_name ) {
		$this->maps_table_name = $maps_table_name;
	}




}