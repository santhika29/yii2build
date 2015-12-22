<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Profile;
use frontend\models\search\ProfileSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\PermissionHelpers;
use common\models\RecordHelpers;
use yii\filters\AccessControl;

/**
 * ProfileController implements the CRUD actions for Profile model.
 */
class ProfileController extends Controller
{
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
                'only' => ['index', 'view','create', 'update', 'delete'],
                'rules' => [
                        [
                        'actions' => ['index', 'view','create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'access2' => [
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

    /**
     * Lists all Profile models.
     * @return mixed
     */
    public function actionIndex()
    {
        if ($already_exists = RecordHelpers::userHas('profile')) {
            
        return $this->render('view', [
            
            'model' => $this->findModel($already_exists),
        ]);
                
        } else {
               
            return $this->redirect(['create']);     
                
        }   
    }

    /**
     * Displays a single Profile model.
     * @param string $id
     * @return mixed
     */
    public function actionView()
    {
        if($already_exists=RecordHelpers::userHas('profile')){
            return $this->render('view',['model'=>$this->findModel($already_exists),]);
        }else{
            return $this->redirect(['create']);
        }
    }

    /**
     * Creates a new Profile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Profile;
          
        $model->user_id = \Yii::$app->user->identity->id;      
        
        if ($already_exists = RecordHelpers::userHas('profile')) {
              
            return $this->render('view', [
                    
                'model' => $this->findModel($already_exists),
            ]);
        
        } elseif ($model->load(Yii::$app->request->post()) && $model->save()){
                            
            return $this->redirect(['view']);
                            
        } else {
                    
            return $this->render('create', [
            
              'model' => $model,
                    
            ]);
        }
    }

    /**
     * Updates an existing Profile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        PermissionHelpers::requireUpgradeTo('Paid');

        if($model =  Profile::find()->where(['user_id' => Yii::$app->user->identity->id])->one()) {
        
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                
                return $this->redirect(['view']);
            
            } else {
                
                return $this->render('update', ['model' => $model,]);
            }
        } else {
                
                throw new NotFoundHttpException('No Such Profile.');       
        }
    }

    /**
     * Deletes an existing Profile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = Profile::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
        $this->findModel($model->id)->delete();
        return $this->redirect(['site/index']);
    }

    /**
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Profile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
