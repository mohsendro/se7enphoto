<?php

namespace DevOwl\RealPhysicalMedia\misc;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
/**
 * Allow to modify the post_name and guid field to allow more than 255 characters.
 */
class WpPosts {
    use UtilsProvider;
    private static $me;
    const NEEDED_PERMISSION = 'administrator';
    const OPTION_DEFAULT_POST_NAME = 200;
    const OPTION_DEFAULT_GUID = 255;
    private $length_post_name = null;
    private $length_guid = null;
    /**
     * Modify the table now!
     *
     * @param string $post_name
     * @param string $guid
     */
    public function modify($post_name, $guid) {
        if (current_user_can(self::NEEDED_PERMISSION)) {
            require_once path_join(ABSPATH, 'wp-admin/includes/schema.php');
            require_once path_join(ABSPATH, 'wp-admin/includes/upgrade.php');
            $this->length_post_name = $post_name;
            $this->length_guid = $guid;
            make_db_current_silent('blog');
        }
    }
    /**
     * Modify queries in dbDelta filter.
     *
     * @param array $queries
     */
    public function dbdelta_create_queries($queries) {
        global $wpdb;
        $table_name = $wpdb->posts;
        if (isset($queries[$table_name]) && !empty($queries[$table_name])) {
            $lengths = $this->getLengths(\true);
            $post_name = $lengths['post_name'];
            $guid = $lengths['guid'];
            $sql = $queries[$table_name];
            $sql = \preg_replace('/(guid\\s+varchar)\\(\\d+\\)/im', '$1(' . $guid . ')', $sql);
            $sql = \preg_replace('/(post_name\\s+varchar)\\(\\d+\\)/im', '$1(' . $post_name . ')', $sql);
            $queries[$table_name] = $sql;
        }
        return $queries;
    }
    /**
     * Get the length of the post_name and guid field.
     *
     * @param boolean $private
     */
    public function getLengths($private = \false) {
        if ($private && $this->length_post_name !== null && $this->length_guid !== null) {
            $post_name = $this->length_post_name;
            $guid = $this->length_guid;
            if (\is_numeric($post_name) && \is_numeric($guid) && $post_name > 0 && $guid > 0) {
                $res = ['post_name' => $post_name, 'guid' => $guid];
                $this->length_post_name = null;
                $this->length_guid = null;
                return $res;
            }
        }
        global $wpdb;
        $tablefields = $wpdb->get_results('DESCRIBE ' . $wpdb->posts . ';');
        $res = ['post_name' => self::OPTION_DEFAULT_POST_NAME, 'guid' => self::OPTION_DEFAULT_GUID];
        foreach ($tablefields as $field) {
            $vfield = \strtolower($field->Field);
            if ($vfield === 'guid' || $vfield === 'post_name') {
                \preg_match('/(\\d+)/', $field->Type, $matches);
                if (isset($matches) && isset($matches[0])) {
                    $res[$vfield] = (int) $matches[0];
                }
            }
        }
        return $res;
    }
    /**
     * Get singleton instance.
     *
     * @return WpPosts
     */
    public static function getInstance() {
        return !isset(self::$me) ? (self::$me = new \DevOwl\RealPhysicalMedia\misc\WpPosts()) : self::$me;
    }
}
