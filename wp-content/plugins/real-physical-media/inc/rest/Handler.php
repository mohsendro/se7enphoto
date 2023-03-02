<?php

namespace DevOwl\RealPhysicalMedia\rest;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\handler\Handler as HandlerHandler;
use MatthiasWeb\RealMediaLibrary\rest\Service as RestService;
use DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\Utils\Service as UtilsService;
use WP_Error;
use WP_REST_Response;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Main service.
 */
class Handler {
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
        register_rest_route($namespace, '/handler', [
            'methods' => 'POST',
            'callback' => [$this, 'routeActivate'],
            'permission_callback' => [$this, 'permission_callback']
        ]);
        register_rest_route($namespace, '/handler', [
            'methods' => 'DELETE',
            'callback' => [$this, 'routeDeactivate'],
            'permission_callback' => [$this, 'permission_callback']
        ]);
    }
    /**
     * Check if user is allowed to call this service requests.
     */
    public function permission_callback() {
        $permit = \MatthiasWeb\RealMediaLibrary\rest\Service::permit('install_plugins');
        return $permit === null ? \true : $permit;
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {post} /real-physical-media/v1/handler Activate a handler
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {string} handler The handler id
     * @apiName PostHandler
     * @apiGroup Handler
     * @apiPermission install_plugins
     * @apiVersion 1.0.0
     */
    public function routeActivate($request) {
        $handler = \DevOwl\RealPhysicalMedia\handler\Handler::getInstance();
        if ($handler->set($request->get_param('handler'))) {
            return new \WP_REST_Response($handler->prepareResponse());
        } else {
            return new \WP_Error('rest_rpm_handler_activate', __('The handler could not be activated.', RPM_TD), [
                'status' => 500
            ]);
        }
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {delete} /real-physical-media/v1/handler Delete the current active handler
     * @apiHeader {string} X-WP-Nonce
     * @apiName DeleteHandler
     * @apiGroup Handler
     * @apiPermission install_plugins
     * @apiVersion 1.0.0
     */
    public function routeDeactivate($request) {
        $handler = \DevOwl\RealPhysicalMedia\handler\Handler::getInstance();
        if ($handler->deactivate()) {
            return new \WP_REST_Response($handler->prepareResponse());
        } else {
            return new \WP_Error('rest_rpm_handler_deactivate', __('The handler could not be deactivated.', RPM_TD), [
                'status' => 500
            ]);
        }
    }
    /**
     * New instance.
     */
    public static function instance() {
        return new \DevOwl\RealPhysicalMedia\rest\Handler();
    }
}
