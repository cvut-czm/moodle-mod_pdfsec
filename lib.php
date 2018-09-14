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

defined('MOODLE_INTERNAL') || die();

function pdfsec_add_instance($pdfsec, mod_pdfsec_mod_form $mform) {
    $entity=new \mod_pdfsec\entity\pdfsec();
    $entity->set_name($pdfsec->name);
    $entity->set_course($pdfsec->course);
    $entity->set_settings(\mod_pdfsec\convert::form_to_settings($pdfsec));
    $entity->save();
    $mform->save_stored_file('input_pdf', $mform->get_context()->get_course_context()->id
            , 'mod_pdfsec', 'v1',$entity->get_id(),'/','input.pdf' );
    return $entity->get_id();
}

function pdfsec_update_instance($pdfsec, mod_pdfsec_mod_form $mform) {
    $entity=\mod_pdfsec\entity\pdfsec::get($pdfsec->id);
    $entity->set_name($pdfsec->displayname);
    $entity->set_course($pdfsec->course);
    $entity->set_settings(\mod_pdfsec\convert::form_to_settings($pdfsec));
    $entity->save();
    $mform->save_stored_file('input_pdf', $mform->get_context()->get_course_context()->id
            , 'mod_pdfsec', 'v1',$entity->get_id(),'/','input.pdf' );
    return $entity->get_id();
}

function pdfsec_delete_instance($pdfsec) {
    \mod_pdfsec\entity\pdfsec::delete($pdfsec);
}