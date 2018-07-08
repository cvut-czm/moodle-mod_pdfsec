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

defined('MOODLE_INTERNAL') || die();

class mod_pdfsec_mod_form extends \mod_pdfsec\form\template_form {

    protected function definition() {

        $mform = $this->_form;

        $mform->addElement('filepicker', 'input_pdf', get_string('input_pdf', 'mod_pdfsec'), null,
                ['accepted_types' => ['.pdf']]);
        $mform->addElement('selectyesno', 'use_template', get_string('use_template', 'mod_pdfsec'));
        $mform->addElement('selectgroups', 'templates', get_string('templates', 'mod_pdfsec'), [
                'public' => ['no' => get_string('no')]
        ]);
        $this->define_shared_fields();
        foreach (['misc', 'permission', 'watermark', 'first_page', 'last_page'] as $el) {
            $mform->disabledIf($el . '_title', 'use_template', 'eq', '0');
        }
        $mform->hideIf('templates', 'use_template', 'eq', '0');

        $this->standard_coursemodule_elements();

        $this->add_action_buttons();

    }
}