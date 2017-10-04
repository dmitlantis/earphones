<?php

class QueryCriteria
{

    protected $index;
    protected $where = [];
    protected $select = [];
    protected $params = [];

    public static function create():self
    {
        return new static;
    }

    public function setSelect(array $select)
    {
        $this->select = $select;
        return $this;
    }

    public function setIndex(string $index)
    {
        $this->index = $index;
        return $this;
    }

    public function addWhere($where)
    {
        $this->where[] = $where;
        return $this;
    }

    public function addParam(string $attribute, $value, $operator = '=')
    {
        $this->params[] = $value;
        $this->where[] = "$attribute $operator" . '$' . count($this->params);
        return $this;
    }

    public function addInCondition(string $attribute, array $values)
    {
        return $this->addWhere("$attribute in (" . implode(',', $values) . ')');
    }

    public function addLikeCondition(string $attribute, string $like)
    {
        $this->params[] = $like;
        return $this->addWhere($attribute . '~~ $' . count($this->params));
    }


    public function getSelect()
    {
        return empty($this->select) ? '*' : implode(',', $this->select);
    }

    public function getWhere()
    {
        return implode(' and ' , $this->where);
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function addNotInCondition(string $attribute, array $values)
    {
        return $this->addWhere("not $attribute in (" . implode(',', $values) . ')');
    }

}