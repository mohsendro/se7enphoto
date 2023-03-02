<?php

namespace DevOwl\RealPhysicalMedia\misc;

use Normalizer;
// defined('ABSPATH') or die('No script kiddies please!'); // Avoid direct file request
/**
 * A collection of a char to value map to handle special characters in filenames and
 * folder names. Why? There are great plugins out there on wordpress.org but they are not
 * updated in regular interval.
 *
 * @see https://plugins.trac.wordpress.org/browser/wp-sanitize-file-name-plus/trunk/wp-sanitize-file-name-plus.php
 * @see https://plugins.trac.wordpress.org/browser/filenames-to-latin/trunk/filenames-to-latin.php
 * @see https://plugins.trac.wordpress.org/browser/sanitize-spanish-filenames/trunk/sanitize-spanish-filenames.php
 */
class SpecialCharacters {
    /**
     * Sanitize a given string.
     *
     * @param string $str
     */
    public static function sanitize($str) {
        // Core functionality of WordPress, depending on the current site language
        $result = remove_accents($str);
        // Do the rest with Normalizer
        return \str_replace(
            '?',
            '',
            \iconv(
                'UTF-8',
                'ASCII//TRANSLIT',
                \preg_replace('/[\\x{0300}-\\x{036f}]/u', '', \normalizer_normalize($result, \Normalizer::FORM_D))
            )
        );
    }
}
