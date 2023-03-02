<?php

namespace DevOwl\RealPhysicalMedia\handler;

\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
/**
 * Handler manager for Media File Renamer.
 */
class MediaFileRenamer extends \DevOwl\RealPhysicalMedia\handler\AbstractHandler {
    const MIN_VERSION = '4.4.0';
    const FILE = 'media-file-renamer/media-file-renamer.php';
    const PRO_MIN_VERSION = '4.4.0';
    const PRO_FILE = 'media-file-renamer-pro/media-file-renamer-pro.php';
    // Documented in AbstractHandler
    public function process($row) {
        if (!$row->exists()) {
            $row->progress();
        }
        if (mfrh_move($row->attachment, $row->destinationPathRelativeUploadDir)) {
            $row->progress();
        }
    }
    // Documented in AbstractHandler
    public function skipWarning($errno, $errstr, $errfile, $errline, $errcontext) {
        // Skip warning when moving a file from '' to 'a/folder' because MFR uses strpos
        $skipErrStr = 'strpos(): Empty needle';
        if ($errstr === $skipErrStr) {
            return \true;
        }
        return \false;
    }
    // Documented in AbstractHandler
    public function getProcessTotal() {
        return 1;
    }
    // Documented in AbstractHandler
    public function metadata() {
        $free = $this->getVersionData(self::FILE, self::MIN_VERSION);
        $pro = $this->getVersionData(self::PRO_FILE, self::PRO_MIN_VERSION);
        if ($pro['active']) {
            return [
                'id' => 'media-file-renamer-pro',
                'file' => self::PRO_FILE,
                'name' => 'Media File Renamer Pro',
                'author' => 'Jordy Meow',
                'isActivated' => \true,
                'isInstalled' => \true,
                'error' => $pro['error'],
                'origin' => 'meowapps.com, ' . __('Pro version'),
                'installUrl' => 'https://meowapps.com/plugin/media-file-renamer/',
                'instance' => $this
            ];
        } else {
            return [
                'id' => 'media-file-renamer',
                'file' => self::FILE,
                'name' => 'Media File Renamer',
                'author' => 'Jordy Meow',
                'isActivated' => $free['active'],
                'isInstalled' => $free['installed'],
                'error' => $free['error'],
                'origin' => 'wordpress.org, ' . __('Free'),
                'installUrl' => admin_url('plugin-install.php') . '?s=Media+File+Renamer&tab=search&type=term',
                'instance' => $this
            ];
        }
    }
}
