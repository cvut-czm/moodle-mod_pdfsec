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

namespace mod_pdfsec\entity;

use local_cool\entity\database_entity;

defined('MOODLE_INTERNAL') || die();

class pdfsec_template extends database_entity {

    /**
     * Visibility of the template.
     *
     * 0 - File template
     * 1 - Unlisted template
     * 2 - Public template
     *
     * @var int $visibility
     */
    protected $visibility;
    protected $user;
    protected $basedon;
    protected $settings;

    protected function before_delete() {
        $childs = self::get_all(['based_on' => $this->id]);
        if ($this->basedon != null) {
            $basedon = $this->get_basedon()->get_settings();
        } else {
            $basedon = new pdfsec_settings();
        }
        foreach ($childs as $child) {
            /** @var pdfsec_settings $settings */
            $settings = $child->get_settings();
            // We take this settings (that will be deleted) and combine it with child settings.
            $settings->combine_with($basedon, false);
            // We need to insert settings back (json <-> object).
            $child->set_settings($settings);
            // Switching parent template.
            $child->basedon = $this->basedon;
            $child->save();
        }
    }

    public function get_basedon(): pdfsec_template {
        return self::get(['id' => $this->basedon]);
    }

    public function get_settings(bool $completetree = false): pdfsec_settings {
        $settings = pdfsec_settings::from_json($this->settings);
        if (!$completetree) {
            return $settings;
        }
        $settings->set_parent($this->get_basedon()->get_settings(true));
        return $settings;
    }

    public function set_settings(pdfsec_settings $settings) {
        $this->settings = $settings->to_json();
    }

    public function before_update() {
        // TODO: We need to be protected from cycle referencing in basedon.
    }
}