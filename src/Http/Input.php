<?php
namespace Bow\Http;

use Closure;
use ErrorException;
use Bow\Support\Collection;
use Bow\Interfaces\CollectionAccess;

/**
 * Class RequestData
 *
 * @author Franck Dakia <dakiafranck@gmail.com>
 * @package Bow\Http
 */
class Input implements CollectionAccess
{

    /**
     * @var array
     */
    private $data = [];

    /**
     * @static self
     */
    private static $instance = null;

    /**
     * @static string
     */
    private static $last_method = '';

    /**
     * Fonction magic __clone en <<private>>
     */
    private function __clone()
    {
    }

    public function __construct()
    {
        $this->data = array_merge($_POST, $_GET, $_FILES);
    }

    /**
     * has, vérifie l'existance d'une clé dans la colléction
     *
     * @param string $key
     * @param bool $strict
     *
     * @return boolean
     */
    public function has($key, $strict = false)
    {
        if ($strict) {
            return isset($this->data[$key]) && !empty($this->data[$key]);
        } else {
            return isset($this->data[$key]);
        }
    }

    /**
     * isEmpty, vérifie si une collection est vide.
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->data);
    }

    /**
     * get, permet de récupérer une valeur ou la colléction de valeur.
     *
     * @param string $key =null
     * @param mixed $default =false
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    /**
     * get, permet de récupérer une valeur ou la colléction de valeur.
     *
     * @param array|string|int $expects
     * @return mixed
     */
    public function getWithOut($expects)
    {
        $data = [];

        if (!is_array($expects)) {
            $keyWasDefine = $expects;
        } else {
            $keyWasDefine = func_get_args();
        }

        foreach ($this->data as $key => $value) {
            if (!in_array($key, $keyWasDefine)) {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    /**
     * vérifie si le contenu de $this->data poccedent la $key n'est pas vide.
     *
     * @param string $key
     * @param string $eqTo
     *
     * @return bool
     */
    public function isValide($key, $eqTo = null)
    {
        $boolean = $this->has($key, true);

        if ($eqTo && $boolean) {
            $boolean = $boolean && preg_match("~$eqTo~", $this->get($key));
        }

        return $boolean;
    }

    /**
     * remove, supprime une entrée dans la colléction
     *
     * @param string $key
     *
     * @return Input
     */
    public function remove($key)
    {
        unset($this->data[$key]);

        return $this;
    }

    /**
     * add, ajoute une entrée dans la colléction
     *
     * @param string $key
     * @param mixed $data
     * @param bool $next
     *
     * @return Input
     */
    public function add($key, $data, $next = false)
    {
        if ($this->has($key)) {
            if ($next) {
                array_push($this->data[$key], $data);
            } else {
                $this->data[$key] = $data;
            }
        } else {
            $this->data[$key] = $data;
        }

        return $this;
    }

    /**
     * set, modifie une entrée dans la colléction
     *
     * @param string $key
     * @param mixed $value
     *
     * @throws ErrorException
     *
     * @return Input
     */
    public function set($key, $value)
    {
        if ($this->has($key)) {
            $this->data[$key] = $value;
        } else {
            throw new ErrorException("Clé non définie", E_NOTICE);
        }

        return $this;
    }

    /**
     * each, parcourir les entrées de la colléction
     *
     * @param Closure $cb
     */
    public function each(Closure $cb)
    {
        if ($this->isEmpty()) {
            call_user_func_array($cb, [null, null]);
        } else {
            foreach ($this->data as $key => $value) {
                call_user_func_array($cb, [$value, $key]);
            }
        }
    }

    /**
     * __get
     *
     * @param string $name Le nom de la variable
     * @return null
     */
    public function __get($name)
    {
        if ($this->has($name)) {
            return $this->data[$name];
        }

        return null;
    }

    /**
     * Alias sur toArray
     */
    public function all()
    {
        return $this->toArray();
    }

    /**
     * @param $method
     * @return array
     */
    public function method($method)
    {
        if ($method == "GET") {
            return $_GET;
        } else if ($method == "POST") {
            return $_POST;
        } else if ($method == "FILES") {
            return $_FILES;
        }

        return [];
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return (array) $this->data;
    }

    /**
     * @inheritdoc
     */
    public function toObject()
    {
        return (object) $this->data;
    }

    /**
     * Retourne une instance de la classe collection.
     *
     * @return Collection
     */
    public function toCollection()
    {
        return new Collection($this->data);
    }

    /**
     * __set
     *
     * @param string $name Le nom de la variable
     * @param mixed $value La valeur a assigné
     * @return null
     */
    public function __set($name, $value)
    {
        $old = null;

        if ($this->has($name)) {
            $old = $this->data[$name];
            $this->data[$name] = $value;
        } else {
            $this->data[$name] = $value;
        }

        return $old;
    }
}
