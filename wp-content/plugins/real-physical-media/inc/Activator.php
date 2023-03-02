<?php

namespace DevOwl\RealPhysicalMedia;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\handler\Handler;
use DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\Utils\Activator as UtilsActivator;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * The activator class handles the plugin relevant activation hooks: Uninstall, activation,
 * deactivation and installation. The "installation" means installing needed database tables.
 */
class Activator {
    use UtilsProvider;
    use UtilsActivator;
    /**
     * Method gets fired when the user activates the plugin.
     */
    public function activate() {
        // Check if handler plugin is activate and automatically activate it
        $instance = \DevOwl\RealPhysicalMedia\handler\Handler::getInstance();
        if ($instance->getCurrent() === null) {
            $handlers = $instance->get();
            foreach ($handlers as $handler) {
                if (is_plugin_active($handler['file'])) {
                    $instance->set($handler['id']);
                    break;
                }
            }
        }
    }
    /**
     * Method gets fired when the user activates Real Media Library.
     */
    public function rmlActivate() {
        $this->install();
    }
    /**
     * Method gets fired when the user deactivates the plugin.
     */
    public function deactivate() {
        // Your implementation...
    }
    /**
     * Install tables, stored procedures or whatever in the database.
     * This method is always called when the version bumps up or for
     * the first initial activation.
     *
     * @param boolean $errorlevel If true throw errors
     */
    public function dbDelta($errorlevel) {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        // Your table installation here...
        $table_name = $this->getTableName('queue');
        $sql = "CREATE TABLE {$table_name} (\n\t\t    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,\n\t\t    processId char(32),\n\t\t    attachment bigint(20) NOT NULL,\n\t\t    log bigint(20) unsigned,\n\t\t\tprocessLoaded tinyint(2) unsigned NOT NULL DEFAULT 0,\n\t\t\tprocessTotal tinyint(2) unsigned NOT NULL,\n\t\t\tcreated timestamp DEFAULT CURRENT_TIMESTAMP,\n\t\t\tcleanup_path tinytext,\n\t\t\tpreviousUrls text,\n\t\t\tPRIMARY KEY  (id),\n\t\t\tUNIQUE KEY id (attachment)\n\t\t) {$charset_collate};";
        dbDelta($sql);
        if ($errorlevel) {
            $wpdb->print_error();
        }
        $table_name = $this->getTableName('log');
        $sql = "CREATE TABLE {$table_name} (\n\t\t    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,\n\t\t    attachment bigint(20) NOT NULL,\n\t\t\tduration mediumint(10) NOT NULL,\n\t\t\tdone tinyint(1) unsigned NOT NULL,\n\t\t\tfromPath tinytext,\n\t\t\ttoPath tinytext,\n\t\t\tPRIMARY KEY  (id)\n\t\t) {$charset_collate};";
        dbDelta($sql);
        if ($errorlevel) {
            $wpdb->print_error();
        }
        $table_name = $this->getTableName('seo');
        $sql = "CREATE TABLE {$table_name} (\n\t\t    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,\n\t\t    processId char(32),\n\t\t    attachment bigint(20) NOT NULL,\n\t\t    size varchar(55) NOT NULL,\n\t\t    fromHash char(32),\n\t\t\tfromUrl tinytext,\n\t\t\ttoUrl tinytext,\n\t\t\tmodified timestamp DEFAULT CURRENT_TIMESTAMP,\n\t\t\tvalidFullHash char(32),\n\t\t\tPRIMARY KEY  (id),\n\t\t\tUNIQUE KEY id (fromHash)\n\t\t) {$charset_collate};";
        dbDelta($sql);
        if ($errorlevel) {
            $wpdb->print_error();
        }
    }
}
