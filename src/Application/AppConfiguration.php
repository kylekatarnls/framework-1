<?php
namespace Bow\Application;

use Bow\Support\Util;
use Bow\Exception\ApplicationException;

/**
 * Class AppConfiguratio
 *
 * @author Franck Dakia <dakiafranck@gmail.com>
 * @package Bow\Core
 */
class AppConfiguration
{
    /**
     * @var AppConfiguration
     */
    private static $instance;

    /**
     * @var string
     */
    private $approot;

    /**
     * @var string
     */
    private $public = "";

    /**
     * @var string
     */
    private $template_extension = ".php";

    /**
     * @var string
     */
    private $app_key = "Eda4W+AyMDE2LTAyLTE2IDIwOjM2OjE0";

    /**
     * @var array
     */
    private $routes = [];

    /**
     * @param $config
     *
     * @throws \Bow\Exception\UtilException
     */
    private final function __construct($config)
    {
        /**
         * Chargement complet de toute la configuration de Bow
         */
        $this->config = $config;

        if (isset($config["application"])) {
            if (isset($config["application"]->app_root)) {
                $this->approot = $config["application"]->app_root;
            }

            if (is_file($config["application"]->app_key)) {
                $this->app_key = file_get_contents($config["application"]->app_key);
            }

            if (isset($config["application"]->timezone)) {
                Util::setTimezone($config["application"]->timezone);
            }
        }
    }

    /**
     * Ferméture de la fonction magic __clone pour optimizer le singleton
     */
    private final function __clone(){}

    /**
     * takeInstance singleton
     *
     * @param array $config
     *
     * @return AppConfiguration
     */
    public static function configure($config) {
        if (! static::$instance instanceof AppConfiguration) {
            static::$instance = new self($config);
        }

        return static::$instance;
    }

    /**
     * takeInstance singleton
     *
     * @return AppConfiguration
     */
    public static function takeInstance() {
        if (! static::$instance instanceof AppConfiguration) {
            static::configure([]);
        }

        return static::$instance;
    }

    /**
     * Retourne Application key
     *
     * @return string
     */
    public function getAppkey()
    {
        return $this->app_key;
    }
    /**
     * configure, Application key
     *
     * @param string $key
     *
     * @throws ApplicationException
     *
     * @return string
     */
    public function setAppkey($key)
    {
        $old = $this->app_key;

        if (!is_string($key)) {
            throw new ApplicationException("Le paramètre doit être une chaine de caractère.", E_USER_ERROR);
        }

        $this->app_key = $key;
        return $old;
    }

    /**
     * Met Ajout les routes nommés.
     *
     * @param array $routes
     */
    public function setApplicationRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Retourne la liste de Routes nommés
     *
     * @return array
     */
    public function getApplicationRoutes()
    {
        return $this->routes;
    }

    /**
     * setAppName
     *
     * @param string $newAppName
     *
     * @throws ApplicationException
     *
     * @return string
     */
    public function setAppname($newAppName)
    {
        $old = $newAppName;

        if (!is_string($newAppName)) {
            throw new ApplicationException("Le parametre doit etre une chaine de carrectere.", E_USER_ERROR);
        }

        $this->config["application"]->app_name = $newAppName;

        return $old;
    }

    /**
     * getAppName
     *
     * @return string
     */
    public function getAppname()
    {
        return $this->config["application"]->app_name;
    }

    /**
     * getViewPath retourne configuration du path du repertoire du cache
     *
     * @param string $viewPath
     *
     * @throws ApplicationException
     *
     * @return string
     */
    public function setViewpath($viewPath)
    {
        $old = $this->config["application"]->views_path;

        if (!realpath($viewPath)) {
            throw new ApplicationException("Ce chemin n'est pas valide.", E_USER_ERROR);
        }

        $this->config["application"]->views_path = $viewPath;

        return $old;
    }

    /**
     * getViewPath retourne configuration du path du repertoire du cache
     *
     * @return string
     */
    public function getViewpath()
    {
        return rtrim($this->config["application"]->views_path, '/');
    }

    /**
     * setCachePath
     *
     * @param string $newCachePath
     *
     * @throws ApplicationException
     *
     * @return string
     */
    public function setCachepath($newCachePath)
    {
        $old = $this->config["application"]->template_cache_folder;

        if (!realpath($newCachePath)) {
            throw new ApplicationException("Ce chemin n'est valide.", E_USER_ERROR);
        }

        $this->config["application"]->template_cache_folder = $newCachePath;

        return $old;
    }

    /**
     * getCachePath retourne configuration du path du repertoire du cache
     *
     * @return string
     */
    public function getCachepath()
    {
        return $this->config["application"]->template_cache_folder;
    }

    /**
     * setLogPath configuration du path du répertoir de log
     *
     * @param string $newLogPath
     *
     * @throws ApplicationException
     *
     * @return string
     */
    public function setLoggerPath($newLogPath)
    {
        $old = $this->config["application"]->log_direcotory_name;

        if (!realpath($newLogPath)) {
            throw new ApplicationException("Ce chemin n'est valide.", E_USER_ERROR);
        }

        $this->config["application"]->log_direcotory_name = $newLogPath;

        return $old;
    }

