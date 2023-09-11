<?php
/**
 * Search Indexer Plugin
 *
 * @category  Wordpress-plugin
 * @package   SearchIndexerPlugin
 * @author    Amartya Gaur <amarkaushik1999@gmail.com>
 * @copyright 2023 All rights reserved.
 * @license   GPL2.0-or-later (https://www.gnu.org/licenses/gpl-2.0.html)
 *
 * @wordpress-plugin
 * Plugin Name:       Search Indexer Plugin
 * Plugin URI:        https://wordpress.org/plugins/search-indexer-plugin
 * Description:       A WordPress plugin to easily index search posts to any index.
 * Version:           1.0.0
 * Requires PHP:      5.6
 * Requires at least: 4.7
 * Author:            Amartya Gaur
 * Author URI:        https://github.com/amartya-dev
 * Text Domain:       search-indexer-plugin
 * Domain Path:       /languages
 * License:           GPL V2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/constants.php';

// Initialize the admin page
new SearchIndexerPlugin\WP_Admin();

// Initialize the REST APIs
new \SearchIndexerPlugin\RestApi\RestApi();

// initialize the listeners
new \SearchIndexerPlugin\SetupListeners();
