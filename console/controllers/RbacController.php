<?php

namespace console\controllers;

use console\rule\AuthorRule;
use Yii;
use yii\base\Exception;
use yii\console\Controller;

class RbacController extends Controller
{
    /**
     * @throws Exception
     * @throws \Exception
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $author = $auth->createRole('author');
        $auth->add($author);

        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $rule = new AuthorRule();
        $auth->add($rule);

        $create = $auth->createPermission('create');
        $create->description = 'Create';
        $auth->add($create);

        $update = $auth->createPermission('update');
        $update->description = 'Update';
        $auth->add($update);

        $ownAuthor = $auth->createPermission('ownAuthor');
        $ownAuthor->description = 'Own Author';
        $ownAuthor->ruleName = $rule->name;
        $auth->add($ownAuthor);

        $auth->addChild($admin, $author);
        $auth->addChild($admin, $update);
        $auth->addChild($author, $ownAuthor);
        $auth->addChild($author, $create);
        $auth->addChild($ownAuthor, $update);
    }
}