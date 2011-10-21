<?php
namespace A;

/**
 * Register class
 *
 * @throws \Exception
 */
class Registry {

    private static $_registry = array();

    /**
     * Geting registry variable
     *
     * @static
     * @throws \Exception
     * @param string $name
     * @return midex
     */
    public static function get($name)
    {
        if (!isset(self::$_registry[$name])) {
            throw new \Exception("No entry is registtered for key '$name'");
        }

        return self::$_registry[$name];
    }

    /**
     * Seting variable to registry
     * @static
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public static function set($name, $value)
    {
        self::$_registry[$name] = $value;
    }
}