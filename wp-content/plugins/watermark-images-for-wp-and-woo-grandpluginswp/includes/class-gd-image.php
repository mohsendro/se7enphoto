<?php
namespace GPLSCorePro\GPLS_PLUGIN_WMFW;

use GPLSCorePro\GPLS_PLUGIN_WMFW\Watermark_Base;
use GPLSCorePro\GPLS_PLUGIN_WMFW\Image_Watermark;

/**
 * Image Operation Using GD Library.
 */
class GD_Image extends Watermark_Base {

	/**
	 * Image Details Array.
	 *
	 * @var Array
	 */
	protected $img = array();

	/**
	 * Constructor.
	 *
	 * @param array $img Image Details Array.
	 */
	public function __construct( $img ) {
		$this->img = $img;
	}

	/**
	 * Load needed image libs.
	 *
	 * @return void
	 */
	private static function load_wp_img_classes() {
		require_once \ABSPATH . \WPINC . '/class-wp-image-editor.php';
		require_once \ABSPATH . \WPINC . '/class-wp-image-editor-imagick.php';
	}

	/**
	 * Add Image Watermark to Image.
	 *
	 * @param \GdImage $img_resource  GD Resource.
	 * @param array    $watermark Watermark Details Array.
	 * @param \GdImage $watermark_resource Watermark Resource Object.
	 *
	 * @return void
	 */
	public function add_image_watermark( &$img_resource, $watermark, $watermark_resource ) {
		// 1) Create blank Image.
		$blank_image = imagecreatetruecolor( $this->img['width'], $this->img['height'] );
		imagecopy( $blank_image, $img_resource, 0, 0, 0, 0, $this->img['width'], $this->img['height'] );

		// 2) Setup the rotation degree.
		$degree = (float) round( $watermark['styles']['degree'] );
		$degree = ( $degree < 0 ? ( 360 + $degree ) : $degree );

		// 3) Apply black Background
		$col_black         = imagecolorallocatealpha( $watermark_resource, 0, 0, 0, 127 );
		$rotated_watermark = imagerotate( $watermark_resource, - $degree, $col_black );

		imagealphablending( $rotated_watermark, false );
		imagesavealpha( $rotated_watermark, true );
		imagefilter( $rotated_watermark, IMG_FILTER_COLORIZE, 0, 0, 0, absint( 127 * ( 1 - $watermark['styles']['opacity'] ) ) );

		// 4) Setup the position.
		$position             = $this->calculate_watermark_position( $watermark, $this->img );
		$watermark['absLeft'] = $position['left'];
		$watermark['absTop']  = $position['top'];

		if ( ! empty( $watermark['centerOffset'] ) && ( 'true' === $watermark['centerOffset'] || 'yes' === $watermark['centerOffset'] ) ) {
			$watermark = $this->top_left_after_rotation_around_center( $watermark, $degree );
		}

		// 5) Setup the top left pivot based on the rotation degree.
		$watermark = $this->image_watermark_position_from_rotation( $watermark, $degree );

		// 6) Create Blank image and copy both frame and watermark onto it.
		$this->draw_watermark_on_image( $blank_image, $rotated_watermark, $watermark, $watermark['absLeft'], $watermark['absTop'], 'image' );

		// 7) Repeat Watermark.
		$this->repeat_watermark( $blank_image, $rotated_watermark, $watermark, $watermark['absLeft'], $watermark['absTop'], 'image' );

		// 8) Clear resources.
		imagedestroy( $img_resource );
		imagedestroy( $rotated_watermark );

		$img_resource = $blank_image;
	}

