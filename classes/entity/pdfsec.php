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
 * Database entity PDFsec
 *
 * @package    mod_pdfsec
 * @category   entity
 * @copyright  2018 CVUT CZM, Jiri Fryc
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_pdfsec\entity;

use local_cool\entity\database_entity;
use mod_pdfsec\convert;

defined('MOODLE_INTERNAL') || die();

class pdfsec extends database_entity {
    const TABLENAME = 'pdfsec';

    // region Variables.
    /** @var int $course Moodle course*/
    protected $course;
    /** @var string $name Instance name*/
    protected $name;
    /** @var string $settings Settings for pdf generation stored in JSON format.*/
    protected $settings;
    /** @var $string $intro Intro text is not used, but needed by moodle core.*/
    protected $intro = '';
    /** @var int $introformat Intro format is not used, but needed by moodle core.*/
    protected $introformat = 1;


    // endregion.

    public function set_settings($settings) {
        $this->settings = $settings;
    }

    public function set_name(string $name) {
        $this->name = $name;
    }

    public function set_course($id) {
        $this->course = $id;
    }

    public function get_name() {
        return $this->name;
    }

    public function get_settings() : pdfsec_settings
    {
        return pdfsec_settings::wrapper(json_decode($this->settings,true));
    }
}