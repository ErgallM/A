<?php
namespace A\Event;

class Container
{
    /**
     * @var Container
     */
    private static $_instance = null;

    protected $_events = array();

    /**
     * @static
     * @return Container
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Add event
     *
     * @param Event $event
     * @return Container
     */
    public function addEvent(Event $event)
    {
        $name = $event->getName();
        $this->_events[$name] = $event;
        return $this;
    }

    /**
     * Get event
     *
     * @throws \Exception
     * @param string $name
     * @return Event
     */
    public function getEvent($name)
    {
        $name = (string) $name;
        if (!isset($this->_events[$name])) {
            throw new \Exception("Event with name '$name' not found");
        }

        return $this->_events[$name];
    }

    /**
     * Get event
     * 
     * @param string $name
     * @return Event
     */
    public function __get($name)
    {
        return $this->getEvent($name);
    }

    /**
     * Call event
     *
     * @param string $name
     * @return void
     */
    public function callEvent($name)
    {
        $this->getEvent($name)->__invoke();
    }
}