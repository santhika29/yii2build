<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\PermissionHelpers;
use common\models\RecordHelpers;
use frontend\models\Profile;

class UpgradeController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $name = Profile::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
        return $this->render('index', ['name' => $name]);
    }

    public function behaviors()
    {
    	return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' =>[
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {
                            return PermissionHelpers::requireStatus('Active');
                        }
                    ],
                ],
            ],
        ];
    }
}
