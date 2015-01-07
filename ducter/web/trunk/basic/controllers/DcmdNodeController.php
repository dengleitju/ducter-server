<?php

namespace app\controllers;

use Yii;
use app\models\DcmdNode;
use app\models\DcmdServicePoolNode;
use app\models\DcmdNodeGroup;
use app\models\DcmdNodeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdNodeController implements the CRUD actions for DcmdNode model.
 */
class DcmdNodeController extends Controller
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
     * Lists all DcmdNode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdNodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdNode model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DcmdNode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($ngroup_id)
    {
        $model = new DcmdNode();
        $ret = DcmdNodeGroup::findOne($ngroup_id);
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = 100;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->nid]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'node_group' => array($ngroup_id=>$ret->ngroup_name),
            ]);
        }
    }

    /**
     * Updates an existing DcmdNode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $node_group = $this->getDcmdNodeGroup();
        if(Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = 100;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->nid]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'node_group' => $node_group,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdNode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $ngroup_id=NULL)
    {
        $node = DcmdServicePoolNode::find()->where(['nid' => $id])->one();
        if($node) {
          Yii::$app->getSession()->setFlash('error', '有服务池子使用该设备,不可删除!');
        }else {
          $this->findModel($id)->delete();
          Yii::$app->getSession()->setFlash('success', '删除成功!');
        }

        if($ngroup_id == NULL) {
         return $this->redirect(['index']);
        }else{
         return $this->redirect(array('dcmd-node-group/view', 'id'=>$ngroup_id)); 
        }
    }

    /**
     * Finds the DcmdNode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdNode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdNode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     *Get dcmd_node_group list
     */
    protected  function getDcmdNodeGroup() {
      $group = array();
      $ret = DcmdNodeGroup::findBySql("select ngroup_id, ngroup_name from dcmd_node_group")->asArray()->all();
      foreach($ret as $g) {
        $group[$g['ngroup_id']] = $g['ngroup_name'];
      }
      return $group;
    }
}
