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
 * Settings for pdf annotation.
 *
 *
 * @package    mod_pdfsec\entity
 * @category   entity
 * @copyright  2018 CVUT CZM
 * @author Jiri Fryc
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_pdfsec\entity;

defined('MOODLE_INTERNAL') || die();

class pdfsec_settings {

    // region Variables.
    /** @var ?pdfsec_settings $_parent Parent settings. */
    private $_parent;

    /** @var ?string $subject Subject of the PDF. */
    protected $subject = null;
    /** @var ?string $keywords Keywords */
    protected $keywords = null;
    /** @var ?string $author of the PDF */
    protected $author = null;
    /** @var ?bool $printheader Hide header */
    protected $printheader = null;
    /** @var ?bool $printfooter Hide footer */
    protected $printfooter = null;
    /** @var ?string $watermark Watermark */
    protected $watermark = null;
    /** @var ?string $language Language */
    protected $language = null;
    /** @var ?string $lastpage Last page of the PDF. */
    protected $lastpage = null;
    /** @var ?string $firstpage First page of the PDF. */
    protected $firstpage = null;

    /** @var string $userpassword User password */
    protected $userpassword = null;
    /** @var string $ownerpassword Owner password */
    protected $ownerpassword = null;

    /** @var bool $canprint If user can print PDF. */
    protected $canprint = null;
    /** @var bool $canmodify If user can modify PDF. */
    protected $canmodify = null;
    /** @var bool $cancopy If user can copy text from PDF. */
    protected $cancopy = null;
    /** @var bool $canextract If user can extract page from PDF. */
    protected $canextract = null;
    /** @var bool $canassemble If user can combine other PDF with this PDF. */
    protected $canassemble = null;

    // endregion.

    // region Getters.

    protected function getter(string $name) {
        if ($this->{$name} === null) {
            if ($this->_parent == null) {
                return null;
            }
            return $this->_parent->getter($name);
        }
        return $this->{$name};
    }

    public function get_subject() {
        return $this->getter('subject');
    }
    public function get_keywords() {
        return $this->getter('keywords');
    }
    public function get_author() {
        return $this->getter('author');
    }
    public function get_printheader() {
        return $this->getter('printheader');
    }
    public function get_printfooter() {
        return $this->getter('printheader');
    }

    // endregion.

    public function combine_with(pdfsec_settings $settings, bool $overwrite = false) {
        $reflect = new \ReflectionClass($this);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PROTECTED);
        foreach ($props as $prop) {
            if ($this->{$prop->getName()} == null || $overwrite) {
                $this->{$prop->getName()} = $settings->{$prop->getName()};
            }
        }
    }

    public function set_parent(pdfsec_settings $settings) {
        $this->_parent = $settings;
    }

    public static function from_json($json) {
        $array = json_decode($json, true);

        $object = new pdfsec_settings();
        foreach ($array as $key => $value) {
            $object->{$key} = $value;
        }
        return $object;
    }

    public function to_json(): string {
        $array = [];
        $reflect = new \ReflectionClass($this);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PROTECTED);
        foreach ($props as $prop) {
            if ($this->{$prop->getName()} != null) {
                $array[$prop->getName()] = $this->{$prop->getName()};
            }
        }
        return json_encode($array);
    }

    public static function get_default() {
        $object = new pdfsec_settings();
        $object->printfooter = false;
        $object->printheader = false;
        $object->author = '%username%';
        $object->language = 'en';
        $object->canprint = true;
        $object->canmodify = false;
        $object->cancopy = false;
        $object->canextract = false;
        $object->canassemble = false;
        return $object;
    }
}