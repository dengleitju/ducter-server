<?php

namespace app\controllers;

use Yii;
use app\models\DcmdTaskCmdArg;
use app\models\DcmdTaskCmdArgSearch;
use app\models\DcmdTaskCmd;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdTaskCmdArgController implements the CRUD actions for DcmdTaskCmdArg model.
 */
class DcmdTaskCmdArgController extends Controller
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
     * Lists all DcmdTaskCmdArg models.
     * @return mixed
     */
    private function actionIndex()
    {
        $searchModel = new DcmdTaskCmdArgSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdTaskCmdArg model.
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
     * Creates a new DcmdTaskCmdArg model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($task_cmd_id)
    {
        $query = DcmdTaskCmd::findOne($task_cmd_id);
        $model = new DcmdTaskCmdArg();
        $model->task_cmd_id = $task_cmd_id;
        $model->task_cmd = $query['task_cmd'];
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = 100;
        } 
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', '添加成功!');
            return $this->redirect(['dcmd-task-cmd/view', 'id' => $model->task_cmd_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'task_cmd_id' => $task_cmd_id,
            ]);
        }
    }

    /**
     * Updates an existing DcmdTaskCmdArg model.
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
            Yii::$app->getSession()->setFlash('success', '修改成功!');
            return $this->redirect(['dcmd-task-cmd/view', 'id' => $model->task_cmd_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdTaskCmdArg model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $task_cmd_id)
    {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(['dcmd-task-cmd/view',  'id' => $task_cmd_id]);
    }

    /**
     * Finds the DcmdTaskCmdArg model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdTaskCmdArg the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdTaskCmdArg::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
