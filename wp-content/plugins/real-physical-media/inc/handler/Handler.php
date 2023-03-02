<?php

namespace DevOwl\RealPhysicalMedia\handler;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\queue\Queue;
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
/**
 * Handler management.
 */
class Handler {
    use UtilsProvider;
    private $handlers;
    public static $me;
    /**
     * Set the handler.
     *
     * @param string $handler
     */
    public function set($handler) {
        $get = $this->get();
        foreach ($get as $value) {
            if ($value['id'] === $handler) {
                \DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->clear();
                return update_option(RPM_OPT_PREFIX . '_handler', $handler, \true);
            }
        }
        return \false;
    }
    /**
     * Deactivate the handler.
     */
    public function deactivate() {
        \DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->clear();
        return update_option(RPM_OPT_PREFIX . '_handler', \false);
    }
    /**
     * This action is fired after a plugin is activated. Here we need to check if it
     * is a registered handler got activated and activate it as rename handler if none
     * given.
     *
     * @param string $plugin
     * @param boolean $network_wide
     */
    public function activated_plugin($plugin, $network_wide) {
        if (!$network_wide) {
            $handlers = $this->get();
            foreach ($handlers as $handler) {
                if ($handler['file'] === $plugin) {
                    $this->set($handler['id']);
                    break;
                }
            }
        }
    }
    /**
     * Get the list of handlers with the active handler marked.
     */
    public function get() {
        if (!$this->handlers) {
            /**
             * Add a new rename handler to the available list.
             *
             * @example <caption>The Media File Renamer handler (MediaFileRenamer#metadata)</caption>
             * [
             *   'id' => 'media-file-renamer',
             *   'file' => 'media-file-renamer/media-file-renamer.php',
             *   'name' => 'Media File Renamer',
             *   'author' => 'Jordy Meow',
             *   'isActivated' => $free['active'],
             *   'error' => $free['error'],
             *   'origin' => 'wordpress.org, ' . __('Free'),
             *   'installUrl' => admin_url('plugin-install.php') . '?s=Media+File+Renamer&tab=search&type=term'
             *   'instance' => $this
             * ]
             * @param {array[]} $handler Current available handler
             * @hook RPM/Handlers
             * @return {array[]}
             */
            $handlers = apply_filters('RPM/Handlers', [
                (new \DevOwl\RealPhysicalMedia\handler\MediaFileRenamer())->metadata()
            ]);
            $current = $this->getCurrentId();
            foreach ($handlers as $key => $handler) {
                $id = $handler['id'];
                $isHandler = $current === $id;
                if ($isHandler && (!$handler['isActivated'] || !empty($handler['error']))) {
                    $isHandler = \false;
                    $this->deactivate();
                }
                // Detect developer notices
                if (
                    ($isHandler && !isset($handler['instance'])) ||
                    !\is_object($handler['instance']) ||
                    !$handler['instance'] instanceof \DevOwl\RealPhysicalMedia\handler\AbstractHandler
                ) {
                    $handler['devNotice'] =
                        'The handler does not have an instance of an AbstractHandler implementation.';
                    $isHandler = \false;
                    $this->deactivate();
                }
                $handlers[$key]['isHandler'] = $isHandler;
                $handlers[$key]['activatePluginUrl'] = current_user_can('install_plugins')
                    ? add_query_arg(
                        '_wpnonce',
                        wp_create_nonce('activate-plugin_' . $handler['file']),
                        admin_url('plugins.php?action=activate&plugin=' . \urlencode($handler['file']))
                    )
                    : '';
            }
            $this->handlers = $handlers;
        }
        return $this->handlers;
    }
    /**
     * Get current handler instance.
     *
     * @return AbstractHandler
     */
    public function getCurrentInstance() {
        return $this->getCurrent('instance');
    }
    /**
     * Get a property from the currently used handler.
     *
     * @param string $param
     */
    public function getCurrent($param = '') {
        $handlers = $this->get();
        foreach ($handlers as $value) {
            if ($value['isHandler'] === \true) {
                if (empty($param)) {
                    return $value;
                } elseif (isset($value[$param])) {
                    return $value[$param];
                } else {
                    return null;
                }
            }
        }
        return null;
    }
    /**
     * Get the current working handler.
     *
     * @return string|false
     */
    private function getCurrentId() {
        $value = get_option(RPM_OPT_PREFIX . '_handler');
        return empty($value) ? \false : $value;
    }
    /**
     * Prepare response for JSON output.
     */
    public function prepareResponse() {
        return ['handlers' => $this->get()];
    }
    /**
     * Get singleton instance.
     *
     * @return Handler
     */
    public static function getInstance() {
        return self::$me === null ? (self::$me = new \DevOwl\RealPhysicalMedia\handler\Handler()) : self::$me;
    }
}
