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
require_once('../../config.php');

$id = required_param('id', PARAM_INT);           // Course ID
$fileid = required_param('file', PARAM_INT);           // File ID
/** @var cm_info $cm */
list ($course, $cm) = get_course_and_cm_from_cmid($id, 'pdfsec');

$pdfentity = \mod_pdfsec\entity\pdfsec::get($cm->instance);


require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/pdfsec:view', $context);

// Retrieve the file from the Files API.
$fs = get_file_storage();
$file = $fs->get_file_by_id($fileid);
if (!$file) {
    return false; // The file does not exist.
}

$settings = $pdfentity->get_settings();
$pdf = new \assignfeedback_editpdf\pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

$pdf->SetCreator($settings->author());
$pdf->SetTitle($pdfentity->get_name());
$pdf->SetSubject($settings->subject());
$pdf->SetKeywords($settings->keywords());
$pdf->SetAuthor($settings->author());

// remove default header/footer
$pdf->setPrintHeader(!$settings->remove_header());
$pdf->setPrintFooter(!$settings->remove_footer());

$language_meta = Array();
$language_meta['a_meta_charset'] = 'UTF-8';
$language_meta['a_meta_dir'] = get_string('thisdirection', 'langconfig');
$language_meta['a_meta_language'] = current_language();
$language_meta['w_page'] = get_string('page');

$pdf->setLanguageArray($language_meta);

$path = \assignfeedback_editpdf\pdf::ensure_pdf_compatible($file);

$pagecount = $pdf->setSourceFile($path);

//add watermark with user personal id on each page
for ($i = 1; $i <= $pagecount; $i++) {
    $tplidx = $pdf->importPage($i, '/MediaBox'); //CropBox
    $s = $pdf->getTemplatesize($tplidx);
    $orientation = 'P';
    if ($s['w'] > $s['h']) {
        $orientation = 'L';
    }
    $pdf->AddPage($orientation, array($s['w'], $s['h']));
    $pdf->useTemplate($tplidx);
    $pdf->setAlpha($settings->watermarkVisibility());
    // Projector settings $pdf->setAlpha(0.04);
    // Invisible settings $pdf->setAlpha(0.01);
    // Visible settings $pdf->setAlpha(0.3);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->StartTransform();
    $pdf->Rotate(rad2deg(atan($s['w'] / $s['h'])), $s['w'] / 2, $s['h'] / 2);
    $pdf->SetFont('freesans', '', 10);
    $pdf->MultiCell(0, 0, $settings->watermark(), 0, 'C', 0, 1, 0, $s['h'] / 2);
    $pdf->Rotate(0);
    $pdf->StopTransform();

    $pdf->setAlpha(1);
}
$options = [
        'secpdf' => [
                'secpdfpermlicense' => true,
        ]
];
if (array_key_exists('secpdf', $options)) {
    $secpdfoptions = $options['secpdf'];
    if ((is_array($secpdfoptions)) && (array_key_exists('secpdfpermlicense', $secpdfoptions))) {
        $s_lic['w'] = 210;
        $s_lic['h'] = 297;

        $pdf->AddPage('P', array($s_lic['w'], $s_lic['h']));
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('freesans', '', 10);
        $pdf->Write(5, $settings->last_page());
    }

    $permissions = Array();
    $user_pass = $settings->password_view();
    $owner_pass = $settings->password_edit();

    if (is_array($secpdfoptions)) {
        if (array_key_exists('secpdfuserpwd', $secpdfoptions)) {
            $user_pass = $secpdfoptions['secpdfuserpwd'];
        }
        if (array_key_exists('secpdfownerpwd', $secpdfoptions)) {
            $owner_pass = $secpdfoptions['secpdfownerpwd'];
        }
        if (!array_key_exists('secpdfpermprint', $secpdfoptions)) {
            $permissions[] = 'print';
        }
        if (!array_key_exists('secpdfpermmodify', $secpdfoptions)) {
            $permissions[] = 'modify';
        }
        if (!array_key_exists('secpdfpermcopy', $secpdfoptions)) {
            $permissions[] = 'copy';
        }
        if (!array_key_exists('secpdfpermextract', $secpdfoptions)) {
            $permissions[] = 'extract';
        }
        if (!array_key_exists('secpdfpermassemble', $secpdfoptions)) {
            $permissions[] = 'assemble';
        }
    }

    // set security permission - mode 3 - 256 AES
    $pdf->SetProtection($permissions, (empty($user_pass) ? '' : $user_pass), (empty($owner_pass) ? null : $owner_pass), $mode = 3,
            null);

}
$pdf->Output();