    /**
     * getLogPath retourne la configuration du path du répertoir de log
     *
     * @return string
     */
    public function getLoggerPath()
    {
        return $this->config["application"]->log_direcotory_name;
    }

    /**
     * Modifie le niveau de log
     * Deux valeur possible development | production
     *
     * @param string $log
     *
     * @throws ApplicationException
     *
     * @return string
     */
    public function setLoggerMode($log)
    {
        $old = $this->config["application"]->debug;

        if (!in_array($log, ["development", "production"])) {
            throw new ApplicationException("$log n'est pas accepte. <i>development|production</i>", E_USER_ERROR);
        }

        $this->config["application"]->debug = $log;

        return $old;
    }

    /**
     * Retoure de niveau du log
     * Deux valeur possible development | production
     *
     * @return string
     */
    public function getLoggerMode()
    {
        return $this->config["application"]->debug;
    }

    /**
     * getTimezone retourne la configuration de la TL
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->config["application"]->timezone;
    }

    /**
     * getNamespace retourne la configuration des namespaces
     *
     * @return array
     */
    public function getNamespace()
    {
        return $this->config["application"]->classes;
    }

    /**
     * getEngine retourne le nom du moteur de template définir
     *
     * @return string
     */
    public function getTemplateEngine()
    {
        return $this->config["application"]->template_engine;
    }

    /**
     * setEngine retourne le nom du moteur de template définir
     *
     * @param string $engine
     * @return string
     */
    public function setTemplateEngine($engine)
    {
        return $this->config["application"]->template_engine = $engine;
    }

    /**
     * setApproot
     *
     * @param string $newApproot
     *
     * @return string
     */
    public function setApproot($newApproot)
    {
        $old = $this->approot;

        if (is_string($this->approot)) {
            $this->approot = $newApproot;
        }

        return $old;
    }

    /**
     * Retourn la route principal de l'application.
     *
     * @return string
     */
    public function getApproot()
    {
        return ltrim($this->approot, '/');
    }

    /**
     * @return string
     */
    public function getPublicPath()
    {
        if (isset($this->config["application"]->static_files_directory)) {
            return $this->config["application"]->static_files_directory;
        }

        return $this->public;
    }

    /**
     * Modifie le chemin vers les fichiers public.
     *
     * @param string $public Le nouveau chemin vers les fichiers public.
     * @return string
     */
    public function setPublicPath($public)
    {
        if (isset($this->config["application"]->static_files_directory)) {
            $old = $this->config["application"]->static_files_directory;
            $this->$this->config["application"]->static_files_directory = $public;
        } else {
            $old = $this->public;
            $this->public = $public;
        }

        return $old;
    }

    /**
     * modifier l'extension de template.
     *
     * @param string $extension
     *
     * @return string
     */
    public function setTemplateExtension($extension)
    {
        $old = $this->config["application"]->template_extension;

        if (is_string($extension)) {
            $this->config["application"]->template_extension = $extension;
        }

        return $old;
    }

    /**
     * retourne l'extension de template.
     *
     * @return string
     */
    public function getTemplateExtension()
    {
        return $this->config["application"]->template_extension;
    }

    /**
     * @return mixed
     */
    public function getCacheAutoReload()
    {
        return $this->config["application"]->template_auto_reload_cache_views;
    }

    /**
     * retourne la configuration de la base de donnée
     *
     * @return array|object
     */
    public function getDatabaseConfiguration()
    {
        return $this->config["database"];
    }

    /**
     * retourne la configuration des mails
     *
     * @return array|object
     */
    public function getMailConfiguration()
    {
        return $this->config["mail"];
    }

    /**
     * retourne la configuration des resources locale
     *
     * @return array|object
     */
    public function getDefaultStoragePath()
    {
        return $this->config["resource"]['storage'];
    }

    /**
     * retourne la configuration des resources ftp
     *
     * @return array
     */
    public function getFtpConfiguration()
    {
        return $this->config["resource"]['ftp'];
    }

    /**
     * Retourne un booléen qui répresente une décision de configuration
     * des paramètres sur les callbacks et les methods des controlleurs
     *
     * @return bool
     */
    public function getTakeInstanceOfApplicationInFunction()
    {
        return $this->config["application"]->instance_of_application_in_function;
    }

    /**
     * modifie le driver du systeme de mail.
     * Deux valeur possible mail | smtp
     *
     * @param string $driver
     *
     * @return string
     *
     * @throws ApplicationException
     */
    public function setMailDriver($driver) {

        if (!in_array($driver, ["mail", "smtp"])) {
            throw new ApplicationException("$driver n'est valide", E_ERROR);
        }

        $old = $this->config["mail"]->driver;
        $this->config["mail"]->driver = $driver;

        return $old;
    }
}