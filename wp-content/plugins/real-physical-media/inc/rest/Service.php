<?php

namespace DevOwl\RealPhysicalMedia\rest;

use DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\Utils\Service as UtilsService;
use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\configuration\Options;
use DevOwl\RealPhysicalMedia\misc\WpPosts;
use DevOwl\RealPhysicalMedia\view\CustomField;
use MatthiasWeb\RealMediaLibrary\rest\Service as RestService;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Main service.
 */
class Service {
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
        register_rest_route($namespace, '/customField/(?P<id>\\d+)', [
            'methods' => 'GET',
            'callback' => [$this, 'routeCustomField'],
            'permission_callback' => [$this, 'permission_callback']
        ]);
        register_rest_route($namespace, '/schema/filelength', [
            'methods' => 'POST',
            'callback' => [$this, 'routeFilelength'],
            'permission_callback' => [$this, 'permission_callback_wp_posts']
        ]);
        register_rest_route($namespace, '/notice/license', [
            'methods' => 'DELETE',
            'callback' => [$this, 'routeNoticeDismissLicense'],
            'permission_callback' => [$this, 'permission_callback_install_plugins']
        ]);
    }
    /**
     * Check if user is allowed to call this service requests with needed `WpPosts` cap.
     */
    public function permission_callback_wp_posts() {
        $permit = \MatthiasWeb\RealMediaLibrary\rest\Service::permit(
            \DevOwl\RealPhysicalMedia\misc\WpPosts::NEEDED_PERMISSION
        );
        return $permit === null ? \true : $permit;
    }
    /**
     * Check if user is allowed to call this service requests.
     */
    public function permission_callback() {
        $permit = \MatthiasWeb\RealMediaLibrary\rest\Service::permit();
        return $permit === null ? \true : $permit;
    }
    /**
     * Check if user is allowed to call this service requests with `install_plugins` cap.
     */
    public function permission_callback_install_plugins() {
        $permit = \MatthiasWeb\RealMediaLibrary\rest\Service::permit('install_plugins');
        return $permit === null ? \true : $permit;
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {get} /real-physical-media/v1/customField/:id Get HTML for custom field
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {int} id The post id
     * @apiName GetCustomFieldHTML
     * @apiGroup Attachment
     * @apiVersion 1.0.0
     * @apiPermission upload_files
     */
    public function routeCustomField($request) {
        $html = \DevOwl\RealPhysicalMedia\view\CustomField::getInstance()->getHtml(get_post($request->get_param('id')));
        return new \WP_REST_Response(['html' => $html]);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {get} /real-physical-media/v1/schema/filelength Set the filelength tables in wp_posts
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {int} post_name The new length of the post_name field
     * @apiParam {int} guid The new length of the guid field
     * @apiName SetFilelengthSchema
     * @apiGroup System
     * @apiVersion 1.0.0
     * @apiPermission administrator
     */
    public function routeFilelength($request) {
        if (($demo = \DevOwl\RealPhysicalMedia\rest\Service::demoNotAllowed()) !== null) {
            return $demo;
        }
        \DevOwl\RealPhysicalMedia\misc\WpPosts::getInstance()->modify(
            (int) $request->get_param('post_name'),
            (int) $request->get_param('guid')
        );
        return new \WP_REST_Response(\true);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {delete} /real-physical-media/v1/notice/license Dismiss the license notice for a given time (transient)
     * @apiName DismissLicenseNotice
     * @apiGroup Plugin
     * @apiVersion 1.0.0
     * @apiPermission install_plugins
     */
    public function routeNoticeDismissLicense() {
        $this->getCore()->isLicenseNoticeDismissed(\true);
        return new \WP_REST_Response(\true);
    }
    /**
     * Checks if the current user has a given capability to perform the action in demo env and throws an error if not.
     *
     * @throws WP_Error
     */
    public static function demoNotAllowed() {
        if (\DevOwl\RealPhysicalMedia\configuration\Options::isDemoEnv()) {
            return new \WP_Error('rest_rpm_demo', __('This service is not allowed in the test drive.', RPM_TD), [
                'status' => 403
            ]);
        }
        return null;
    }
    /**
     * New instance.
     */
    public static function instance() {
        return new \DevOwl\RealPhysicalMedia\rest\Service();
    }
}
