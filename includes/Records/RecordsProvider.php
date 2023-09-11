<?php

namespace SearchIndexerPlugin\Records;

interface RecordsProvider {
	/**
	 * A function to provide the number of pages based on the number of records
	 * to be included per page
	 *
	 * @param int $per_page The number of records to include per page
	 *
	 * @return int the total pages
	 */
	public function get_total_pages_count( $per_page );

	/**
	 * The function to get the records for a given page
	 *
	 * @param int $page    the page number
	 * @param int $per_page number of records per page
	 *
	 * @return array
	 */
	public function get_records( $page, $per_page );

	/**
	 * Function to get records for a given id
	 *
	 * @param mixed $id the id
	 *
	 * @return array
	 */
	public function get_records_for_id( $id );
}
