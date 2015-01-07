<?php

namespace app\controllers;

use Yii;
use app\models\DcmdUser;
use app\models\DcmdUserGroup;
use app\models\DcmdDepartment;
use app\models\DcmdUserSearch;
use app\models\DcmdGroupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdUserController implements the CRUD actions for DcmdUser model.
 */
class DcmdUserController extends Controller
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
     * Lists all DcmdUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdUser model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $query = DcmdUserGroup::find()->andWhere("uid=".$id)->asArray()->all();
        $gids = "gid in (0";///array(0,); 
        foreach($query as $item) $gids = $gids.",".$item['gid'];////array_push($gids ,$item['gid']);
        $gids = $gids.")";
        $searchModel = new DcmdGroupSearch();
        $dataProvider = $searchModel->search(array(), $gids);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new DcmdUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DcmdUser();
        $ret = DcmdDepartment::find()->asArray()->all();
        $depart = array();
        foreach($ret as $item) {
         $depart[$item['depart_id']] = $item['depart_name'];
        }
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->state = 0;
          $model->passwd = md5("123456"+Yii::$app->request->post()["DcmdUser"]["username"]+$model->ctime);
          $model->opr_uid = 100;
          Yii::$app->getSession()->setFlash('success', '添加用户成功,初始密码:123456');
        } 
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->uid, 'depart'=>$depart]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'depart' => $depart,
            ]);
        }
    }

    /**
     * Updates an existing DcmdUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $ret = DcmdDepartment::find()->asArray()->all();
        $depart = array();
        foreach($ret as $item) {
         $depart[$item['depart_id']] = $item['depart_name'];
        }
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = 100;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->uid]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'depart' => $depart,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        ///delete from dcmd_user_group
        DcmdUserGroup::deleteAll("uid=".$id);
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(['index']);
    }

    /**
     * Finds the DcmdUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
