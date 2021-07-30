<?php
/**
 * Created by : PhpStorm
 * User: Sin Lee
 * Date: 2021/7/30
 * Time: 16:27
 */


namespace Fastswoole\model\relation;


use Fastswoole\model\Model;

class HasOne extends Relation
{
    public $parent;
    public $model;
    public $foreignKey;
    public $localKey;
    public $query;

    public function __construct(Model $parent, string $model, string $foreignKey, string $localKey)
    {
        parent::__construct($parent, $model, $foreignKey, $localKey);
    }

}
