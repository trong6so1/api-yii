<?php
namespace console\rule;
use yii\rbac\Rule;

class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    /**
     * @inheritDoc
     */
    public function execute($user, $item, $params): bool
    {
        if(isset($params['post'])){
            return $params['post']->created_by == $user;
        }
        if(isset($params['product'])){
            return $params['product']->created_by == $user;
        }
        return false;
    }
}