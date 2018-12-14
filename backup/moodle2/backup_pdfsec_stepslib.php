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
 * @package   mod_page
 * @category  backup
 * @copyright 2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Define all the backup steps that will be used by the backup_page_activity_task
 */

/**
 * Define the complete page structure for backup, with file and id annotations
 */
class backup_pdfsec_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {
        $folder = new backup_nested_element('pdfsec', array('id'), array(
                'name', 'intro', 'introformat', 'revision',
                'timemodified', 'settings', 'template'));
        $folder->set_source_table('pdfsec', array('id' => backup::VAR_ACTIVITYID));
        $folder->annotate_files('mod_pdfsec', 'intro', null);
        $folder->annotate_files('mod_pdfsec', 'input_pdf', null);
        return $this->prepare_activity_structure($folder);
    }
}
