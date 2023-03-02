<?php
namespace CatFolders;

defined( 'ABSPATH' ) || exit;
class I18n {
	public function __construct() {
		add_action( 'init', array( __CLASS__, 'load_text_domain' ) );
	}

	public static function load_text_domain() {
		if ( function_exists( 'determine_locale' ) ) {
			$locale = determine_locale();
		} else {
			$locale = is_admin() ? get_user_locale() : get_locale();
		}
		unload_textdomain( 'catfolders' );
		load_textdomain( 'catfolders', CATF_PLUGIN_PATH . '/languages/catfolders-' . $locale . '.mo' );
		load_plugin_textdomain( 'catfolders', false, CATF_PLUGIN_PATH . '/languages/' );
	}

	public static function getTranslation() {
		return array(
			'global' => array(
				'loading'                               => __( 'Loading...', 'catfolders' ),
				'move_successfully'                     => __( 'Moved successfully!', 'catfolders' ),
				'something_went_wrong_please_try_again' => __( 'Something went wrong! Please try again!', 'catfolders' ),
				'all_files'                             => __( 'All Files', 'catfolders' ),
				'uncategorized'                         => __( 'Uncategorized', 'catfolders' ),
				'default'                               => __( 'Default', 'catfolders' ),
				'name_ascending'                        => __( 'Name Ascending', 'catfolders' ),
				'name_descending'                       => __( 'Name Descending', 'catfolders' ),
				'enter_folder_name'                     => __( 'Enter folder name', 'catfolders' ),
				'date_ascending'                        => __( 'Date Ascending', 'catfolders' ),
				'date_descending'                       => __( 'Date Descending', 'catfolders' ),
				'modified_ascending'                    => __( 'Modified Ascending', 'catfolders' ),
				'modified_descending'                   => __( 'Modified Descending', 'catfolders' ),
				'author_ascending'                      => __( 'Author Ascending', 'catfolders' ),
				'author_descending'                     => __( 'Author Descending', 'catfolders' ),
				'title_ascending'                       => __( 'Title Ascending', 'catfolders' ),
				'title_descending'                      => __( 'Title Descending', 'catfolders' ),
				'saving_progress'                       => __( 'Saving progress...', 'catfolders' ),
				'setting_saved'                         => __( 'Setting Saved!', 'catfolders' ),
				'settings'                              => __( 'Settings', 'catfolders' ),
				'previous_folder_selected'              => __( 'Previous folder selected', 'catfolders' ),
				'ascending'                             => __( 'Ascending', 'catfolders' ),
				'descending'                            => __( 'Descending', 'catfolders' ),
				'save_successfully'                     => __( 'Saved successfully!', 'catfolders' ),
				'something_went_wrong'                  => __( 'Something went wrong!', 'catfolders' ),
				'user_restriction'                      => __( 'User Restriction', 'catfolders' ),
				'settings'                              => __( 'Settings', 'catfolders' ),
				'license'                               => __( 'License', 'catfolders' ),
				'import'                                => __( 'Import', 'catfolders' ),
				'successfully'                          => __( 'Successfully!', 'catfolders' ),
				'backup_and_restore'                    => __( 'Backup and Restore', 'catfolders' ),
				'export'                                => __( 'Export', 'catfolders' ),
				'export_now'                            => __( 'Export Now', 'catfolders' ),
				'download_csv'                          => __( 'Download CSV', 'catfolders' ),
				'clear_all_data'                        => __( 'Clear all data', 'catfolders' ),
				'clear_now'                             => __( 'Clear now', 'catfolders' ),
				'import_from_other_plugin'              => __( 'Import From Other Plugins', 'catfolders' ),
				'already_imported'                      => __( 'Already Imported', 'catfolders' ),
				'by'                                    => __( 'by', 'catfolders' ),
				'folders_found_to_import'               => __( 'folders found to import', 'catfolders' ),
				'import_now'                            => __( 'Import now', 'catfolders' ),
				'enter_your_license'                    => __( 'Enter your license', 'catfolders' ),
				'are_you_sure_to_delete'                => __( 'Are you sure to delete?', 'catfolders' ),
				'this_action_will_delete'               => __( 'This action will delete all CatFolders data, CatFolders settings and bring you back to WordPress default media library.', 'catfolders' ),
				'yes'                                   => __( 'Yes', 'catfolders' ),
				'no'                                    => __( 'No', 'catfolders' ),
				'startup_folder'                        => __( 'Startup Folder: ', 'catfolders' ),
				'auto_sort_folder_by'                   => __( 'Auto sort folder by (PRO): ', 'catfolders' ),
				'auto_sort_files_by'                    => __( 'Auto sort files by (PRO): ', 'catfolders' ),
				'sort_folders'                          => __( 'Sort Folders', 'catfolders' ),
				'by_author'                             => __( 'By Author', 'catfolders' ),
				'by_modified'                           => __( 'By Modified', 'catfolders' ),
				'by_date'                               => __( 'By Date', 'catfolders' ),
				'sort_files'                            => __( 'Sort Files', 'catfolders' ),
				'by_name'                               => __( 'By Name', 'catfolders' ),
				'bulk_select'                           => __( 'Bulk Select', 'catfolders' ),
				'uploaded'                              => __( 'Uploaded', 'catfolders' ),
				'all_folders'                           => __( 'All Folders', 'catfolders' ),
				'view_our_document'                     => sprintf( __( 'View our document <a href="%s" target="_blank">here</a>', 'catfolders' ), 'https://wpmediafolders.com/documentation/' ),
				'user_mode_desc'                        => __( 'Users will only be able to access their folders and media.', 'catfolders' ),
				'welcome_mess'                          => sprintf( __( 'Welcome To <a href="%s" target="_blank">CatFolders</a>', 'catfolders' ), 'https://catfolders.com/' ),
				'export_desc'                           => __( 'The current folder structure will be exported.', 'catfolders' ),
				'more_plugins_to_import'                => sprintf( __( 'More Plugins To Import? <a href="%s" target="_blank">Send Us!</a>', 'catfolders' ), 'https://wpmediafolders.com/contact/' ),
				'license_desc'                          => __( 'Your license key provides access to updates and support.', 'catfolders' ),
				'deactivate_key'                        => __( 'Deactivate Key', 'catfolders' ),
				'your_license_is_activated'             => __( 'Your license is activated.', 'catfolders' ),
				'choose_csv_file_to_import'             => __( 'Choose CSV file to import.', 'catfolders' ),
				'noMedia'                               => __( 'No media', 'catfolders' ),
				'move'                                  => __( 'Move', 'catfolders' ),
				'item'                                  => __( 'item', 'catfolders' ),
				'items'                                 => __( 'items', 'catfolders' ),
				'new_folder'                            => __( 'New Folder', 'catfolders' ),
				'rename'                                => __( 'Rename', 'catfolders' ),
				'delete'                                => __( 'Delete', 'catfolders' ),
				'unlock_new_features'                   => __( 'Unlock new features', 'catfolders' ),
				'pro_feature_description'               => __( 'Browse media files of your full folder structure in the CatFolders drag-and-drop interface.', 'catfolders' ),
				'go_catfolders'                         => __( 'Get CatFolders', 'catfolders' ),
				'go_pro'                                => __( 'Go Pro', 'catfolders' ),
				'get_catf_pro'                          => __( 'Get CatFolders Pro', 'catfolders' ),
				'unlock_features'                       => __( 'Unlock full features and premium support', 'catfolders' ),
				'want_subfolders'                       => __( 'Want subfolders?', 'catfolders' ),
				'sort_files_pro'                        => __( 'Sort Files (PRO)', 'catfolders' ),
				'sort_folders_pro'                      => __( 'Sort Folders (PRO)', 'catfolders' ),
				'buy_now'                               => __( 'Buy Now', 'catfolders' ),
				'lite'                                  => __( 'Lite', 'catfolders' ),
				'properties'                            => __( 'Properties', 'catfolders' ),
				'save'                                  => __( 'Save', 'ccatfolderstf' ),
				'cancel'                                => __( 'Cancel', 'catfolders' ),
				'folders'                               => __( 'Folders', 'catfolders' ),
				'verify'                                => __( 'Verify', 'catfolders' ),
				'type'                                  => __( 'Type', 'catfolders' ),
				'total_items'                           => __( 'Total Items', 'catfolders' ),
				'total_children'                        => __( 'Total Children', 'catfolders' ),
				'author'                                => __( 'Author', 'catfolders' ),
				'create_subfolders'                     => __("Create Subfolders", 'catfolders'),
				'advanced_sort'                         => __("Advanced Sort Options", 'catfolders'),
				'file_count'                            => __("File Count", 'catfolders'),
				'page_builders'    						=> __("Page Builders", 'catfolders'),
				'auto_update' 							=> __("Auto Update", 'catfolders'),
				'vip_support' 							=> __("VIP Support", 'catfolders'),
			),
		);
	}
}
