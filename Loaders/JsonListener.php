<?php

/**
 * This Listener is a part of implementation of salsify/json-streaming-parser
 */

namespace Neelkanthk\EsLoader\Loaders;

use JsonStreamingParser\Listener\ListenerInterface;

class JsonListener implements ListenerInterface
{

    private $_json;
    private $_stack;
    private $_key;

    public function getJson()
    {
        return $this->_json;
    }

    public function startDocument(): void
    {
        $this->_stack = array();
        $this->_key = null;
    }

    public function endDocument(): void
    {
        // w00t!
    }

    public function startObject(): void
    {
        array_push($this->_stack, array());
    }

    public function endObject(): void
    {
        $obj = array_pop($this->_stack);
        if (empty($this->_stack)) {
            // doc is DONE!
            $this->_json = $obj;
        } else {
            $this->value($obj);
        }
    }

    public function startArray(): void
    {
        $this->startObject();
    }

    public function endArray(): void
    {
        $this->endObject();
    }

    // Key will always be a string
    public function key(string $key): void
    {
        $this->_key = $key;
    }

    // Note that value may be a string, integer, boolean, null
    public function value($value): void
    {
        $obj = array_pop($this->_stack);
        if ($this->_key) {
            $obj[$this->_key] = $value;
            $this->_key = null;
        } else {
            array_push($obj, $value);
        }
        array_push($this->_stack, $obj);
    }

    public function whitespace(string $whitespace): void
    {
        
    }

}
