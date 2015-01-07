<?php

namespace app\controllers;

use Yii;
use app\models\DcmdGroup;
use app\models\DcmdNode;
use app\models\DcmdNodeSearch;
use app\models\DcmdNodeGroup;
use app\models\DcmdNodeGroupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdNodeGroupController implements the CRUD actions for DcmdNodeGroup model.
 */
class DcmdNodeGroupController extends Controller
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
     * Lists all DcmdNodeGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdNodeGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $ret = DcmdGroup::findBySql("select gid,gname from dcmd_group")->asArray()->all();
        $groupId = array();
        foreach($ret as $gid) {
         $groupId[$gid['gid']] = $gid['gname']; 
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'groupId' => $groupId,
        ]);
    }

    /**
     * Displays a single DcmdNodeGroup model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new DcmdNodeSearch();
        $searchModel->ngroup_id = $id;
        $params = Yii::$app->request->queryParams;
        $params["DcmdNodeSearch"]["ngroup_id"] = $id; 
        $params["DcmdNodeSearch"]["rack"] = "";
       $dataProvider = $searchModel->search($params);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'ngroup_id' => $id,
        ]);
    }

    /**
     * Creates a new DcmdNodeGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DcmdNodeGroup();
        $groups = $this->getGroups();
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = date('Y-m-d H:i:s');
          $model->opr_uid = 100;
        } 
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ngroup_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'groups' => $groups,
            ]);
        }
    }

    /**
     * Updates an existing DcmdNodeGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $groups = $this->getGroups();
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = 100;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ngroup_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'groups' => $groups,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdNodeGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $node = DcmdNode::find()->where(['ngroup_id' => $id])->one();
        if($node) {
          Yii::$app->getSession()->setFlash('error', '设备池子不为空,不可删除!');
        }else {
          $this->findModel($id)->delete();
          Yii::$app->getSession()->setFlash('success', '删除成功!');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the DcmdNodeGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdNodeGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdNodeGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     *Get gid-gname
     */
    protected function getGroups() {
        $ret = DcmdGroup::findBySql("select gid, gname from dcmd_group")->asArray()->all();
        $groupId = array();
        foreach($ret as $gid) {
         $groupId[$gid['gid']] = $gid['gname'];
        }
        return $groupId;
    }
}
