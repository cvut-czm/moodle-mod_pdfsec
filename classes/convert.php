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

use mod_pdfsec\entity\pdfsec_settings;

defined('MOODLE_INTERNAL') || die();

class convert {

    private static $convert_table = [
            'ä' => 'a', 'Ä' => 'A', 'á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'ã' => 'a', 'Ã' => 'A', 'â' => 'a', 'Â' => 'A',
            'č' => 'c', 'Č' => 'C', 'ć' => 'c', 'Ć' => 'C',
            'ď' => 'd', 'Ď' => 'D',
            'ě' => 'e', 'Ě' => 'E', 'é' => 'e', 'É' => 'E', 'ë' => 'e', 'Ë' => 'E', 'è' => 'e', 'È' => 'E', 'ê' => 'e', 'Ê' => 'E',
            'í' => 'i', 'Í' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ľ' => 'l',
            'Ľ' => 'L', 'ĺ' => 'l', 'Ĺ' => 'L',
            'ń' => 'n', 'Ń' => 'N', 'ň' => 'n', 'Ň' => 'N', 'ñ' => 'n', 'Ñ' => 'N',
            'ó' => 'o', 'Ó' => 'O', 'ö' => 'o', 'Ö' => 'O', 'ô' => 'o', 'Ô' => 'O', 'ò' => 'o', 'Ò' => 'O', 'õ' => 'o', 'Õ' => 'O',
            'ő' => 'o', 'Ő' => 'O',
            'ř' => 'r', 'Ř' => 'R', 'ŕ' => 'r', 'Ŕ' => 'R',
            'š' => 's', 'Š' => 'S', 'ś' => 's', 'Ś' => 'S', 'ť' => 't',
            'Ť' => 'T',
            'ú' => 'u', 'Ú' => 'U', 'ů' => 'u', 'Ů' => 'U', 'ü' => 'u', 'Ü' => 'U', 'ù' => 'u', 'Ù' => 'U', 'ũ' => 'u', 'Ũ' => 'U',
            'û' => 'u', 'Û' => 'U',
            'ý' => 'y', 'Ý' => 'Y',
            'ž' => 'z', 'Ž' => 'Z', 'ź' => 'z', 'Ź' => 'Z'];

    public static function convert_to_safe_string(string $string, $replace_space = true) {
        $table = self::$convert_table;
        if ($replace_space) {
            $table[' '] = '_';
        }
        strtr($string, $table);
    }

    protected function __construct() {
    }

    public static function form_to_settings($formdata) : pdfsec_settings {
        return pdfsec_settings::get_default();
    }

    public static function convert($file, pdfsec_settings $settings) {
        $oldcontent = $file->get_content();
        $pdf = new \FPDI();
        $pdf->setSourceFile();

    }
}