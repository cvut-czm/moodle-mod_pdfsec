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

function pdfsec_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_ARCHETYPE:           return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_GROUPS:                  return false;
        case FEATURE_GROUPINGS:               return false;
        case FEATURE_MOD_INTRO:               return false;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_BACKUP_MOODLE2:          return true;
        case FEATURE_SHOW_DESCRIPTION:        return true;
        default: return null;
    }
}
function pdfsec_add_instance($pdfsec, mod_pdfsec_mod_form $mform) {
    $entity=new \mod_pdfsec\entity\pdfsec();
    $entity->set_name($pdfsec->name);
    $entity->set_course($pdfsec->course);
    $entity->set_settings(\mod_pdfsec\entity\pdfsec_settings::from_formdata($mform->get_data()));
    $entity->save();
    pdfsec_set_file($mform->get_data());
    return $entity->get_id();
}

function pdfsec_set_file($data) {
    global $DB;
    $fs = get_file_storage();
    $cmid = $data->coursemodule;
    $draftitemid = $data->input_pdf;

    $context = context_module::instance($cmid);
    if ($draftitemid) {
        $options = array('subdirs' => false, 'embed' => false);
        $fs->delete_area_files($context->id,'mod_pdfsec','input_pdf',0);
        file_save_draft_area_files($draftitemid, $context->id, 'mod_pdfsec', 'input_pdf', 0, $options);
    }
}

function pdfsec_cm_info_dynamic(cm_info $cm) {
    $cm->set_no_view_link();
}

function pdfsec_cm_info_view(cm_info $cm) {
    global $PAGE;
    $c = $cm->customdata;
    $cm->set_content($c);
}
function pdfsec_update_instance($pdfsec, mod_pdfsec_mod_form $mform) {
    $entity=\mod_pdfsec\entity\pdfsec::get($pdfsec->instance);
    $entity->set_name($pdfsec->name);
    $entity->set_course($pdfsec->course);
    $entity->set_settings(\mod_pdfsec\entity\pdfsec_settings::from_formdata($mform->get_data()));
    $entity->save();
    pdfsec_set_file($mform->get_data());
    return true;
}

function pdfsec_delete_instance($pdfsec) {
    \mod_pdfsec\entity\pdfsec::delete($pdfsec);
}

function pdfsec_get_coursemodule_info($cm) {
    global $DB;
    $fs= get_file_storage();
    if (!($folder = $DB->get_record('pdfsec', array('id' => $cm->instance)))) {
        return null;
    }
    $context = context_module::instance($cm->id);
    $cminfo = new cached_cm_info();
    $cminfo->customdata = '';
    foreach($fs->get_area_files($context->id,'mod_pdfsec','input_pdf',0,"itemid, filepath, filename",false) as $file) {
        $cminfo->customdata .= '<a class="" onclick="" href="'.(new moodle_url('/mod/pdfsec/view.php',['id'=>$cm->id,'file'=>$file->get_id()])).'">';
        $cminfo->customdata .= '<img src="/mod/pdfsec/pix/icon.svg" class="iconlarge activityicon" alt=" " role="presentation"/>';
        $cminfo->customdata .= '<span class="instancename">'.$file->get_filename().'<span class="accesshide "> File</span></span></a><br/>';
    }
    return $cminfo;
}