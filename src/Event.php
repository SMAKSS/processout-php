<?php

namespace ProcessOut;

use ProcessOut\ProcessOut;
use ProcessOut\Networking\Response;
use ProcessOut\Networking\RequestProcessoutPrivate;
use ProcessOut\Networking\RequestProcessoutPublic;


class Event
{

    /**
     * ProcessOut's instance
     * @var ProcessOut\ProcessOut
     */
    protected $instance;

    /**
     * Id of the event
     * @var string
     */
    protected $id;

    /**
     * Name of the event
     * @var string
     */
    protected $name;

    /**
     * Data associated to the event, in the form of a dictionary
     * @var dictionary
     */
    protected $data;

    /**
     * The date at which the event was fired
     * @var string
     */
    protected $date;

    /**
     * Whether or not the event was fired in the sandbox environment
     * @var boolean
     */
    protected $sandbox;

    /**
     * Event constructor
     * @param ProcessOut\ProcessOut|null $processOut
     */
    public function __construct(ProcessOut $processOut = null)
    {
        if(is_null($processOut))
        {
            $processOut = ProcessOut::getDefault();
        }

        $this->instance = $processOut;

        
    }

    
    /**
     * Get Id
     * Id of the event
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Id
     * Id of the event
     * @param  string $value
     * @return $this
     */
    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }
    
    /**
     * Get Name
     * Name of the event
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Name
     * Name of the event
     * @param  string $value
     * @return $this
     */
    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }
    
    /**
     * Get Data
     * Data associated to the event, in the form of a dictionary
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set Data
     * Data associated to the event, in the form of a dictionary
     * @param  array $value
     * @return $this
     */
    public function setData($value)
    {
        $this->data = $value;
        return $this;
    }
    
    /**
     * Get Date
     * The date at which the event was fired
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set Date
     * The date at which the event was fired
     * @param  string $value
     * @return $this
     */
    public function setDate($value)
    {
        $this->date = $value;
        return $this;
    }
    
    /**
     * Get Sandbox
     * Whether or not the event was fired in the sandbox environment
     * @return bool
     */
    public function getSandbox()
    {
        return $this->sandbox;
    }

    /**
     * Set Sandbox
     * Whether or not the event was fired in the sandbox environment
     * @param  bool $value
     * @return $this
     */
    public function setSandbox($value)
    {
        $this->sandbox = $value;
        return $this;
    }
    

    /**
     * Fills the current object with the new values pulled from the data
     * @param  array $data
     * @return Event
     */
    public function fillWithData($data)
    {
        if(! empty($data["id"]))
            $this->setId($data["id"]);

        if(! empty($data["name"]))
            $this->setName($data["name"]);

        if(! empty($data["data"]))
            $this->setData($data["data"]);

        if(! empty($data["date"]))
            $this->setDate($data["date"]);

        if(! empty($data["sandbox"]))
            $this->setSandbox($data["sandbox"]);

        return $this;
    }

    /**
     * Get the 15 oldest events pending processing.
     * @param array $options
     * @return array
     */
    public static function pull($options = array())
    {
        $request = new RequestProcessoutPrivate($this->instance);
        $path    = "/events";

        $data = array(

        );

        $response = new Response($request->get($path, $data, $options));
        $a    = array();
        $body = $response->getBody();
        foreach($body['events'] as $v)
        {
            $tmp = new Event($this->instance);
            $tmp->fillWithData($v);
            $a[] = $tmp;
        }

        return $a;
    }

    /**
     * Set all the events as processed.
     * @param array $options
     * @return bool
     */
    public static function setAllProcessed($options = array())
    {
        $request = new RequestProcessoutPrivate($this->instance);
        $path    = "/events";

        $data = array(

        );

        $response = new Response($request->delete($path, $data, $options));
        return $response->isSuccess();
        
    }

    /**
     * Get the information related to the specific event.
	 * @param string $id
     * @param array $options
     * @return $this
     */
    public static function find($id, $options = array())
    {
        $request = new RequestProcessoutPrivate($this->instance);
        $path    = "/events/" . urlencode($id) . "";

        $data = array(

        );

        $response = new Response($request->get($path, $data, $options));
        $body = $response->getBody();
        $body = $body['event'];
        return $this->fillWithData($body);
        
    }

    /**
     * Set the specific event as processed.
     * @param array $options
     * @return bool
     */
    public function markProcessed($options = array())
    {
        $request = new RequestProcessoutPrivate($this->instance);
        $path    = "/events/" . urlencode($this->getId()) . "";

        $data = array(

        );

        $response = new Response($request->delete($path, $data, $options));
        return $response->isSuccess();
        
    }

    
}
