<?php
namespace A\Event;
use A\Config as C;

class Event
{
    /**
     * @var string
     */
    protected $_name = '';

    /**
     * @var \Closure
     */
    protected $_event = null;

    /**
     * @var array
     */
    protected $_params = array();

    /**
     * @var array
     */
    protected $_prependEvent = array();

    /**
     * @var array
     */
    protected $_appendEvent = array();

    /**
     * @throws \Exception
     * @param array|\A\Config\Config|string $name
     * @param \Closure|null $event
     * @param null $params
     */
    public function __construct($name, \Closure $event = null, $params = null)
    {
        if ($name instanceof C\Config) {
            $name = $name->toArray();
        }

        if (is_array($name)) {
            extract($name);
        }

        if (!is_callable($event)) {
            throw new \Exception("Event isn't callable");
        }

        $this->setName($name);
        $this->setEventFunction($event);

        if (null !== $params) $this->setParams($params);
    }

    /**
     * Set event name
     *
     * @param string $name
     * @return Event
     */
    public function setName($name)
    {
        $this->_name = (string) $name;
        return $this;
    }

    /**
     * Get event name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Set event function
     *
     * @param \Closure $event
     * @return Event
     */
    public function setEventFunction(\Closure $event)
    {
        $this->_event = $event;
        return $this;
    }

    /**
     * Get event function
     * @return \Closure|null
     */
    public function getEventFunction()
    {
        return $this->_event;
    }

    /**
     * Set event param
     * @param string $name
     * @param mixed $value
     * @return Event
     */
    public function __set($name, $value)
    {
        $this->_params[(string) $name] = $value;
        return $this;
    }

    /**
     * Set event params
     *
     * @param array|\A\Config\Config $params
     * @return Event
     */
    public function setParams($params)
    {
        $this->_params = array();
        
        if ($params instanceof C\Config) {
            $params = $params->toArray();
        }

        foreach ($params as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }

    /**
     * Add event param
     *
     * @param string $name
     * @param mixed $value
     * @return Event
     */
    public function addParam($name, $value)
    {
        $name = (string) $name;
        $this->$name = $value;
        return $this;
    }

    /**
     * Get event param
     *
     * @param string $name
     * @param mixed|null $default
     * @return mixed|null
     */
    public function getParam($name, $default = null)
    {
        return (!isset($this->_params[(string) $name])) ? $default : $this->_params[(string) $name];
    }

    /**
     * Get event param
     *
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->getParam($name);
    }

    /**
     * Add child event
     *
     * @param Event $event
     * @param string $type ('prepend', 'append')
     * @return Event
     */
    public function addChildEvent(Event $event, $type = 'prepend')
    {
        $name = $event->getName();
        Container::getInstance()->addEvent($event);

        if ('prepend' == $type) {
            $this->_prependEvent[] = $name;
        } else {
            $this->_appendEvent[] = $name;
        }

        return $this;
    }

    /**
     * Get child event
     *
     * @param string $name
     * @return Event
     */
    public function getChildEvent($name)
    {
        return Container::getInstance()->$name;
    }

    /**
     * Get child events
     * 
     * @param null|string $type (null, 'prepend', 'append')
     * @return array
     */
    public function getChildEvents($type = null)
    {
        if (null == $type) {
            return array('prepend' => $this->_prependEvent, 'append' => $this->_appendEvent);
        } elseif ('prepend' == $type) {
            return $this->_prependEvent;
        } else {
            return $this->_appendEvent;
        }
    }

    /**
     * Run the event
     * 
     * @return void
     */
    public function __invoke()
    {
        $container = Container::getInstance();

        foreach ($this->_prependEvent as $eventName) {
            $container->callEvent($eventName);
        }

        $event = $this->getEventFunction();
        $event();

        foreach ($this->_appendEvent as $eventName) {
            $container->callEvent($eventName);
        }
    }

}