<?php
/**
 * Settings.
 *
 * @category   Moodle
 * @author     Jiri Fryc
 * @copyright  2017 Jiri Fryc,CVUT CZM
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html  GNU Affero General Public License (Version 3)
 * @version    Alpha 0.1.0
 * @link       https://gitlab.fel.cvut.cz/czm/moodle17/moodle-local_Kos
 * @since      Alpha 0.1.0
 */
defined('MOODLE_INTERNAL') || die;
if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configselect(
            'mod_pdfsec/defaultwatermarkvisibility', get_string('watermarkvisibletypes', 'mod_pdfsec'), '', '0',
            ['0' => get_string('watermarkvisible', 'mod_pdfsec'), '1' => get_string('watermarkinvisible', 'mod_pdfsec')]
    ));
    $settings->add(new admin_setting_configtext('mod_pdfsec/subject', get_string('subject', 'mod_pdfsec'), '', ''));
    $settings->add(new admin_setting_configtext('mod_pdfsec/keywords', get_string('keywords', 'mod_pdfsec'), '', ''));
    $settings->add(new admin_setting_configtext('mod_pdfsec/author', get_string('author', 'mod_pdfsec'), '', ''));
    $settings->add(new admin_setting_configtext('mod_pdfsec/language', get_string('language', 'mod_pdfsec'), '', ''));

    $settings->add(new admin_setting_configselect('mod_pdfsec/perm_print', get_string('perm_print', 'mod_pdfsec'),
            get_string('perm_print_help', 'mod_pdfsec'), '1', ['0' => get_string('no'), '1' => get_string('yes')]));
    $settings->add(new admin_setting_configselect('mod_pdfsec/perm_modify', get_string('perm_modify', 'mod_pdfsec'),
            get_string('perm_modify_help', 'mod_pdfsec'), '0', ['0' => get_string('no'), '1' => get_string('yes')]));
    $settings->add(new admin_setting_configselect('mod_pdfsec/perm_extract', get_string('perm_extract', 'mod_pdfsec'),
            get_string('perm_extract_help', 'mod_pdfsec'), '1', ['0' => get_string('no'), '1' => get_string('yes')]));
    $settings->add(new admin_setting_configselect('mod_pdfsec/perm_copy', get_string('perm_copy', 'mod_pdfsec'),
            get_string('perm_copy_help', 'mod_pdfsec'), '1', ['0' => get_string('no'), '1' => get_string('yes')]));
    $settings->add(new admin_setting_configselect('mod_pdfsec/perm_assemble', get_string('perm_assemble', 'mod_pdfsec'),
            get_string('perm_assemble_help', 'mod_pdfsec'), '0', ['0' => get_string('no'), '1' => get_string('yes')]));

    $settings->add(new admin_setting_configtextarea('mod_pdfsec/firstpage',
            get_string('firstpage', 'mod_pdfsec'), '', ''));
    $settings->add(new admin_setting_configtextarea('mod_pdfsec/lastpage',
            get_string('lastpage', 'mod_pdfsec'), '', ''));
    $settings->add(new admin_setting_configtextarea('mod_pdfsec/watermark',
            get_string('watermark', 'mod_pdfsec'), '', ''));

    $settings->add(new admin_setting_configselect('mod_pdfsec/watermarkvisible',get_string('watermarkvisibletypes', 'mod_pdfsec'),'','1',[
            1 => get_string('watermarkvisible', 'mod_pdfsec'),
            0 => get_string('watermarkinvisible', 'mod_pdfsec')
    ]));

}