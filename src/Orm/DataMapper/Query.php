<?php

namespace Juggle\Frm\Orm\DataMapper;

use Juggle\Frm\Exception\RuntimeException;

class Query
{
    public $datasource;
    public $fields = [];
    public $order = [];
    public $group = [];

    public function select($fields = '*', $datasource = null)
    {
        if (is_string($fields)) {
            $fields = explode(',', $fields);
        }
        if (!is_array($fields)) {
            throw new RuntimeException('fields is invalid!');
        }
        $this->fields = $fields;
        if ($datasource !== null) {
            $this->from($datasource);
        }
        return $this;
    }

    public function from($datasource = null)
    {
        $this->datasource = $datasource;
        return $this;
    }

    public function order($order = [])
    {
        $this->order = $order;
        return $this;
    }

    public function group($group = [])
    {
        $this->group = $group;
        return $this;
    }

    public function where()
    {

    }

    public function count()
    {

    }

    public function limit()
    {

    }

    public function offset()
    {

    }

    public function first()
    {

    }

    public function toArray()
    {

    }

    public function execute()
    {

    }
}