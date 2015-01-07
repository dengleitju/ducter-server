<?php

namespace app\controllers;

use Yii;
use app\models\DcmdServicePool;
use app\models\DcmdServicePoolSearch;
use app\models\DcmdServicePoolNode;
use app\models\DcmdServicePoolNodeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
/**
 * DcmdServicePoolController implements the CRUD actions for DcmdServicePool model.
 */
class DcmdServicePoolController extends Controller
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
     * Lists all DcmdServicePool models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdServicePoolSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdServicePool model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new DcmdServicePoolNodeSearch();
        $con = array();
        $con['DcmdServicePoolNodeSearch'] = array('svr_pool_id'=>$id);
        if(array_key_exists('DcmdServicePoolNodeSearch', Yii::$app->request->queryParams))
           $con = array_merge($con, Yii::$app->request->queryParams);
        $dataProvider = $searchModel->search($con);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new DcmdServicePool model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($app_id, $svr_id)
    {
        $model = new DcmdServicePool();
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = 100;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->svr_pool_id]);
        } else {
            $model->app_id = $app_id;
            $model->svr_id = $svr_id;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DcmdServicePool model.
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
            return $this->redirect(['view', 'id' => $model->svr_pool_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdServicePool model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $svr_id=NULL)
    {
        $node = DcmdServicePoolNode::find()->where(['svr_pool_id' => $id])->one();
        if($node) {
          Yii::$app->getSession()->setFlash('error', '池子设备不为空,不可删除!');
        }else {
          $this->findModel($id)->delete();
          Yii::$app->getSession()->setFlash('success', '删除成功!');
        }
        if ($svr_id) {
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the DcmdServicePool model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdServicePool the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdServicePool::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
