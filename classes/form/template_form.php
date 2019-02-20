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


    protected function define_shared_fields() {
        $form = $this->_form;

        $form->addElement('header', 'permission_general', get_string('title_general', 'mod_pdfsec'));
        $form->addElement('selectyesno', 'general_sel', get_string('use_template', 'mod_pdfsec'));
        $form->setDefault('general_sel',1);
        foreach (['subject', 'keywords', 'author', 'language'] as $element) {
            $form->addElement('text', $element, get_string($element, 'mod_pdfsec'));
            $form->setType($element, PARAM_ALPHANUMEXT);
            $form->hideIf($element,'general_sel','neq',0);

            $form->addElement('text', $element.'_global', get_string($element, 'mod_pdfsec'));
            $form->setType($element.'_global', PARAM_ALPHANUMEXT);
            $form->hideIf($element.'_global','general_sel','neq',1);
            $form->disabledIf($element.'_global','general_sel','neq','3');
        }
        $this->define_pdf_datasource('first_page');
        $this->define_pdf_datasource('last_page');
        $this->define_pdf_datasource('watermark');
        $form->addElement('select', 'watermarkvisible', get_string('watermarkvisibletypes', 'mod_pdfsec'), [
                1 => get_string('watermarkvisible', 'mod_pdfsec'),
                0 => get_string('watermarkinvisible', 'mod_pdfsec')
        ]);
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

        $form->addElement('header', 'permission_title', get_string('title_permission', 'mod_pdfsec'));/*
        $form->addElement('selectyesno', 'perm_sel', get_string('use_template', 'mod_pdfsec'));
        $form->setDefault('perm_sel',1);
        foreach([0=>'',1=>'_global'] as $key=>$value)
        {
            $form->addElement('selectyesno', 'perm_print'.$value, get_string('perm_print', 'mod_pdfsec'));
            $form->addHelpButton('perm_print'.$value,'perm_print','mod_pdfsec');
            $form->hideIf('perm_print'.$value,'perm_sel','neq',$key);

            $form->addElement('selectyesno', 'perm_modify'.$value, get_string('perm_modify', 'mod_pdfsec'));
            $form->addHelpButton('perm_modify'.$value,'perm_modify','mod_pdfsec');
            $form->hideIf('perm_modify'.$value,'perm_sel','neq',$key);

            $form->addElement('selectyesno', 'perm_extract'.$value, get_string('perm_extract', 'mod_pdfsec'));
            $form->addHelpButton('perm_extract'.$value,'perm_extract','mod_pdfsec');
            $form->hideIf('perm_extract'.$value,'perm_sel','neq',$key);

            $form->addElement('selectyesno', 'perm_copy'.$value, get_string('perm_copy', 'mod_pdfsec'));
            $form->addHelpButton('perm_copy','perm_copy','mod_pdfsec');
            $form->hideIf('perm_copy'.$value,'perm_sel','neq',$key);

            $form->addElement('selectyesno', 'perm_assemble'.$value, get_string('perm_assemble', 'mod_pdfsec'));
            $form->addHelpButton('perm_assemble'.$value,'perm_assemble','mod_pdfsec');
            $form->hideIf('perm_assemble'.$value,'perm_sel','neq',$key);
        }
        $form->disabledIf('perm_print'.'_global','perm_sel','neq','3');
        $form->disabledIf('perm_modify'.'_global','perm_sel','neq','3');
        $form->disabledIf('perm_extract'.'_global','perm_sel','neq','3');
        $form->disabledIf('perm_copy'.'_global','perm_sel','neq','3');
        $form->disabledIf('perm_assemble'.'_global','perm_sel','neq','3');
*/
        $form->addElement('passwordunmask', 'perm_view_password', get_string('perm_view_password', 'mod_pdfsec'));
  //      $form->addElement('passwordunmask', 'perm_edit_password', get_string('perm_edit_password', 'mod_pdfsec'));
    }


    private function define_pdf_datasource(string $identifier) {
        $form = $this->_form;
        $selector = 'sel';

        $form->addElement('header', $identifier . '_title', get_string('title_' . $identifier, 'mod_pdfsec'));
        $form->addElement('selectyesno', $identifier . '_' . $selector, get_string('use_template', 'mod_pdfsec'));
        $form->setDefault($identifier . '_' . $selector,1);

        $form->addElement('textarea', $identifier . '_own', get_string('datasource_definition', 'mod_pdfsec'),'wrap="virtual" rows="20" cols="50"');
        $form->setType($identifier . '_own', PARAM_RAW);

        $form->addElement('textarea', $identifier . '_global', get_string('datasource_definition', 'mod_pdfsec'),'wrap="virtual" rows="20" cols="50"');
        $form->disabledIf($identifier.'_global',$identifier.'_'.$selector,'neq','3');
        $form->setType($identifier . '_global', PARAM_RAW);

        $form->hideIf($identifier.'_global',$identifier.'_'.$selector,'neq','1');
        $form->hideIf($identifier.'_own',$identifier.'_'.$selector,'neq','0');

    }
}