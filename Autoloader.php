<?php
/**
 * Created by PhpStorm.
 * User: fstein
 * Date: 07.12.18
 * Time: 15:31
 */


class Autoloader
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $alias;

    /**
     * Initialisiert den Autoloader und registriert im spl_autoload einen neuen Callback, der die Funktion "loadClass"
     * dieser Klasse ausführt.
     */
    public function __construct()
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * Definieren einen Prefix im Namespace der in eine Ordnerstruktur übersetzt werden soll.
     *
     * @param string $alias
     * @param string $prefix
     */
    public function registerNamespace($alias, $prefix)
    {
        $this->prefix = $prefix;
        $this->alias = $alias;
    }

    /**
     * Versuche eine Klasse anhand des gegebenen Namens zu laden.
     *
     * @param string $className
     */
    public function loadClass($className)
    {
        $path = str_replace('\\', '/', $className);
        $path = str_replace($this->alias, $this->prefix, $path);

        $filename = __DIR__ . '/' . $path . '.php';

        if(file_exists($filename))
        {
            require_once $filename;
        }
    }
}