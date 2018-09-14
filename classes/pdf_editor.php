<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This is a one-line short description of the file.
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    xxxxxx
 * @category   xxxxxx
 * @copyright  2018 CVUT CZM, Jiri Fryc
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_pdfsec;

use assignfeedback_editpdf\pdf;

defined('MOODLE_INTERNAL') || die();


if (!defined('PDF_CUSTOM_FONT_PATH')) {
    /** Defines the site-specific location of fonts. */
    define('PDF_CUSTOM_FONT_PATH', $CFG->dataroot.'/fonts/');
}

if (!defined('PDF_DEFAULT_FONT')) {
    /** Default font to be used. */
    define('PDF_DEFAULT_FONT', 'FreeSerif');
}

/** tell tcpdf it is configured here instead of in its own config file */
define('K_TCPDF_EXTERNAL_CONFIG', 1);

// The configuration constants needed by tcpdf follow

/**
 * Init K_PATH_FONTS and PDF_FONT_NAME_MAIN constant.
 *
 * Unfortunately this hack is necessary because the constants need
 * to be defined before inclusion of the tcpdf.php file.
 */
function tcpdf_init_k_font_path() {
    global $CFG;

    $defaultfonts = $CFG->dirroot.'/lib/tcpdf/fonts/';

    if (!defined('K_PATH_FONTS')) {
        if (is_dir(PDF_CUSTOM_FONT_PATH)) {
            // NOTE:
            //   There used to be an option to have just one file and having it set as default
            //   but that does not make sense any more because add-ons using standard fonts
            //   would fail very badly, also font families consist of multiple php files for
            //   regular, bold, italic, etc.

            // Check for some standard font files if present and if not do not use the custom path.
            $somestandardfiles = array('courier',  'helvetica', 'times', 'symbol', 'zapfdingbats', 'freeserif', 'freesans');
            $missing = false;
            foreach ($somestandardfiles as $file) {
                if (!file_exists(PDF_CUSTOM_FONT_PATH . $file . '.php')) {
                    $missing = true;
                    break;
                }
            }
            if ($missing) {
                define('K_PATH_FONTS', $defaultfonts);
            } else {
                define('K_PATH_FONTS', PDF_CUSTOM_FONT_PATH);
            }
        } else {
            define('K_PATH_FONTS', $defaultfonts);
        }
    }

    if (!defined('PDF_FONT_NAME_MAIN')) {
        define('PDF_FONT_NAME_MAIN', strtolower(PDF_DEFAULT_FONT));
    }
}
tcpdf_init_k_font_path();

/** tcpdf installation path */
define('K_PATH_MAIN', $CFG->dirroot.'/lib/tcpdf/');

/** URL path to tcpdf installation folder */
define('K_PATH_URL', $CFG->wwwroot . '/lib/tcpdf/');

/** cache directory for temporary files (full path) */
define('K_PATH_CACHE', $CFG->cachedir . '/tcpdf/');

/** images directory */
define('K_PATH_IMAGES', $CFG->dirroot . '/');

/** blank image */
define('K_BLANK_IMAGE', K_PATH_IMAGES . 'pix/spacer.gif');

/** height of cell repect font height */
define('K_CELL_HEIGHT_RATIO', 1.25);

/** reduction factor for small font */
define('K_SMALL_RATIO', 2/3);

/** Throw exceptions from errors so they can be caught and recovered from. */
define('K_TCPDF_THROW_EXCEPTION_ERROR', true);

require_once($CFG->libdir.'/tcpdf/tcpdf.php');

class pdf_editor extends \TCPDF {

}
$k=new pdf();
$k->setSourceFile();
$p=new pdf_editor();
$p->setSourceFile();