	/**
	 * Add Text Watermark to Image.
	 *
	 * @param \GdImage $img_resource GD Resource.
	 * @param array    $watermark Watermark Info Array.
	 * @return void
	 */
	public function add_text_watermark( &$img_resource, $watermark ) {
		// 1) Create blank image.
		$blank_image = imagecreatetruecolor( $this->img['width'], $this->img['height'] );
		imagecopy( $blank_image, $img_resource, 0, 0, 0, 0, $this->img['width'], $this->img['height'] );

		// 2) Set the Text Color.
		$color = $watermark['styles']['font']['color'];
		$color = $this->hex_color_2_allocate( $color );

		if ( ! $color ) {
			$color = imagecolorallocate( $img_resource, 0, 0, 0 );
		} else {
			$color = imagecolorallocate( $blank_image, $color['red'], $color['green'], $color['blue'] );
		}

		// 3) Write the watermark.
		$blank_text_bg = imagecreatetruecolor( $this->img['width'], $this->img['height'] );
		imagesavealpha( $blank_text_bg, true );

		// 4) Make the text watermark background transparent.
		$blank_text_bg_color = imagecolorallocatealpha( $blank_text_bg, 255, 255, 255, 127 );
		imagefill( $blank_text_bg, 0, 0, $blank_text_bg_color );
		imagealphablending( $blank_text_bg, false );
		imagesavealpha( $blank_text_bg, true );

		$degree            = (int) ( $watermark['styles']['degree'] );
		$degree            = ( $degree < 0 ? ( 360 + $degree ) : $degree );
		$degree_in_radians = round( $degree * M_PI / 180, 2 );

		// 5) Setup the position.
		$position             = $this->calculate_watermark_position( $watermark, $this->img );
		$watermark['absLeft'] = $position['left'];
		$watermark['absTop']  = $position['top'];

		if ( ! empty( $watermark['centerOffset'] ) && ( 'true' === $watermark['centerOffset'] || 'yes' === $watermark['centerOffset'] ) ) {
			$watermark = $this->top_left_after_rotation_around_center( $watermark, $degree );
		}

		$watermark['botLeft'] = round( $watermark['absLeft'] ) - round( ( $watermark['height'] ) * sin( $degree_in_radians ) );
		$watermark['botTop']  = round( $watermark['absTop'] ) + round( ( $watermark['height'] ) * cos( $degree_in_radians ) );

		list( $h_spacing, $v_spacing ) = $this->text_watermark_position_from_rotation( $watermark, $degree );

		$font_baseline_left = intval( $watermark['botLeft'] ) + $h_spacing + 5;
		$font_baseline_top  = intval( $watermark['botTop'] ) - $v_spacing;

		$watermark['styles']['degree'] = $degree;
		$watermark['color']            = $color;

		// 6) Copy the watermark text on the image.
		$this->draw_watermark_on_image( $blank_image, $blank_text_bg, $watermark, $font_baseline_left, $font_baseline_top, 'text' );

		// 7) Repeat Watermark.
		$this->repeat_watermark( $blank_image, $blank_text_bg, $watermark, $font_baseline_left, $font_baseline_top, 'text' );

		// 8) Clear Resources.
		imagedestroy( $img_resource );
		imagedestroy( $blank_text_bg );

		$img_resource = $blank_image;
	}

	/**
	 * Copy the watermark on the image.
	 *
	 * @param object $img_resource        Image Resource.
	 * @param object $watermark_resource  Watermark Resource
	 * @param array  $watermark           Watermark Details Array.
	 * @param int    $left                Left coordinate.
	 * @param int    $top                 Top Coordinate
	 * @param string $type                Watermark Type [ image | text ].
	 * @return void
	 */
	protected function draw_watermark_on_image( &$img_resource, &$watermark_resource, $watermark, $left, $top, $type = 'image' ) {
		// 1) Get Image Resource from the frame string.
		if ( 'image' === $type ) {
			imagecopy( $img_resource, $watermark_resource, $left, $top, 0, 0, imagesx( $watermark_resource ), imagesy( $watermark_resource ) );
		} elseif ( 'text' === $type ) {
			// Write the text watermark.
			imagettftext( $watermark_resource, round( (float) $watermark['styles']['font']['fontSize'] * 0.75 ), - $watermark['styles']['degree'], $left, $top, $watermark['color'], Image_Watermark::get_font_path( $watermark['styles']['font']['fontFamily'] ), $watermark['text'] );
			// Add the watermark opacity.
			imagefilter( $watermark_resource, IMG_FILTER_COLORIZE, 0, 0, 0, round( 127 - $watermark['styles']['opacity'] * 127 ) );
			imagecopy( $img_resource, $watermark_resource, 0, 0, 0, 0, $this->img['width'], $this->img['height'] );
		}
	}

