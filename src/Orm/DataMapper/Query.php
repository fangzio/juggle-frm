<?php

namespace Juggle\Frm\Orm\DataMapper;

use Juggle\Frm\Exception\RuntimeException;
use Juggle\Frm\Orm\ConnectionManager;

class Query
{
    protected $datasource;
    protected $fields = [];
    protected $whereStr = '';
    protected $order = [];
    protected $group = [];
    protected $offset = 0;
    protected $limit = 10;

    public function select($datasource = null, $fields = '*')
    {
        if (is_array($fields)) {
            $fields = implode(',', $fields);
        }
        if (!is_string($fields)) {
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

    public function where($option, $value = null, $innerRelation = 'AND', $outerRelation = 'AND')
    {
        if (is_array($option)) {
            $this->parseWhereArr($option, $innerRelation, $outerRelation);
        } elseif (is_scalar($option)) {
            $this->parseWhereArr([$option => $value], $innerRelation, $outerRelation);
        }
        return $this;
    }

    protected function parseWhereArr($cond, $innerRelation = 'AND', $outerRelation = 'AND')
    {
        if (empty($cond) || !is_array($cond)) {
            return;
        }
        $whereMap = [];
        foreach ($cond as $f => $v) {
            $f = preg_replace('/s+/', ' ', $f);
            $fArr = explode(' ', $f);
            $field = array_shift($fArr);
            if (isset($fArr[0])) {
                $opr = strtoupper($fArr[0]);
                switch ($opr) {
                    case 'LIKE':
                        $tField = $field . ' LIKE ';
                        break;
                    case 'LT':
                    case '<':
                        $tField = $field . ' < ';
                        break;
                    case 'LE':
                    case '<=':
                        $tField = $field . ' <= ';
                        break;
                    case 'EQ':
                    case '=':
                        $tField = $field . ' = ';
                        break;
                    case 'GT':
                    case '>':
                        $tField = $field . ' > ';
                        break;
                    case 'GE':
                    case '>=':
                        $tField = $field . ' >= ';
                        break;
                    case 'NE':
                    case '!=':
                    case '<>':
                        $tField = $field . ' <> ';
                        break;
                    case 'IN':
                        $tField = $field . ' IN ';
                        break;
                    default:
                        $tField = $field . ' ' . $opr;
                        break;
                }
            } else {
                $tField = $f . ' = ';
            }
            $whereMap[$tField] = $this->parseValue($v);
        }
        $this->appendWhere($whereMap, $innerRelation, $outerRelation);
    }

    protected function parseValue($v)
    {
        if (is_scalar($v)) {
            return addslashes($v);
        }
        if (is_array($v)) {
            array_map('addslashes', $v);
            return '(' . implode('\',\'', $v) . ')';
        }
        return $v;
    }

    /**
     * 追加where条件
     * @param $fvMap
     * @param string $innerRelation
     * @param string $outerRelation
     */
    protected function appendWhere($fvMap, string $innerRelation = 'AND', string $outerRelation = 'OR')
    {
        if (empty($fvMap) || !is_array($fvMap)) {
            return;
        }
        if (!isset($this->whereStr)) {
            $this->whereStr = '';
        }
        $innerCond = [];

        foreach ($fvMap as $f => $v) {
            $innerCond[] = $f . $v;
        }
        $subWhere = implode(' ' . $innerRelation . ' ', $innerCond);

        if (empty($this->whereStr)) {
            $this->whereStr = '(' . $subWhere . ')';
        } else {
            $this->whereStr .= $outerRelation . ' (' . $subWhere . ') ';
        }
    }

    public function andWhere($cond, $value = null, $innerRelation = 'AND')
    {
        return $this->where($cond, $value, $innerRelation);
    }

    public function orWhere($cond, $value = null, $innerRelation = 'OR')
    {
        return $this->where($cond, $value, $innerRelation, 'OR');
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
        $db = 'default';
        $cm = new ConnectionManager();
        $handler = $cm->connection($db);
        $sql = $handler->parseQuery(
            $this->datasource,
            $this->fields,
            $this->whereStr,
            $this->order,
            $this->group,
            $this->offset,
            $this->limit
        );
        return $handler->query($sql);
    }
}