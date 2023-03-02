<?php

namespace DevOwl\RealPhysicalMedia\handler;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\configuration\Options;
use DevOwl\RealPhysicalMedia\listener\Lockfile;
use DevOwl\RealPhysicalMedia\queue\Row;
use DevOwl\RealPhysicalMedia\Util;
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
/**
 * Abstract Handler.
 */
abstract class AbstractHandler {
    use UtilsProvider;
    /**
     * Process a given row.
     *
     * @param Row $row
     * @throws
     */
    abstract public function process($row);
    /**
     * Get the steps needed for processing.
     *
     * @return int
     */
    abstract public function getProcessTotal();
    /**
     * Post process a given row after done.
     *
     * @param Row $row
     */
    public function finish($row) {
        $path = $row->sourceAbsPath;
        // Delete the physical folder recursively
        if (\DevOwl\RealPhysicalMedia\configuration\Options::getInstance()->isCleanupMoveEnabled()) {
            \DevOwl\RealPhysicalMedia\Util::removeEmptyDirs($path);
            if (!empty($row->cleanupPath)) {
                \DevOwl\RealPhysicalMedia\Util::removeEmptyDirs(path_join(ABSPATH, $row->cleanupPath));
            }
        }
        // Delete physical folder when it is marked with the process id
        if (\DevOwl\RealPhysicalMedia\configuration\Options::getInstance()->isCleanupCreateEnabled()) {
            $lockfile = \DevOwl\RealPhysicalMedia\listener\Lockfile::getInstance();
            if ($lockfile->isLocked($path)) {
                $ids = $lockfile->getProcessIds($path);
                if (\in_array($row->processId, $ids, \true) && $lockfile->remove($path) === \true) {
                    $lockfile->clear($path);
                }
            }
        }
    }
    /**
     * Allows you to skip a Exception generation while processing the handler because
     * Warnings can also result in Exceptions.
     *
     * @param string $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @param string $errcontext
     */
    public function skipWarning($errno, $errstr, $errfile, $errline, $errcontext) {
        return \false;
    }
    /**
     * Get the metadata for the 'RPM/Handlers' filter.
     *
     * @return array
     */
    abstract public function metadata();
    /**
     * Get the version data of a specific plugin and check the min version.
     *
     * @param string $mfr_file
     * @param string $minVersion
     * @return array with 'active' and 'error' value
     */
    final protected function getVersionData($mfr_file, $minVersion) {
        // Media File Renamer
        $mfr_active = is_plugin_active($mfr_file);
        $mfr_installed = \file_exists(path_join(\constant('WP_PLUGIN_DIR'), $mfr_file));
        $mfr_path = path_join(\constant('WP_PLUGIN_DIR'), $mfr_file);
        $mfr_version = $mfr_active ? get_plugin_data($mfr_path, \true, \false)['Version'] : '0.0.0';
        $mfr_error =
            $mfr_active && \version_compare($mfr_version, $minVersion, '<')
                ? \sprintf(
                    __('You need to install at least version %s. Please update the software!', RPM_TD),
                    $minVersion
                )
                : '';
        return ['installed' => $mfr_installed, 'active' => $mfr_active, 'error' => $mfr_error];
    }
}
