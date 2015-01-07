<?php

namespace app\controllers;

use Yii;
use app\models\DcmdService;
use app\models\DcmdServicePool;
use app\models\DcmdServiceSearch;
use app\models\DcmdServicePoolSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
/**
 * DcmdServiceController implements the CRUD actions for DcmdService model.
 */
class DcmdServiceController extends Controller
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
        ];
    }

    /**
     * Lists all DcmdService models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdServiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdService model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        ///$query = DcmdServicePool::find()->andWhere(['svr_id'=>$id]);
        $searchModel = new DcmdServicePoolSearch();
        $con = array();
        $con['DcmdServicePoolSearch'] = array('svr_id' => $id);
        if(array_key_exists('DcmdServicePoolSearch', Yii::$app->request->queryParams))
          $con = array_merge($con,Yii::$app->request->queryParams);
        $dataProvider = $searchModel->search($con);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new DcmdService model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($app_id)
    {
        $model = new DcmdService();
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = 100;
          $model->owner = $model->opr_uid;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->svr_id]);
        } else {
            $model->app_id = $app_id;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DcmdService model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = 100;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->svr_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdService model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $app_id=NULL)
    {

      $node = DcmdServicePool::find()->where(['svr_id' => $id])->one();
      if($node) {
        Yii::$app->getSession()->setFlash('error', '服务池子不为空,不可删除!');
      }else {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
      }
      if ($app_id) {
        return $this->redirect(array('dcmd-app/view', 'id'=>$app_id));
      }
      return $this->redirect(['index']);
    }

    /**
     * Finds the DcmdService model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdService the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdService::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
