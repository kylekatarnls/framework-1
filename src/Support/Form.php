<?php
namespace Bow\Support;

/**
 * Class Form
 *
 * @author Franck Dakia <dakiafranck@gmail.com>
 * @package Bow\Support
 */
class Form
{
    private final function __construct() {}
    private final function __clone() {}
    private static $form = "";

    /**
     * Les informations prédéfinis.
     *
     * @var array
     */
    private static $with = [];

    /**
     * @param $key
     * @return string
     */
    private static function getAssociateValue($key)
    {
        return isset(static::$with[$key]) ? static::$with[$key] : '';
    }

    /**
     * Ajout le tag <input type="text">
     *
     * @param string $name
     * @param string $value=""
     * @param string $placeholder=null
     *
     * @return void
     */
    public static function text($name, $value = "", $placeholder = null)
    {
        $value = static::getAssociateValue($name);
        self::$form .= "<input type=\"text\" value=\"{$value}\" name=\"{$name}\" ".(is_string($placeholder) ? 'placeholder="' . $placeholder . '"' : '')."/>";
    }

    /**
     * Ajout le tag <input type="password">
     *
     * @param string $name
     * @param string $value=""
     * @param string $placeholder=null
     *
     * @return void
     */
    public static function password($name, $value = "", $placeholder = null)
    {
        $value = static::getAssociateValue($name);
        self::$form .= "<input type=\"password\" name=\"{$name}\" value=\"{$value}\" ".(is_string($placeholder) ? 'placeholder="' . $placeholder . '"' : '')."/>";
    }


    /**
     * Ajout le tag <input type="hidden">
     *
     * @param string $name
     * @param string $value=""
     * @param string $placeholder=null
     *
     * @return void
     */
    public static function hidden($name, $value = "", $placeholder = null)
    {
        $value = static::getAssociateValue($name);
        self::$form .= "<input type=\"hidden\" value=\"{$value}\" name=\"{$name}\" ".(is_string($placeholder) ? 'placeholder="' . $placeholder . '"' : '')."/>";
    }

    /**
     * Ajout le tag <input type="file">
     *
     * @param string $name
     *
     * @return void
     */
    public static function file($name)
    {
        self::$form .= "<input type=\"file\" name=\"{$name}\"/>";
    }

    /**
     * Ajout le tag <input type="submit">
     *
     * @param string $value
     *
     * @return void
     */
    public static function submit($value)
    {
        self::$form .= "<button type=\"submit\">{$value}</button>";
    }

    /**
     * Ajout le tag <textarea></textarea>
     *
     * @param string $name
     * @param string $text
     *
     * @return void
     */
    public static function textarea($name, $text = "")
    {
        $text = static::getAssociateValue($name);
        self::$form .= "<textarea name=\"{$name}\">" . $text . "</textarea>";
    }

    /**
     * Ajout le tag <input type="checkbox">
     *
     * @param string $name
     * @param bool $checked=false
     * @param string $value=""
     *
     * @return void
     */
    public static function checkbox($name, $checked = false,  $value = "")
    {
        $value = static::getAssociateValue($name);
        self::$form .= "<input type=\"checkbox\" name=\"{$name}\" value=\"{$value}\" ". ($checked == true ? 'cheched' : '')."/>";
    }

    /**
     * Ajout le tag radio.
     *
     * @param string $name
     * @param bool $checked=false
     * @param string $value
     *
     * @return void
     */
    public static function radio($name, $checked = false, $value = "")
    {
        $value = static::getAssociateValue($name);
        self::$form .= "<input type=\"radio\" name=\"{$name}\" value=\"{$value}\" " . ($checked == true ? 'cheched' : '')."/>";
    }

    /**
     * Ajout le tag radio.
     *
     * @param string $name
     * @param array $options
     * @param string|null $selected
     *
     * @return void
     */
    public static function select($name, array $options = [], $selected = null)
    {
        self::$form .= "<select name=\"$name\">";
        $oldValue = static::getAssociateValue($name);

        foreach($options as $key => $value) {

            if ($oldValue !== "") {
                $key = $oldValue;
            }

            self::$form .= "<option value=\"{$key}\" " . ($selected == $key ? "selected" : "") . ">" . $value . "</option>";
        }

        self::$form .= "</select>";
    }

    /**
     * Ajout le tag <fieldset>
     *
     * @param null|string $legend
     *
     * @return void
     */
    public static function addFieldSet($legend = null)
    {
        self::$form .= "<fieldset>";
        if (is_string($legend)) {
            self::$form .= "<legend>{$legend}</legend>";
        }
    }

    /**
     * Ferméture du tag fieldset
     *
     * @return void
     */
    public static function closeFieldSet()
    {
        self::$form .= "</fieldset>";
    }

    /**
     * Ajout un retoure chariot de la colléction de balise
     *
     * @return void
     */
    public static function outline()
    {
        self::$form .= "<br/>";
    }

    /**
     * Ajout le tag <label></label>
     *
     * @param string $title
     * @param string|null $for=null
     *
     * @return void
     */
    public static function label($title, $for = null)
    {
        self::$form .= "<label ". ($for !== null ? "for={$for}": "") .">" . $title . "</label>";
    }

    /**
     * Creation définitive du formulaire.
     *
     * @param $method
     * @param $action
     * @param string $id
     * @param bool|false $enctype
     *
     * @return string
     */
    public static function compile($method, $action, $enctype = false, $id = "form")
    {
        return "<form id=\"$id\" method=\"{$method}\" action=\"{$action}\" ".($enctype === true ? 'enctype="multipart/form-data"': "").">". self::$form . "</form>";
    }

    /**
     * Associé un model
     *
     * @param array $data
     * @return void
     */
    public static function with($data)
    {
        static::$with = $data;
    }
}