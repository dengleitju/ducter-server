<?php

namespace app\controllers;

use Yii;
use app\models\DcmdGroup;
use app\models\DcmdUserGroup;
use app\models\DcmdGroupSearch;
use app\models\DcmdUserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdGroupController implements the CRUD actions for DcmdGroup model.
 */
class DcmdGroupController extends Controller
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
     * Lists all DcmdGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdGroup model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
       /* $query = DcmdUserGroup::find()->andWhere("gid=".$id)->asArray()->all();
        $users = "uid in (0"; 
        foreach($query as $item) $users = $users.",".$item['uid'];
        $users .= ")";
        $searchModel = new DcmdUserSearch();
        $dataProvider = $searchModel->search(array(), $users);
*/
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DcmdGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DcmdGroup();
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = 100;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->gid]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DcmdGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = 100;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->gid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        /*$node = DcmdUserGroup::find()->where(['gid' => $id])->one();
        if($node) {
          Yii::$app->getSession()->setFlash('error', '用户组内有用户,不可删除!');
        }else {
          $this->findModel($id)->delete();
          Yii::$app->getSession()->setFlash('success', '删除成功!');
        }*/
        DcmdUserGroup::deleteAll("gid=".$id);
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(['index']);
    }
  
    public function actionUpdateUser($id)
    {

    }

    /**
     * Finds the DcmdGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
