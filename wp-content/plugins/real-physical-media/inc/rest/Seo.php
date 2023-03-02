<?php

namespace DevOwl\RealPhysicalMedia\rest;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\misc\Seo as MiscSeo;
use MatthiasWeb\RealMediaLibrary\rest\Service;
use DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\Utils\Service as UtilsService;
use WP_REST_Request;
use WP_REST_Response;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Main service.
 */
class Seo {
    use UtilsProvider;
    /**
     * C'tor.
     */
    private function __construct() {
        // Silence is golden.
    }
    /**
     * Register endpoints.
     */
    public function rest_api_init() {
        $namespace = \DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\Utils\Service::getNamespace($this);
        register_rest_route($namespace, '/seo/state', [
            'methods' => ['POST', 'DELETE'],
            'callback' => [$this, 'routeState'],
            'permission_callback' => [$this, 'permission_callback_manage_options']
        ]);
        register_rest_route($namespace, '/seo(?:/(?P<id>\\d+))?', [
            'methods' => 'GET',
            'callback' => [$this, 'routeItems'],
            'permission_callback' => [$this, 'permission_callback']
        ]);
        register_rest_route($namespace, '/seo/(?P<processId>[A-Za-z0-9]+)/thumbs', [
            'methods' => 'GET',
            'callback' => [$this, 'routeThumbs'],
            'permission_callback' => [$this, 'permission_callback']
        ]);
        register_rest_route($namespace, '/seo/(?P<id>\\d+)/older', [
            'methods' => 'GET',
            'callback' => [$this, 'routeOlder'],
            'permission_callback' => [$this, 'permission_callback']
        ]);
        register_rest_route($namespace, '/seo/clear/id/(?P<id>\\d+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'routeDeleteId'],
            'permission_callback' => [$this, 'permission_callback_manage_options']
        ]);
        register_rest_route($namespace, '/seo/clear/process/(?P<processId>[A-Za-z0-9]+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'routeDeleteProcess'],
            'permission_callback' => [$this, 'permission_callback_manage_options']
        ]);
        register_rest_route($namespace, '/seo/clear/attachment/(?P<id>\\d+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'routeDeleteAttachment'],
            'permission_callback' => [$this, 'permission_callback_manage_options']
        ]);
        register_rest_route($namespace, '/seo/clear/size/(?P<size>[A-Za-z0-9_-]+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'routeDeleteSize'],
            'permission_callback' => [$this, 'permission_callback_manage_options']
        ]);
        register_rest_route($namespace, '/seo/clear/all', [
            'methods' => 'DELETE',
            'callback' => [$this, 'routeDeleteAll'],
            'permission_callback' => [$this, 'permission_callback_manage_options']
        ]);
    }
    /**
     * Check if user is allowed to call this service requests.
     */
    public function permission_callback() {
        $permit = \MatthiasWeb\RealMediaLibrary\rest\Service::permit();
        return $permit === null ? \true : $permit;
    }
    /**
     * Check if user is allowed to call this service requests with `manage_options` cap.
     */
    public function permission_callback_manage_options() {
        $permit = \MatthiasWeb\RealMediaLibrary\rest\Service::permit('manage_options');
        return $permit === null ? \true : $permit;
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {post,delete} /real-physical-media/v1/seo/state Activate or deactivate the SEO generator
     * @apiHeader {string} X-WP-Nonce
     * @apiName SetSeoState
     * @apiGroup Seo
     * @apiVersion 1.0.0
     * @apiPermission manage_options
     */
    public function routeState($request) {
        return new \WP_REST_Response(
            \DevOwl\RealPhysicalMedia\misc\Seo::getInstance()->isActive($request->get_method() === 'POST')
        );
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {get} /real-physical-media/v1/seo Get SEO entries
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {int} [skip=0] Offset
     * @apiParam {int} [top=20] Top, maximum 20
     * @apiName GetSeo
     * @apiGroup Seo
     * @apiVersion 1.0.0
     * @apiPermission upload_files
     */
    public function routeItems($request) {
        $attachment = $request->get_param('id');
        $result = \DevOwl\RealPhysicalMedia\misc\Seo::getInstance()->get(
            $request->get_param('skip'),
            $request->get_param('top'),
            null,
            $attachment
        );
        global $wpdb;
        // phpcs:disable WordPress.DB.PreparedSQL
        $result['sizes'] = $wpdb->get_col(
            'SELECT size FROM ' . $this->getTableName('seo') . ' WHERE size != "full" GROUP BY size'
        );
        // phpcs:enable WordPress.DB.PreparedSQL
        $result['attachment'] = $attachment;
        $result['state'] = \DevOwl\RealPhysicalMedia\misc\Seo::getInstance()->isActive();
        return new \WP_REST_Response($result);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {get} /real-physical-media/v1/seo/:processId/thumbs Get SEO thumbnail entries for a SEO process id
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {string} processId The process id
     * @apiName GetSeoThumbs
     * @apiGroup Seo
     * @apiVersion 1.0.0
     * @apiPermission upload_files
     */
    public function routeThumbs($request) {
        global $wpdb;
        // Get results
        $id = $request->get_param('processId');
        if (isset($id)) {
            $table_name = $this->getTableName('seo');
            // phpcs:disable WordPress.DB.PreparedSQL
            $results = $wpdb->get_results(
                $wpdb->prepare(
                    'SELECT *
                FROM ' .
                        $table_name .
                        '
                WHERE size != "full"
                AND processId = %s
                ORDER BY modified DESC',
                    $id
                ),
                ARRAY_A
            );
            // phpcs:enable WordPress.DB.PreparedSQL
            foreach ($results as &$value) {
                $value['id'] = \intval($value['id']);
                $value['attachment'] = \intval($value['attachment']);
            }
        } else {
            $results = [];
        }
        return new \WP_REST_Response(['items' => $results]);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {get} /real-physical-media/v1/seo/:id/older Get older redirects for an attachment
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {int} id The attachment id
     * @apiName GetSeoOlder
     * @apiGroup Seo
     * @apiVersion 1.0.0
     * @apiPermission upload_files
     */
    public function routeOlder($request) {
        // Get results
        $id = $request->get_param('id');
        $result = \DevOwl\RealPhysicalMedia\misc\Seo::getInstance()->get(
            $request->get_param('skip'),
            $request->get_param('top'),
            $id
        );
        return new \WP_REST_Response($result);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {delete} /real-physical-media/v1/seo/clear/id/:id Delete a single item
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {int} id The seo id
     * @apiName DeleteSeoId
     * @apiGroup Seo
     * @apiVersion 1.0.0
     * @apiPermission manage_options
     */
    public function routeDeleteId($request) {
        // Get results
        $id = $request->get_param('id');
        global $wpdb;
        $wpdb->delete($this->getTableName('seo'), ['id' => $id], ['%d']);
        return new \WP_REST_Response(\true);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {delete} /real-physical-media/v1/seo/clear/process/:processId Delete SEO entries by process id
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {string} processId The process id
     * @apiName DeleteSeoProcess
     * @apiGroup Seo
     * @apiVersion 1.0.0
     * @apiPermission manage_options
     */
    public function routeDeleteProcess($request) {
        // Get results
        $id = $request->get_param('processId');
        global $wpdb;
        $wpdb->delete($this->getTableName('seo'), ['processId' => $id], ['%s']);
        return new \WP_REST_Response(\true);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {delete} /real-physical-media/v1/seo/clear/attachment/:id Delete SEO entries by attachment
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {int} id The seo id
     * @apiName DeleteSeoAttachment
     * @apiGroup Seo
     * @apiVersion 1.0.0
     * @apiPermission manage_options
     */
    public function routeDeleteAttachment($request) {
        // Get results
        $id = $request->get_param('id');
        global $wpdb;
        $wpdb->delete($this->getTableName('seo'), ['attachment' => $id], ['%d']);
        return new \WP_REST_Response(\true);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {delete} /real-physical-media/v1/seo/clear/size/:size Delete SEO entries by size
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {string} size The size
     * @apiName DeleteSeoSize
     * @apiGroup Seo
     * @apiVersion 1.0.0
     * @apiPermission manage_options
     */
    public function routeDeleteSize($request) {
        // Get results
        $id = $request->get_param('size');
        global $wpdb;
        $wpdb->delete($this->getTableName('seo'), ['size' => $id], ['%s']);
        return new \WP_REST_Response(\true);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {delete} /real-physical-media/v1/seo/clear/all Delete all SEO entries
     * @apiHeader {string} X-WP-Nonce
     * @apiName DeleteSeo
     * @apiGroup Seo
     * @apiVersion 1.0.0
     * @apiPermission manage_options
     */
    public function routeDeleteAll($request) {
        // Get results
        $id = $request->get_param('size');
        global $wpdb;
        // phpcs:disable WordPress.DB.PreparedSQL
        $wpdb->query('DELETE FROM ' . $this->getTableName('seo'));
        // phpcs:enable WordPress.DB.PreparedSQL
        return new \WP_REST_Response(\true);
    }
    /**
     * New instance.
     */
    public static function instance() {
        return new \DevOwl\RealPhysicalMedia\rest\Seo();
    }
}