	/**
	 * Get Image Resource
	 *
	 * @param string  $img_path
	 * @param boolean $include_type
	 * @return object|\WP_Error
	 */
	public static function get_image_resource( $img_path, $include_type = false ) {
		list( $width, $height, $type ) = getimagesize( $img_path );
		unset( $width, $height );

			// GIF.
		if ( 1 === $type ) {
			return new \WP_Error(
				self::$plugin_info['name'] . '-unsupported-image-type',
				esc_html__( 'GIF is not supported', 'watermark-images-for-wp-and-woo-grandpluginswp' )
			);
			// JPEG.
		} elseif ( 2 === $type ) {
			$gd = @imagecreatefromjpeg( $img_path );
			if ( ! $gd ) {
				return new \WP_Error(
					self::$plugin_info['name'] . '-failed-image-resource',
					sprintf(
						esc_html__( 'File is not valid %s', 'watermark-images-for-wp-and-woo-grandpluginswp' ),
						$img_path
					)
				);
			}
			// PNG.
		} elseif ( 3 === $type ) {
			$gd = @imagecreatefrompng( $img_path );
			if ( ! $gd ) {
				return new \WP_Error(
					self::$plugin_info['name'] . '-failed-image-resource',
					sprintf(
						esc_html__( 'File is not valid %s', 'watermark-images-for-wp-and-woo-grandpluginswp' ),
						$img_path
					)
				);
			}
			// WBMP.
		} elseif ( 15 === $type ) {
			$gd = @imagecreatefromwbmp( $img_path );
			if ( ! $gd ) {
				return new \WP_Error(
					self::$plugin_info['name'] . '-failed-image-resource',
					sprintf(
						esc_html__( 'File is not valid %s', 'watermark-images-for-wp-and-woo-grandpluginswp' ),
						$img_path
					)
				);
			}
		} else {
			return new \WP_Error(
				self::$plugin_info['name'] . '-unsupported-image-type',
				esc_html__( 'File is not supported', 'watermark-images-for-wp-and-woo-grandpluginswp' )
			);
		}

		if ( $include_type ) {
			return array(
				'resource' => $gd,
				'type'     => self::$img_types[ $type ],
			);
		} else {
			return $gd;
		}
	}

	/**
	 * Convert Image Resource to string.
	 *
	 * @param \GdImage $img_resource  Image Resource.
	 * @param string   $img_type  Image Type.
	 * @return string|\WP_Error
	 */
	public static function resource_to_string( $img_resource, $img_type ) {
		$img_func_name = 'image' . $img_type;
		ob_start();
		$img_func_name( $img_resource );
		return ob_get_clean();
	}

	/**
	 * Clear Resource.
	 *
	 * @param \GdImage $img_resource Image Resource.
	 * @return boolean
	 */
	public static function clear_resource( $img_resource ) {
		return imagedestroy( $img_resource );
	}

	/**
	 * Resize Image.
	 */
	public static function resize( &$img_resource, $img_path, $dst_w, $dst_h ) {
		$resized = wp_imagecreatetruecolor( $dst_w, $dst_h );
		$size    = getimagesize( $img_path );
		imagecopyresampled( $resized, $img_resource, 0, 0, 0, 0, $dst_w, $dst_h, $size[0], $size[1] );
		imagedestroy( $img_resource );
		$img_resource = $resized;
		return true;
	}

}
