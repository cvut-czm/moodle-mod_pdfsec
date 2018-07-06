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

class pdfsec extends database_entity {
    const TABLENAME = 'pdfsec';
    // region Variables.
    /** @var int $course */
    protected $course;
    /** @var string $name */
    protected $name;
    /** @var $string $intro */
    protected $intro;
    /** @var int $introformat */
    protected $introformat;

    protected $template;
    // endregion.

    // region File system.

    public function has_file(): bool {
        $fs = get_file_storage();
        $files = $fs->get_area_files(\context_course::instance($this->course)->instanceid, 'mod_pdfsec', 'content');
        return count($files) > 0;
    }

    // endregion.
}