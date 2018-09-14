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
/** @var cm_info $cm */
list ($course, $cm) = get_course_and_cm_from_cmid($id, 'pdfsec');

$pdf=\mod_pdfsec\entity\pdfsec::get($cm->instance);


// Retrieve the file from the Files API.
$fs = get_file_storage();
$file = $fs->get_file($cm->context->get_course_context()->id, 'mod_pdfsec', 'v1',$pdf->get_id(),'/','input.pdf');
if (!$file) {
    return false; // The file does not exist.
}
/*
$p=new \assignfeedback_editpdf\pdf();
$path=$file->copy_content_to_temp();
$pagecount=$p->setSourceFile($path);
$p->SetTitle('What?');
$p->setHeaderData('',0,'ČESKÝ VYSOKÝ UPEČENÝ TATARÁČEK','Fakulta Etiopie');
$p->setPrintHeader(true);
$p->AddPage();
$p->Polygon([50,50,100,75,150,200]);
$p->Arrow(20,20,50,50);
$p->AddPage();
$p->Text(20,20,'Tralalalalala aldasldas asodl aosdjaoisdjasiojd iojasdioj asiodj dioas.');
for ($i = 1; $i <= $pagecount; $i++) {
    $tplidx = $p->importPage($i);
    $p->AddPage();
    $p->setBookmark('First page');

    $p->useTemplate($tplidx);
}
$p->Output();*/




$pdf = new \assignfeedback_editpdf\pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

$pdf->SetCreator(PDF_CREATOR." for ".$USER->idnumber);
$pdf->SetTitle('Hospodka test');
$pdf->SetSubject('Study material for user '.$USER->idnumber);
$pdf->SetKeywords('PDF, document, dokument, study, studium, CTU in Prague, ČVUT v Praze');
$pdf->SetAuthor('(c) Copyright, České vysoké učení technické v Praze, Fakulta elektrotechnická, Technická 2, 166 27  Praha 6');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$language_meta = Array();
$language_meta['a_meta_charset'] = 'UTF-8';
$language_meta['a_meta_dir'] = get_string('thisdirection', 'langconfig');
$language_meta['a_meta_language'] = current_language();
$language_meta['w_page'] = get_string('page');

$pdf->setLanguageArray($language_meta);

$path=$file->copy_content_to_temp();
$pagecount=$pdf->setSourceFile($path);

