<?php namespace BookStack\Orz\Criteria;

use BookStack\Orz\Contract\RepositoryInterface;

abstract class Criteria {
    
    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public abstract function apply($model, RepositoryInterface $repositoryInterface);
}