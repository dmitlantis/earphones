<?php

class QueryCriteria
{

    protected $index;
    protected $where = [];
    protected $select = [];
    protected $sort = [];

    const MERGE_MODE_AND = 'AND';
    const MERGE_MODE_OR  = 'OR';

    protected $mergeMode = 'and';

    public function __construct(string $mergeMode = self::MERGE_MODE_AND)
    {
        $this->mergeMode = $mergeMode;
    }

    public static function create(string $mergeMode = self::MERGE_MODE_AND):self
    {
        return new static($mergeMode);
    }

    public function select(array $select)
    {
        $this->select = $select;
        return $this;
    }

    public function index(string $index)
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @param array|string|self $where
     * @return $this
     */
    public function where($where)
    {
        $this->where[] = $where;
        return $this;
    }

    public function param(string $attribute, $value, $operator = '=')
    {
        $this->where[] = ["$attribute $operator", $value];
        return $this;
    }

    public function IN(string $attribute, array $values)
    {
        return $this->where("$attribute in (" . implode(',', $values) . ')');
    }

    public function like(string $attribute, string $like, bool $caseInsensitive = true)
    {
        return $this->where([$attribute . '~~' . ($caseInsensitive ? '*' : '') . ' ', $like]);
    }

    public function regexp(string $attribute, string $regexp, bool $caseInsensitive = true)
    {
        return $this->where([$attribute . '~' . ($caseInsensitive ? '*' : ''), $regexp]);
    }

    public function isNull(string $attribute)
    {
        return $this->where("$attribute is null");
    }

    public function sort(string $fieldName, $asc = true)
    {
        $this->sort[] = $fieldName . ($asc ? ' asc' : ' desc');
        return $this;
    }


    public function getSelect()
    {
        return empty($this->select) ? '*' : implode(',', $this->select);
    }

    public function getWhere(&$params = []):string
    {
        if (empty($this->where)) {
            return '';
        }
        $where = [];

        foreach ($this->where as $part) {
            if (is_array($part)) {
                $wherePart = '';
                foreach ($part as $key => $value) {
                    if ($key % 2 == 0) {
                        $wherePart .= $value;
                    } else {
                        $params[] = $value;
                        $wherePart .= ' $' . count($params) . ' ';
                    }
                }
                $where[] = $wherePart;
            } elseif(is_object($part) && $part instanceof self) {
                $where[] = '(' . $part->getWhere($params) . ')';
            } else {
                $where[] = $part;
            }
        }
        return implode(" $this->mergeMode ", $where);
    }

    public function getSort():string
    {
        return implode(',', $this->sort);
    }

    public function getWhereRaw():array
    {
        return $this->where;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    public function addNotInCondition(string $attribute, array $values)
    {
        return $this->where("not $attribute in (" . implode(',', $values) . ')');
    }

}