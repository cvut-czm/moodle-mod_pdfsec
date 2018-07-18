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

namespace mod_pdfsec\form;
defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/course/moodleform_mod.php');

use mod_pdfsec\entity\pdfsec_settings;
use moodleform_mod;

defined('MOODLE_INTERNAL') || die();

class template_form extends moodleform_mod {

    /**
     * Form definition. Abstract method - always override!
     */
    protected function definition() {
        // TODO: Implement definition() method.
    }

    protected function get_settings(): pdfsec_settings {
        if ($this->_customdata == null) {
            $this->_customdata = pdfsec_settings::get_default();
        }
        return $this->_customdata;
    }

    protected function define_shared_fields() {
        $form = $this->_form;
        $settings = $this->get_settings();

        $form->addElement('header', 'permission_general', get_string('title_general', 'mod_pdfsec'));
        foreach (['subject', 'keywords', 'author', 'language'] as $element) {
            $form->addElement('text', $element, get_string($element, 'mod_pdfsec'));
            $form->setType($element, PARAM_ALPHANUMEXT);
        }
        $this->define_pdf_datasource('first_page');
        $this->define_pdf_datasource('last_page');
        $this->define_pdf_datasource('watermark');
        $this->define_permission_settings();
        $this->define_misc_settings();

    }

    private function define_misc_settings() {
        $form = $this->_form;
        $form->addElement('header', 'misc_title', get_string('title_misc', 'mod_pdfsec'));
        $form->addElement('selectyesno', 'remove_header', get_string('remove_header', 'mod_pdfsec'));
        $form->addElement('selectyesno', 'remove_footer', get_string('remove_footer', 'mod_pdfsec'));
    }

    private function define_permission_settings() {
        $form = $this->_form;
        $form->addElement('header', 'permission_title', get_string('title_permission', 'mod_pdfsec'));
        $form->addElement('selectyesno', 'perm_print', get_string('perm_print', 'mod_pdfsec'));
        $form->addElement('selectyesno', 'perm_modify', get_string('perm_modify', 'mod_pdfsec'));
        $form->addElement('selectyesno', 'perm_extract', get_string('perm_extract', 'mod_pdfsec'));
        $form->addElement('selectyesno', 'perm_copy', get_string('perm_copy', 'mod_pdfsec'));
        $form->addElement('selectyesno', 'perm_assemble', get_string('perm_assemble', 'mod_pdfsec'));
    }

    private function define_override_checkbox(string $element) {
        $form = $this->_form;
        $settings = $this->get_settings();
        $override = 'override';

        $form->addElement('advcheckbox', $element . '_' . $override, get_string('override', 'mod_pdfsec'));
        $form->setDefault($element . '_' . $override, $settings->has($element));
        $form->disabledIf($element, $element . '_' . $override, 'notchecked');
    }

    private function define_pdf_datasource(string $identifier) {
        $form = $this->_form;
        $idimg = 'img';
        $idtext = 'txt';
        $idcode = 'cdr';
        $selector = 'sel';

        $form->addElement('header', $identifier . '_title', get_string('title_' . $identifier, 'mod_pdfsec'));
        $form->addElement('select', $identifier . '_' . $selector, get_string('datasource', 'mod_pdfsec'), [
                $idimg => get_string('datasource_' . $idimg, 'mod_pdfsec'),
                $idtext => get_string('datasource_' . $idtext, 'mod_pdfsec'),
                $idcode => get_string('datasource_' . $idcode, 'mod_pdfsec')
        ]);

        $form->addElement('filepicker', $identifier . '_' . $idimg, get_string('datasource_' . $idimg, 'mod_pdfsec'), null,
                ['accepted_types' => ['.png', '.jpg', '.jpeg']]);
        $form->addElement('textarea', $identifier . '_' . $idtext, get_string('datasource_' . $idtext, 'mod_pdfsec'));
        $form->setType($identifier . '_' . $idtext, PARAM_RAW);

        $form->addElement('textarea', $identifier . '_' . $idcode, get_string('datasource_' . $idcode, 'mod_pdfsec'));
        $form->setType($identifier . '_' . $idcode, PARAM_RAW);

        $form->hideIf($identifier . '_' . $idimg, $identifier . '_' . $selector, 'neq', $idimg);
        $form->hideIf($identifier . '_' . $idtext, $identifier . '_' . $selector, 'neq', $idtext);
        $form->hideIf($identifier . '_' . $idcode, $identifier . '_' . $selector, 'neq', $idcode);

    }
}