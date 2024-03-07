<?php
/**
 * WordPress Upgrade Functions
 *
 * @package WordPress
 * @subpackage Upgrade
 */

/**
 * Execute database delta queries to add/update tables.
 *
 * @param string $sql SQL statement for creating or updating tables.
 * @return mixed Boolean true on success, otherwise false.
 */
function dbDelta($sql) {
    return true;
}
