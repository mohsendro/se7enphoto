<?php

namespace DevOwl\RealPhysicalMedia\misc;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\configuration\Options;
use DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\WpdbBatch\Update;
use Exception;
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
/**
 * Singleton seo class.
 */
class Seo {
    use UtilsProvider;
    const MAX_TOP = 20;
    /**
     * Singleton instance.
     */
    private static $me;
    /**
     * Redirect 404 errors if there is found a redirection of an uploaded attachment.
     */
    public function template_redirect() {
        if (is_404() && $this->isActive()) {
            global $wp, $wpdb;
            $url = \urldecode(home_url($wp->request));
            $fromHash = \md5($url);
            $toUrl = $this->getValidUrl($fromHash);
            // Handle GUID accesses
            if (!isset($toUrl)) {
                $guidId = $wpdb->get_var(
                    $wpdb->prepare(
                        'SELECT id FROM ' . $wpdb->posts . ' WHERE guid = %s OR guid = %s ORDER BY id DESC LIMIT 1',
                        set_url_scheme($url, 'https'),
                        set_url_scheme($url, 'http')
                    )
                );
                if ($guidId > 0) {
                    $src = wp_get_attachment_image_src($guidId, 'full');
                    if ($src !== \false) {
                        $toUrl = [
                            'modified' => null,
                            // Only 301er redirect should be used (permanently)
                            'toUrl' => $src[0]
                        ];
                        $toUrl = (object) $toUrl;
                    }
                }
            }
            // Handle redirect
            if (isset($toUrl)) {
                // Decide status code
                $hours = \DevOwl\RealPhysicalMedia\configuration\Options::getInstance()->getSeo301();
                if ($toUrl->modified === null) {
                    $status = 301;
                } else {
                    $hourdiff = \round((\time() - \strtotime($toUrl->modified)) / 3600, 1);
                    $status = $hourdiff > $hours ? 301 : 302;
                }
                // Apply sent query args
                $toUrl = $toUrl->toUrl;
                $query = $_SERVER['QUERY_STRING'];
                if (!empty($query)) {
                    $toUrl .= '?' . $query;
                }
                wp_safe_redirect($toUrl, $status);
            }
        }
    }
    /**
     * Get a valid url from a given hash.
     *
     * @param string $fromHash
     */
    public function getValidUrl($fromHash) {
        global $wpdb;
        $table_name = $this->getTableName('seo');
        // phpcs:disable WordPress.DB.PreparedSQL
        return $wpdb->get_row(
            $wpdb->prepare(
                'SELECT t3.toUrl, t3.modified
            FROM ' .
                    $table_name .
                    ' t1
            INNER JOIN ' .
                    $table_name .
                    ' t2 ON t1.validFullHash = t2.fromHash
            INNER JOIN ' .
                    $table_name .
                    ' t3 ON t2.processId = t3.processId
            WHERE t1.fromHash = %s
            AND t3.size = t1.size',
                $fromHash
            )
        );
        // phpcs:enable WordPress.DB.PreparedSQL
    }
    /**
     * Delete entry from the seo table.
     *
     * @param int $postId
     */
    public function deleteAttachment($postId) {
        global $wpdb;
        $wpdb->delete($this->getTableName('seo'), ['attachment' => $postId], ['%d']);
    }
    /**
     * Get SEO entries.
     *
     * @param int $skip
     * @param int $top
     * @param int $older If set all the older entries for this attachment id are read
     * @param int $attachment Load SEO entries for an specific attachment
     */
    public function get($skip, $top, $older = null, $attachment = null) {
        global $wpdb;
        // Get limit
        $skip = isset($skip) && $skip >= 0 ? $skip : 0;
        $top = $top > 0 ? $top : self::MAX_TOP;
        $top = $top > self::MAX_TOP ? self::MAX_TOP : $top;
        // Get results
        $table_name = $this->getTableName('seo');
        // phpcs:disable WordPress.DB.PreparedSQL
        // phpcs:disable WordPress.DB.PreparedSQLPlaceholders
        $results = $wpdb->get_results(
            $wpdb->prepare(
                'SELECT s.*,
            (SELECT COUNT(*) FROM ' .
                    $table_name .
                    ' WHERE size != "full" AND attachment = s.attachment AND processId = s.processId) AS thumbs
            ' .
                    ($older === null
                        ? ', (SELECT COUNT(*) FROM ' .
                            $table_name .
                            ' WHERE size = "full" AND attachment = s.attachment AND processId != s.processId) AS older'
                        : '') .
                    '
            FROM ' .
                    $table_name .
                    ' AS s
            WHERE s.size="full"
            AND ' .
                    ($attachment !== null && $older === null ? $wpdb->prepare('attachment = %d', $attachment) : '1=1') .
                    '
            AND ' .
                    ($older === null
                        ? 's.fromHash = s.validFullHash'
                        : $wpdb->prepare('s.fromHash != s.validFullHash AND attachment = %d', $older)) .
                    '
            ORDER BY s.modified DESC
            LIMIT %d, %d',
                $skip,
                $top
            ),
            ARRAY_A
        );
        $count = (int) $wpdb->get_var(
            'SELECT COUNT(*) FROM ' . $table_name . ' WHERE size="full" AND fromHash = validFullHash'
        );
        // phpcs:enable WordPress.DB.PreparedSQL
        // phpcs:enable WordPress.DB.PreparedSQLPlaceholders
        // Get database size
        $size = null;
        try {
            $size = $wpdb->get_row($wpdb->prepare('SHOW TABLE STATUS WHERE name = %s', $table_name));
            $size = ((int) $size->Data_length + (int) $size->Index_length) / 1024;
        } catch (\Exception $e) {
            // Silence is golden.
        }
        // Get thumbnail urls and fix types
        foreach ($results as &$value) {
            $value['id'] = \intval($value['id']);
            $value['attachment'] = \intval($value['attachment']);
            $value['thumbs'] = \intval($value['thumbs']);
            if (isset($value['older'])) {
                $value['older'] = \intval($value['older']);
            }
            $value['thumbnail'] = wp_get_attachment_image_url($value['attachment']);
        }
        return ['cnt' => $count, 'items' => $results, 'size' => $size];
    }
    /**
     * Get the count of available SEO entries for a given attachment.
     *
     * @param int $attachment The attachment id
     */
    public function getCount($attachment) {
        global $wpdb;
        // phpcs:disable WordPress.DB.PreparedSQL
        return (int) $wpdb->get_var(
            $wpdb->prepare('SELECT COUNT(*) FROM ' . $this->getTableName('seo') . ' WHERE attachment = %d', $attachment)
        );
        // phpcs:enable WordPress.DB.PreparedSQL
    }
    /**
     * Persist urls for a given attachment.
     *
     * @param int $attachment The attachment id
     * @param array $previousUrls The reuslt of queue\Queue#fetchUrls(true) before the rename process
     * @param array $newUrls The reuslt of queue\Queue#fetchUrls(true)
     */
    public function persistUrls($attachment, $previousUrls, $newUrls) {
        global $wpdb;
        if ($this->isActive() && $newUrls !== \false && !empty($previousUrls)) {
            $table_name = $this->getTableName('seo');
            // Update valid hashes
            $wpbu = new \DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\WpdbBatch\Update($table_name, 'attachment', [
                'attachment' => '%d'
            ]);
            // Create entries
            $deleteHashes = [];
            $processId = \md5(\uniqid());
            foreach ($previousUrls['u'] as $size => $previousUrl) {
                if (isset($newUrls['u'][$size])) {
                    // Size still exists?
                    $fromHash = \md5($previousUrl);
                    // phpcs:disable WordPress.DB.PreparedSQL
                    $wpdb->query(
                        $wpdb->prepare(
                            'INSERT INTO ' .
                                $table_name .
                                '(processId, attachment, size, fromHash, fromUrl, toUrl, modified)
                        VALUES(%s, %d, %s, %s, %s, %s, CURRENT_TIMESTAMP)
                        ON DUPLICATE KEY UPDATE processId=VALUES(processId), attachment=VALUES(attachment), toUrl=VALUES(toUrl), size=VALUES(size), modified = CURRENT_TIMESTAMP',
                            $processId,
                            $attachment,
                            $size,
                            $fromHash,
                            $previousUrl,
                            $newUrls['u'][$size]
                        )
                    );
                    // phpcs:enable WordPress.DB.PreparedSQL
                    $deleteHashes[] = \md5($newUrls['u'][$size]);
                    // Update valid hash
                    $wpbu->add($attachment, ['validFullHash' => $fromHash]);
                }
            }
            // Delete deprecated links
            if (\count($deleteHashes)) {
                // phpcs:disable WordPress.DB.PreparedSQL
                $wpdb->query(
                    'DELETE FROM ' . $table_name . ' WHERE fromHash IN ("' . \implode('","', $deleteHashes) . '")'
                );
                // phpcs:enable WordPress.DB.PreparedSQL
            }
            $wpbu->execute();
        }
    }
    /**
     * Check if SEO system is active.
     *
     * @param boolean $set
     */
    public function isActive($set = null) {
        $field = RPM_OPT_PREFIX . '_seo';
        if ($set !== null) {
            update_option($field, $set ? '1' : '0');
        }
        return get_option($field, '1') === '1';
    }
    /**
     * Get singleton class.
     *
     * @return Seo
     */
    public static function getInstance() {
        return !isset(self::$me) ? (self::$me = new \DevOwl\RealPhysicalMedia\misc\Seo()) : self::$me;
    }
}
