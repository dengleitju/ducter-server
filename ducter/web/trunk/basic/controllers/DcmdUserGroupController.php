<?php

namespace app\controllers;

use Yii;
use app\models\DcmdUserGroup;
use app\models\DcmdUserSearch;
use app\models\DcmdGroup;
use app\models\DcmdUserGroupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdUserGroupController implements the CRUD actions for DcmdUserGroup model.
 */
class DcmdUserGroupController extends Controller
{
    public $enableCsrfValidation = false;
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
     * Lists all DcmdUserGroup models.
     * @return mixed
     */
    public function actionIndex($gid)
    {
        $searchModel = new DcmdUserGroupSearch(array('gid'=>$gid));
        $dataProvider = $searchModel->search(array('gid'=>$gid));///Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'gid' => $gid,
            'gname' => $this->getGroupName($gid),
        ]);
    }
   
    /**
     * Displays a single DcmdUserGroup model.
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
     * Creates a new DcmdUserGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($gid)
    {
        $query = DcmdUserGroup::find()->andWhere(["gid"=>$gid,])->asArray()->all();
        $uids = "uid not in (0";
        foreach($query as $item) $uids .=",".$item['uid'];
        $uids .= ")";
        
        $searchModel = new DcmdUserSearch();
        $dataProvider = $searchModel->search(array(), $uids);

        return $this->render('create', [
             'gid' => Yii::$app->request->queryParams['gid'],
             'gname' => Yii::$app->request->queryParams['gname'],
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
 
    }

    public function actionAdd() {
        $gid = Yii::$app->request->post()["gid"];
	$gname =  Yii::$app->request->post()["gname"];
        $uid_array = array();
        $success_msg = "未选择用户";
        if (array_key_exists("selection", Yii::$app->request->post())){ 
           $success_msg = "添加用户成功:";  
           $tm =  date('Y-m-d H:i:s');
           foreach(Yii::$app->request->post()["selection"] as $k=>$v) {
             $dcmd_user_group = new DcmdUserGroup();
             $dcmd_user_group->uid = $v;
             $dcmd_user_group->gid = $gid;
             $dcmd_user_group->comment = "comment";
             $dcmd_user_group->utime = $tm;
             $dcmd_user_group->ctime = $tm;
             $dcmd_user_group->opr_uid = 100;
             $dcmd_user_group->save();
             $success_msg .= $v." ";
           }
        }
        Yii::$app->getSession()->setFlash('success', $success_msg);
        return $this->redirect(array('dcmd-user-group/index','gid'=>$gid)); 
    }
    /**
     * Updates an existing DcmdUserGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdUserGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
var_dump(Yii::$app->request->queryParams);exit;
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionRemove()
    {
      $gid = Yii::$app->request->queryParams["gid"];
      $this->findModel(Yii::$app->request->queryParams["id"])->delete();
      Yii::$app->getSession()->setFlash('success', '删除成功!');
      return $this->redirect(['index', 'gid'=>$gid]);
    }
    /**
     * Finds the DcmdUserGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdUserGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdUserGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
 
   protected function getGroupName($gid) 
   {
     $query = DcmdGroup::findOne($gid);
     if($query) return $query['gname'];
     return $gid;
   }

}