//add watermark with user personal id on each page
for ($i=1; $i<=$pagecount; $i++) {
    $tplidx = $pdf->importPage($i, '/MediaBox'); //CropBox
    $s = $pdf->getTemplatesize($tplidx);

    $orientation = 'P';
    if ($s['w']>$s['h']) {
        $orientation='L';
    }
    $pdf->AddPage($orientation, array($s['w'], $s['h']));

    $pdf->useTemplate($tplidx);
    $pdf->setAlpha(0.04);

    $pdf->SetTextColor(0,0,0);
    $pdf->StartTransform();
    $pdf->Rotate(rad2deg(atan($s['w']/$s['h'])),$s['w']/2,$s['h']/2);
    $pdf->SetFont('freesans','',10);
    $pdf->MultiCell(0, 0, '© České vysoké učení technické v Praze
Fakulta elektrotechnická
', 0, 'C', 0, 1, 0, $s['h']/2-17.5);
    $pdf->MultiCell(0, 0, '© Czech Technical University in Prague
Faculty of Electrical Engineering
'.date("j. n. Y"), 0, 'C', 0, 1, 0, $s['h']/2+8.5);
    $pdf->SetFont('freesans','B',45);
    $pdf->MultiCell(0, 0, $USER->idnumber, 0, 'C', 0, 1, 0, $s['h']/2-10);
    $pdf->Rotate(0);
    $pdf->StopTransform();

    $pdf->setAlpha(0);
}
$options=[
        'secpdf'=>[
                'secpdfpermlicense'=>true,
                'secpdfuserpwd'=>'123',
        ]
];
if (array_key_exists('secpdf', $options)) {
    $secpdfoptions = $options['secpdf'];
    if ((is_array($secpdfoptions))&&(array_key_exists('secpdfpermlicense', $secpdfoptions))) {
        //add text of CVUT license as the new last page
        $s_lic['w'] = 210;
        $s_lic['h'] = 297;

        $pdf->AddPage('P', array($s_lic['w'], $s_lic['h']));
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('freesans','',10);
        $pdf->Write(5,"© ".date("Y")." ".'České vysoké učení technické v Praze, Fakulta elektrotechnická

Právní doložka (licence) k tomuto Dílu (elektronický materiál)

České vysoké učení technické v Praze (dále jen ČVUT) je ve smyslu autorského zákona vykonavatelem majetkových práv k Dílu či držitelem licence k užití Díla. Užívat Dílo smí pouze student nebo zaměstnanec ČVUT (dále jen Uživatel), a to za podmínek dále uvedených.
ČVUT poskytuje podle autorského zákona, v platném znění, oprávnění k užití tohoto Díla pouze Uživateli a pouze ke studijním nebo pedagogickým účelům na ČVUT. Toto Dílo ani jeho část nesmí být dále šířena (elektronicky, tiskově, vizuálně, audiem a jiným způsobem), rozmnožována (elektronicky, tiskově, vizuálně, audiem a jiným způsobem), využívána na školení, a to ani jako doplňkový materiál. Dílo nebo jeho část nesmí být bez souhlasu ČVUT využívána ke komerčním účelům. Uživateli je povoleno ponechat si Dílo i po skončení studia či pedagogické činnosti na ČVUT, výhradně pro vlastní osobní potřebu. Tím není dotčeno právo zákazu výše zmíněného užití Díla bez souhlasu ČVUT. Současně není dovoleno jakýmkoliv způsobem manipulovat s obsahem materiálu, zejména měnit jeho obsah včetně elektronických popisných dat, odstraňovat nebo měnit zabezpečení včetně vodoznaku a odstraňovat nebo měnit tyto licenční podmínky.
V případě, že Uživatel nebo jiná osoba, která drží toto Dílo (Držitel díla), nesouhlasí s touto licencí, nebo je touto licencí vyloučena z užití Díla, je jeho povinností zdržet se užívání Díla a je povinen toto Dílo trvale odstranit včetně veškerých kopií (elektronické, tiskové, vizuální, audio a zhotovených jiným způsobem) z elektronického zařízení a všech záznamových zařízení, na které jej Držitel díla umístil.');
    }

    $permissions = Array();
    $user_pass = null;
    $owner_pass = null;

    if (is_array($secpdfoptions)) {
        if (array_key_exists('secpdfuserpwd', $secpdfoptions)) $user_pass = $secpdfoptions['secpdfuserpwd'];
        if (array_key_exists('secpdfownerpwd', $secpdfoptions)) $owner_pass = $secpdfoptions['secpdfownerpwd'];
        if (!array_key_exists('secpdfpermprint', $secpdfoptions)) $permissions[] = 'print';
        if (!array_key_exists('secpdfpermmodify', $secpdfoptions)) $permissions[] = 'modify';
        if (!array_key_exists('secpdfpermcopy', $secpdfoptions)) $permissions[] = 'copy';
        if (!array_key_exists('secpdfpermextract', $secpdfoptions)) $permissions[] = 'extract';
        if (!array_key_exists('secpdfpermassemble', $secpdfoptions)) $permissions[] = 'assemble';
    }
    //error_log("permissions_filelib.php:\n".print_r($permissions, true)."\n", 3 , '/opt/moodle-dev/logs/events2-test.log');
    //error_log("user_pass_filelib.php:\n".print_r($user_pass, true)."\n", 3 , '/opt/moodle-dev/logs/events2-test.log');
    //error_log("owner_pass_filelib.php:\n".print_r($owner_pass, true)."\n", 3 , '/opt/moodle-dev/logs/events2-test.log');

    // set security permission - mode 3 - 256 AES
    $pdf->SetProtection($permissions, ( empty($user_pass) ? '' : $user_pass), ( empty($owner_pass) ? null : $owner_pass), $mode=3, null);

}
$pdf->Output();

