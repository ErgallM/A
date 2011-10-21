<?php
namespace A\Autoload;

/**
 * Main autoload class
 * Load class and files
 * @throws \Exception
 */
class Main {

    /**
     * Load class
     *
     * @static
     * @throws \Exception
     * @param string $className - name loading class
     * @param null|array $dirs
     * @return void
     */
    public static function loadClass($className, $dirs = null)
    {
        if (class_exists($className, false) || interface_exists($className, false)) {
            return;
        }

        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

        include_once $fileName;

        if (!class_exists($className, false) && !interface_exists($className, false)) {
            throw new \Exception("File '$fileName' dos't include '$className' class");
        }
    }

    /**
     * Init class autoloader
     * 
     * @static
     * @return void
     */
    public static function initAutoloader()
    {
        spl_autoload_register(__NAMESPACE__ . '\Main::loadClass');
    }
}