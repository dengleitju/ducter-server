<?php

namespace app\controllers;

use Yii;
use app\models\DcmdTaskHistory;
use app\models\DcmdTaskHistorySearch;
use app\models\DcmdService;
use app\models\DcmdApp;
use app\models\DcmdTaskServicePoolHistory;
use app\models\DcmdTaskServicePoolHistorySearch;
use app\models\DcmdTaskNodeHistory;
use app\models\DcmdCommandHistory;
use app\models\DcmdTaskNodeHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
include_once dirname(__FILE__)."/../common/dcmd_util_func.php";
/**
 * DcmdTaskHistoryController implements the CRUD actions for DcmdTaskHistory model.
 */
class DcmdTaskHistoryController extends Controller
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
     * Lists all DcmdTaskHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdTaskHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $svr = array();
        $query = DcmdService::find()->asArray()->all();
        foreach($query as $item) $svr[$item['svr_name']] = $item['svr_name'];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'svr' => $svr,
        ]);
    }

    /**
     * Displays a single DcmdTaskHistory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $task = $this->findModel($id);
       $query = DcmdService::findOne($task->svr_id);
       $app_id = $query->app_id;
       $query = DcmdApp::findOne($app_id);
       $app_name = $query->app_name;
       $ret = xml_to_array($task->task_arg);
       $args = "";
       if(is_array($ret['env']))
         foreach($ret['env'] as $k=>$v) $args .= $k.'='.$v." ; ";
    
       ///服务池子
        $query = DcmdTaskServicePoolHistory::find()->andWhere(['task_id'=>$id]);
        $svr_dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $svr_searchModel = new DcmdTaskServicePoolHistorySearch();
        ///未运行的任务
        $con = array('task_id'=>$id, 'state'=>0);
        $query = DcmdTaskNodeHistory::find()->andWhere($con)->orderBy("utime desc")->limit(5);
        $init_dataProvider = new ActiveDataProvider([
          'query' => $query,
        ]);
        $init_searchModel = new DcmdTaskNodeHistorySearch();
        ///在运行
        $con = array('task_id'=>$id, 'state'=>1);
        $query = DcmdTaskNodeHistory::find()->andWhere($con)->orderBy("utime desc")->limit(5);
        $run_dataProvider = new ActiveDataProvider([
           'query' => $query,
        ]);
        $run_searchModel = new DcmdTaskNodeHistorySearch();
        ///失败任务
        $con = array('task_id'=>$id, 'state'=>3);
        $query = DcmdTaskNodeHistory::find()->andWhere($con)->orderBy("utime desc")->limit(5);
        $fail_dataProvider = new ActiveDataProvider([
           'query' => $query,
        ]);
        $fail_searchModel = new DcmdTaskNodeHistorySearch();
        ///完成任务
        $con = array('task_id'=>$id, 'state'=>2);
        $query = DcmdTaskNodeHistory::find()->andWhere($con)->orderBy("utime desc")->limit(5);
        $suc_dataProvider = new ActiveDataProvider([
           'query' => $query,
        ]);
        $suc_searchModel = new DcmdTaskNodeHistorySearch();
        return $this->render('monitor', [
            'task' => $task,
            'app_id' => $app_id,
            'app_name' => $app_name,
            'args' => $args,
            'svr_dataProvider' => $svr_dataProvider,
            'svr_searchModel' => $svr_searchModel,
            'init_dataProvider' => $init_dataProvider,
            'init_searchModel' => $init_searchModel,
            'run_dataProvider' => $run_dataProvider,
            'run_searchModel' => $run_searchModel,
            'fail_dataProvider' => $fail_dataProvider,
            'fail_searchModel' => $fail_searchModel,
            'suc_dataProvider' => $suc_dataProvider,
            'suc_searchModel' => $suc_searchModel,
        ]);
    }

    /**
     * Creates a new DcmdTaskHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    private function actionCreate()
    {
        $model = new DcmdTaskHistory();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->task_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DcmdTaskHistory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    private function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->task_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdTaskHistory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        ///删除dcmd_task_service_pool_history
        DcmdTaskServicePoolHistory::deleteAll(['task_id'=>$id]);
        ///dcmd_task_node_history
        DcmdTaskNodeHistory::deleteAll(['task_id'=>$id]);
        ///dcmd_command_history
        DcmdCommandHistory::deleteAll(['task_id'=>$id]);
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', $id.'删除成功!');
        return $this->redirect(['index']);
    }

    /**
     * Finds the DcmdTaskHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdTaskHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdTaskHistory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
