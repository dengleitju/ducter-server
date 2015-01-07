<?php

namespace app\controllers;

use Yii;
use app\models\DcmdApp;
use app\models\DcmdDepartment;
use app\models\DcmdService;
use app\models\DcmdServiceSearch;
use app\models\DcmdGroup;
use app\models\DcmdAppSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
/**
 * DcmdAppController implements the CRUD actions for DcmdApp model.
 */
class DcmdAppController extends Controller
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
     * Lists all DcmdApp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = DcmdGroup::find()->asArray()->all();
        $sys = array();
        $svr = array();
        foreach($query as $item) {
          if($item['gtype'] == 1) $sys[$item['gid']] = $item['gname'];
          else $svr[$item['gid']] = $item['gname'];
        }
        $query = DcmdDepartment::find()->asArray()->all();
        $depart = array();
        foreach($query as $item) $depart[$item['depart_id']] = $item['depart_name'];
        $searchModel = new DcmdAppSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sys' => $sys,
            'svr' => $svr,
            'depart' => $depart,
        ]);
    }

    /**
     * Displays a single DcmdApp model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $depart = $this->getDepart();
        $searchModel = new DcmdServiceSearch();
        $con = array();
        $con['DcmdServiceSearch'] = array('app_id'=>$id);
        if(array_key_exists('DcmdServiceSearch', Yii::$app->request->queryParams))
          $con = array_merge($con, Yii::$app->request->queryParams);
        $dataProvider = $searchModel->search($con);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new DcmdApp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DcmdApp();

        $depart = $this->getDepart();
        $user_group = $this->getUserGroup();
        $sys_user_group = $user_group["sys"];
        $svr_user_group = $user_group["svr"];
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = 100;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->app_id, 'sys_user_group' => $sys_user_group,
                'svr_user_group' => $svr_user_group, 'depart' => $depart,]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'sys_user_group' => $sys_user_group,
                'svr_user_group' => $svr_user_group,
                'depart' => $depart,
            ]);
        }
    }

    /**
     * Updates an existing DcmdApp model.
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
        $depart = $this->getDepart();
        $user_group = $this->getUserGroup();
        $sys_user_group = $user_group["sys"];
        $svr_user_group = $user_group["svr"];
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->app_id, 'sys_user_group' => $sys_user_group,
                'svr_user_group' => $svr_user_group, 'depart' => $depart,]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'sys_user_group' => $sys_user_group,
                'svr_user_group' => $svr_user_group,
                'depart' => $depart,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdApp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $node = DcmdService::find()->where(['app_id' => $id])->one();
        if($node) {
          Yii::$app->getSession()->setFlash('error', '该产品的服务不为空,不可删除!');
        }else {
          $this->findModel($id)->delete();
          Yii::$app->getSession()->setFlash('success', '删除成功!');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the DcmdApp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdApp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdApp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function getDepart() {
      $ret = DcmdDepartment::find()->asArray()->all();
      $depart = array();
      foreach($ret as $item) {
       $depart[$item['depart_id']] = $item['depart_name'];
      }
      return $depart;
   }
   protected function getUserGroup() {
     $ret = DcmdGroup::find()->asArray()->all();
     $user_group = array();
     $user_group['sys'] = array();
     $user_group['svr'] = array();
     foreach($ret as $item) {
      if($item['gtype'] == 1)
       $user_group['sys'][$item['gid']] = $item['gname'];
      else
       $user_group['svr'][$item['gid']] = $item['gname']; 
     }
     return $user_group;
  }
  public function userGroupName($gid) {
    $ret = DcmdUserGroup::findOne($gid);
    if($ret) return $ret['gname'];
    return "";
  }
  public function department($depart_id) {
   $ret = DcmdDepartment::findOne($depart_id);
   if ($ret) return $ret['depart_name'];
   return "";
  }
}
