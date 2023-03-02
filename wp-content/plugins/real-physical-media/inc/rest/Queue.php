<?php

namespace DevOwl\RealPhysicalMedia\rest;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\configuration\Options;
use DevOwl\RealPhysicalMedia\listener\Lockfile;
use DevOwl\RealPhysicalMedia\queue\Queue as QueueQueue;
use DevOwl\RealPhysicalMedia\rest\Service as RestService;
use DevOwl\RealPhysicalMedia\Util;
use DevOwl\RealPhysicalMedia\view\AdminBar;
use MatthiasWeb\RealMediaLibrary\rest\Service;
use DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\Utils\Service as UtilsService;
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
class Queue {
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
        register_rest_route($namespace, '/queue', [
            'methods' => 'GET',
            'callback' => [$this, 'routeQueue'],
            'permission_callback' => [$this, 'permission_callback']
        ]);
        register_rest_route($namespace, '/queue', [
            'methods' => 'POST',
            'callback' => [$this, 'routeQueuePost'],
            'permission_callback' => [$this, 'permission_callback']
        ]);
        register_rest_route($namespace, '/queue/automatic', [
            'methods' => 'POST',
            'callback' => [$this, 'routeQueueAutomatic'],
            'permission_callback' => [$this, 'permission_callback_manage_options'],
            'args' => ['state' => ['type' => 'boolean']]
        ]);
        register_rest_route($namespace, '/queue/automatic-hint/dismiss', [
            'methods' => 'DELETE',
            'callback' => [$this, 'routeQueueAutomaticHintDismiss'],
            'permission_callback' => [$this, 'permission_callback_manage_options']
        ]);
        register_rest_route($namespace, '/queue/notice/dismiss', [
            'methods' => 'DELETE',
            'callback' => [$this, 'routeQueueNoticeDismiss'],
            'permission_callback' => [$this, 'permission_callback_manage_options']
        ]);
        register_rest_route($namespace, '/queue/item/(?P<id>\\d+)', [
            'methods' => 'POST',
            'callback' => [$this, 'routeQueueItemAdd'],
            'permission_callback' => [$this, 'permission_callback']
        ]);
        register_rest_route($namespace, '/queue/item/potential', [
            'methods' => 'POST',
            'callback' => [$this, 'routeQueueItemAddPotential'],
            'permission_callback' => [$this, 'permission_callback_manage_options']
        ]);
        register_rest_route($namespace, '/queue/cron/(?P<token>[A-za-z0-9]+)', [
            'methods' => 'GET',
            'callback' => [$this, 'routeQueueCron'],
            'permission_callback' => '__return_true'
        ]);
        register_rest_route($namespace, '/lockfiles/reflect', [
            'methods' => 'POST',
            'callback' => [$this, 'routeLockfilesReflect'],
            'permission_callback' => [$this, 'permission_callback_manage_options']
        ]);
        register_rest_route($namespace, '/lockfiles', [
            'methods' => 'DELETE',
            'callback' => [$this, 'routeLockfilesDelete'],
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
        $permit = \MatthiasWeb\RealMediaLibrary\rest\Service::permit();
        return $permit === null ? \true : $permit;
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {get} /real-physical-media/v1/queue Get queue information
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {int} [skip=0] Offset
     * @apiParam {int} [top=5] Top, maximum 100
     * @apiName GetQueue
     * @apiGroup Queue
     * @apiVersion 1.0.0
     * @apiPermission upload_files
     */
    public function routeQueue($request) {
        $queue = \DevOwl\RealPhysicalMedia\queue\Queue::getInstance();
        return new \WP_REST_Response($queue->prepareResponse($request->get_param('skip'), $request->get_param('top')));
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {post} /real-physical-media/v1/queue Process the queue and return back updating rows
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {int} [skip=0] Offset
     * @apiParam {int} [top=5] Top, maximum 100
     * @apiParam {string} [skip] The skip mechanism: 'retry' or 'skip'
     * @apiName PostQueue
     * @apiGroup Queue
     * @apiVersion 1.0.0
     * @apiPermission upload_files
     */
    public function routeQueuePost($request) {
        $do = $this->doQueue($request);
        if (is_wp_error($do)) {
            return $do;
        }
        return new \WP_REST_Response($do);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {get} /real-physical-media/v1/queue/cron/:token Process the queue for cron URL
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {string} token The unique token for the given blog
     * @apiParam {string} [skip=0] Offset
     * @apiParam {int} [top=5] Top, maximum 100
     * @apiParam {string} [skip] The skip mechanism: 'retry' or 'skip'
     * @apiName CronQueue
     * @apiGroup Queue
     * @apiVersion 1.0.0
     */
    public function routeQueueCron($request) {
        if (
            \DevOwl\RealPhysicalMedia\configuration\Options::getInstance()->getCronJobToken() !==
            $request->get_param('token')
        ) {
            return new \WP_Error(
                'rpm_cron_queue_process',
                __(
                    'You are not allowed to do this. You can find your cronjob service URLs in the media settings.',
                    RPM_TD
                ),
                ['status' => 403]
            );
        }
        $do = $this->doQueue($request);
        if (is_wp_error($do)) {
            return $do;
        }
        return new \WP_REST_Response($do);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {post} /real-physical-media/v1/queue/item/:id Add an item to the queue
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {int} id The attachment id
     * @apiName AddItem
     * @apiGroup Queue
     * @apiVersion 1.0.0
     * @apiPermission upload_files
     */
    public function routeQueueItemAdd($request) {
        if (($permit = \MatthiasWeb\RealMediaLibrary\rest\Service::permit()) !== null) {
            return $permit;
        }
        $queue = \DevOwl\RealPhysicalMedia\queue\Queue::getInstance();
        // Add item to queue
        $result = $queue->add($request->get_param('id'));
        if (is_wp_error($result)) {
            return $result;
        }
        return new \WP_REST_Response(\true);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {post} /real-physical-media/v1/queue/item/potential Reinitialize and add all potential files from this blog to the queue
     * @apiHeader {string} X-WP-Nonce
     * @apiName AddPotentialItems
     * @apiGroup Queue
     * @apiVersion 1.0.0
     * @apiPermission manage_options
     */
    public function routeQueueItemAddPotential($request) {
        if (($permit = \MatthiasWeb\RealMediaLibrary\rest\Service::permit('manage_options')) !== null) {
            return $permit;
        }
        if (($demo = \DevOwl\RealPhysicalMedia\rest\Service::demoNotAllowed()) !== null) {
            return $demo;
        }
        \DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->addPotentialFiles();
        return new \WP_REST_Response(\true);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {post} /real-physical-media/v1/queue/automatic Activate or deactivate the automatic queueing
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {boolean} state The new state
     * @apiName SetAutomaticQueueing
     * @apiGroup Queue
     * @apiVersion 1.0.0
     * @apiPermission manage_options
     */
    public function routeQueueAutomatic($request) {
        if (($permit = \MatthiasWeb\RealMediaLibrary\rest\Service::permit('manage_options')) !== null) {
            return $permit;
        }
        $queue = \DevOwl\RealPhysicalMedia\queue\Queue::getInstance();
        // Add item to queue
        $result = $queue->isAutomaticQueueing($request->get_param('state'));
        if (is_wp_error($result)) {
            return $result;
        }
        return new \WP_REST_Response($result);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {delete} /real-physical-media/v1/queue/automatic-hint/dismiss Dismiss the hint to automatic queueing
     * @apiName DismissAutomaticHint
     * @apiGroup Queue
     * @apiVersion 1.1.0
     * @apiPermission manage_options
     */
    public function routeQueueAutomaticHintDismiss() {
        if (($permit = \MatthiasWeb\RealMediaLibrary\rest\Service::permit('manage_options')) !== null) {
            return $permit;
        }
        \DevOwl\RealPhysicalMedia\view\AdminBar::getInstance()->isFirstTimeMoveHintDismissed(\true);
        return new \WP_REST_Response(\true);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {delete} /real-physical-media/v1/queue/notice/dismiss Dismiss the notice to queue admin bar
     * @apiName DismissQueueNotice
     * @apiGroup Queue
     * @apiVersion 1.1.0
     * @apiPermission manage_options
     */
    public function routeQueueNoticeDismiss() {
        if (($permit = \MatthiasWeb\RealMediaLibrary\rest\Service::permit('manage_options')) !== null) {
            return $permit;
        }
        \DevOwl\RealPhysicalMedia\view\AdminBar::getInstance()->isFirstTimeQueueNoticeDismissed(\true);
        return new \WP_REST_Response(\true);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {post} /real-physical-media/v1/lockfiles/reflect Reflect the complete virtual structure to physical folders
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {boolean} withlockfile Create lockfiles if true
     * @apiName ReflectLockfiles
     * @apiGroup Lockfiles
     * @apiVersion 1.0.0
     * @apiPermission manage_options
     */
    public function routeLockfilesReflect($request) {
        if (($permit = \MatthiasWeb\RealMediaLibrary\rest\Service::permit('manage_options')) !== null) {
            return $permit;
        }
        \DevOwl\RealPhysicalMedia\listener\Lockfile::getInstance()->createAll(
            $request->get_param('withlockfile') === 'true'
        );
        return new \WP_REST_Response(\true);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {delete} /real-physical-media/v1/lockfiles Remove all lockfiles
     * @apiHeader {string} X-WP-Nonce
     * @apiName DeleteLockfiles
     * @apiGroup Lockfiles
     * @apiVersion 1.0.0
     * @apiPermission manage_options
     */
    public function routeLockfilesDelete($request) {
        if (($permit = \MatthiasWeb\RealMediaLibrary\rest\Service::permit('manage_options')) !== null) {
            return $permit;
        }
        \DevOwl\RealPhysicalMedia\listener\Lockfile::getInstance()->removeAll();
        return new \WP_REST_Response(\true);
    }
    /**
     * Process the queue.
     *
     * @param WP_REST_Request $request
     */
    private function doQueue($request) {
        $queue = \DevOwl\RealPhysicalMedia\queue\Queue::getInstance();
        // Delete error
        $skipError = $request->get_param('skip');
        if ($skipError === 'retry') {
            $queue->clearException();
        } elseif ($skipError === 'skip') {
            $queue->clearException(\true);
        }
        // Get first entry and process is available
        $result = $queue->process(\DevOwl\RealPhysicalMedia\configuration\Options::getInstance()->getMaxExecTime());
        if (is_wp_error($result)) {
            return $result;
        }
        $response = $queue->prepareResponse($request->get_param('skip'), $request->get_param('top'));
        // Find common URL prefix to reduce payload
        $allDoneUrls = [];
        foreach ($result as $value) {
            $allDoneUrls = \array_merge($allDoneUrls, \array_values($value['u']));
        }
        $prefix = \DevOwl\RealPhysicalMedia\Util::commonPrefix($allDoneUrls);
        $prefln = \strlen($prefix);
        foreach ($result as &$value) {
            foreach ($value['u'] as $size => $url) {
                $value['u'][$size] = \substr($url, $prefln);
            }
        }
        $response['done'] = $result;
        $response['doneUrlPrefix'] = $prefix;
        return $response;
    }
    /**
     * New instance.
     */
    public static function instance() {
        return new \DevOwl\RealPhysicalMedia\rest\Queue();
    }
}
