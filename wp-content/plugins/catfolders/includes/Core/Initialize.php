<?php

namespace CatFolders\Core;

class Initialize {
	public function __construct() {
		new \CatFolders\Install();
		new \CatFolders\Plugin();
		new \CatFolders\I18n();

		new \CatFolders\Backend\SettingsPage();
		\CatFolders\Backend\Enqueue::instance();

		new \CatFolders\Internals\WPMedia();
		new \CatFolders\Internals\Users\FolderUser();

		new \CatFolders\Integrations\Init();
		new \CatFolders\Rest\Init();
	}
}
