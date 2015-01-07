<?php

namespace app\controllers;

use Yii;
use app\models\DcmdServicePoolSearch;
use app\models\DcmdService;
use app\models\DcmdServicePool;
use app\models\DcmdTaskTemplate;
use app\models\DcmdTaskTemplateServicePool;
use app\models\DcmdTaskTemplateServicePoolSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
/**
 * DcmdTaskTemplateServicePoolController implements the CRUD actions for DcmdTaskTemplateServicePool model.
 */
class DcmdTaskTemplateServicePoolController extends Controller
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
     * Lists all DcmdTaskTemplateServicePool models.
     * @return mixed
     */
    private function actionIndex()
    {
        $searchModel = new DcmdTaskTemplateServicePoolSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdTaskTemplateServicePool model.
     * @param integer $id
     * @return mixed
     */
    private function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    

    /**
     * Creates a new DcmdTaskTemplateServicePool model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($task_tmpt_id)
    {
        if(Yii::$app->request->post()) {
          $tm = date('Y-m-d H:i:s');
          if(array_key_exists("selection", Yii::$app->request->post())) {
            foreach(Yii::$app->request->post()['selection'] as $k => $v) {
              $dcmd_task_template_service_pool = new DcmdTaskTemplateServicePool();
              $dcmd_task_template_service_pool->task_tmpt_id = $task_tmpt_id;
              $dcmd_task_template_service_pool->utime = $tm;
              $dcmd_task_template_service_pool->ctime = $tm;
              $dcmd_task_template_service_pool->opr_uid = 100;
              $dcmd_task_template_service_pool->svr_pool_id = $v;
              $dcmd_task_template_service_pool->save();
            }
            Yii::$app->getSession()->setFlash('success', "添加服务池子成功!");
            return $this->redirect(['dcmd-task-template/view', 'id' => $task_tmpt_id]);  
          }
        }
        $model = new DcmdTaskTemplateServicePool();
        $query = DcmdTaskTemplate::findOne($task_tmpt_id);
        $svr_id = 0;
        if ($query) $svr_id = $query['svr_id'];
        ///获取未添加的服务池子列表
        $query = DcmdTaskTemplateServicePool::find()->andWhere(['task_tmpt_id'=>$task_tmpt_id])->asArray()->all();
        $exist_svr_pool = "svr_pool_id not in ( 0";
        if($query) {
         foreach($query as $item) $exist_svr_pool .= ",".$item['svr_pool_id'];
        }
        $exist_svr_pool .= ")";
        $query = DcmdServicePool::find()->where($exist_svr_pool);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $searchModel = new DcmdServicePoolSearch();
        ///获取任务模板名称
        $task_tmpt_name = "";
        $query = DcmdTaskTemplate::findOne($task_tmpt_id);
        if($query) $task_tmpt_name = $query['task_tmpt_name'];
        $model->task_tmpt_id = $task_tmpt_id;
        return $this->render('create', [
              'model' => $model,
              'task_tmpt_id' => $task_tmpt_id,
              'task_tmpt_name' => $task_tmpt_name,
              'searchModel' => $searchModel,
              'dataProvider' => $dataProvider, 
            ]);
    }

    /**
     * Updates an existing DcmdTaskTemplateServicePool model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    private function actionUpdate($id)
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
     * Deletes an existing DcmdTaskTemplateServicePool model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $task_tmpt_id)
    {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(['dcmd-task-template/view', 'id' => $task_tmpt_id]);
    }

    /**
     * Finds the DcmdTaskTemplateServicePool model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdTaskTemplateServicePool the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdTaskTemplateServicePool::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
