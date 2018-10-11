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
 * Settings for pdf annotation.
 *
 *
 * @package    mod_pdfsec\entity
 * @category   entity
 * @copyright  2018 CVUT CZM
 * @author Jiri Fryc
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_pdfsec\entity;

defined('MOODLE_INTERNAL') || die();

class pdfsec_settings {
    static function endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    public static function wrapper($data) : pdfsec_settings {
        return new pdfsec_settings($data);
    }

    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public static function from_formdata($data) {
        if (is_object($data)) {
            $data = (array) $data;
        }
        $out = [];
        foreach (
                ['general_sel', 'perm_sel', 'first_page_sel', 'last_page_sel', 'watermark_sel', 'subject', 'keywords', 'author',
                        'language', 'perm_print', 'perm_modify', 'perm_extract', 'perm_copy', 'perm_assemble', 'watermarkvisible',
                        'watermarkvisible', 'first_page_own', 'watermark_own', 'last_page_own', 'remove_header', 'remove_footer',
                        'perm_view_password', 'perm_edit_password',
                ] as $v) {
            if (isset($data[$v])) {
                $out[$v] = $data[$v];
            }
        }
        return json_encode($out);
    }

    function is($param) : bool {
        return isset($this->data[$param]) ? ($this->data[$param] == 1) : false;
    }

    function get($param) {
        if (!isset($this->data[$param])) {
            return null;
        }
        $var = $this->data[$param];
        if ($var === '') {
            return null;
        }
        return $var;
    }

    #region general

    public function remove_header() {
        return $this->get('remove_header');
    }
    public function remove_footer() {
        return $this->get('remove_footer');
    }
    public function subject() {
        if ($this->is('general_sel')) {
            return get_config('mod_pdfsec', 'subject');
        }
        return $this->get('subject');
    }

    public function keywords() {
        if ($this->is('general_sel')) {
            return get_config('mod_pdfsec', 'keywords');
        }
        return $this->get('keywords');
    }

    public function language() {
        if ($this->is('general_sel')) {
            return get_config('mod_pdfsec', 'language');
        }
        return $this->get('language');
    }

    public function author() {
        if ($this->is('general_sel')) {
            return get_config('mod_pdfsec', 'author');
        }
        return $this->get('author');
    }
    #endregion

    #region pages
    public function first_page()
    {
        if ($this->is('first_page_sel')) {
            return get_config('mod_pdfsec', 'firstpage');
        }
        return $this->get('first_page_own');
    }
    public function last_page()
    {
        if ($this->is('last_page_sel')) {
            return get_config('mod_pdfsec', 'lastpage');
        }
        return $this->get('last_page_own');
    }
    public function watermark()
    {
        if ($this->is('watermark_sel')) {
            return get_config('mod_pdfsec', 'watermark');
        }
        return $this->get('watermark_own');
    }
    public function watermarkVisibility()
    {
        if ($this->is('watermark_sel')) {
            return get_config('mod_pdfsec', 'watermarkvisible')==1?0.1:0.04;
        }
        return $this->get('watermarkvisible')==1?0.1:0.04;
    }
    #endregion

    #region permissions

    public function password_view() {
        return $this->get('perm_view_password');
    }
    public function password_edit() {
        return $this->get('perm_edit_password');
    }
    public function perm_print() : bool {
        if ($this->is('perm_sel')) {
            return get_config('mod_pdfsec', 'perm_print') == 1;
        }
        return $this->get('perm_print') == 1;
    }

    public function perm_modify() : bool {
        if ($this->is('perm_sel')) {
            return get_config('mod_pdfsec', 'perm_modify') == 1;
        }
        return $this->get('perm_modify') == 1;
    }

    public function perm_extract() : bool {
        if ($this->is('perm_sel')) {
            return get_config('mod_pdfsec', 'perm_extract') == 1;
        }
        return $this->get('perm_extract') == 1;
    }

    public function perm_copy() : bool {
        if ($this->is('perm_sel')) {
            return get_config('mod_pdfsec', 'perm_copy') == 1;
        }
        return $this->get('perm_copy') == 1;
    }

    public function perm_assemble() : bool {
        if ($this->is('perm_sel')) {
            return get_config('mod_pdfsec', 'perm_assemble') == 1;
        }
        return $this->get('perm_assemble') == 1;
    }
    #endregion
}