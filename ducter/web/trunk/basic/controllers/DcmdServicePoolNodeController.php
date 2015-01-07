<?php

namespace app\controllers;

use Yii;
use app\models\DcmdNodeGroup;
use app\models\DcmdNode;
use app\models\DcmdApp;
use app\models\DcmdService;
use app\models\DcmdServicePool;
use yii\data\ActiveDataProvider;
use app\models\DcmdNodeSearch;
use app\models\DcmdNodeGroupSearch;
use app\models\DcmdServicePoolNode;
use app\models\DcmdServicePoolNodeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdServicePoolNodeController implements the CRUD actions for DcmdServicePoolNode model.
 */
class DcmdServicePoolNodeController extends Controller
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
     * Lists all DcmdServicePoolNode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = DcmdApp::find()->orderBy("app_alias")->asArray()->all();
        $app = array();
        foreach($query as $item) $app[$item['app_id']] = $item['app_alias'];
        $query = DcmdService::find()->orderBy("svr_alias")->asArray()->all();
        $svr = array();
        foreach($query as $item) $svr[$item['svr_id']] = $item['svr_alias'];
        $query = DcmdServicePool::find()->orderBy("svr_pool")->asArray()->all();
        $svr_pool = array();
        foreach($query as $item) $svr_pool[$item['svr_pool_id']] = $item['svr_pool'];
        $searchModel = new DcmdServicePoolNodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'app' => $app,
            'svr' => $svr,
            'svr_pool' => $svr_pool,
        ]);
    }

    /**
     * Displays a single DcmdServicePoolNode model.
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
     * Creates a new DcmdServicePoolNode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    private function actionCreate()
    {
        $model = new DcmdServicePoolNode();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Select node group model.
     */
   public function actionSelectNodeGroup($app_id, $svr_id, $svr_pool_id) 
   {
       $searchModel = new DcmdNodeGroupSearch();
       $dataProvider = $searchModel->search(array());
       return $this->render('select_node_group', [
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
          'app_id' => $app_id,
          'svr_id' => $svr_id,
          'svr_pool_id' => $svr_pool_id,
       ]);
   }

   public function actionShowNodeList() {
        $app_id = Yii::$app->request->post()["app_id"];
        $svr_id = Yii::$app->request->post()["svr_id"];
        $svr_pool_id = Yii::$app->request->post()["svr_pool_id"];
        $node_group = array();
        $exist_nid = array();
        if (array_key_exists("selection", Yii::$app->request->post())) { 
          $node_group = Yii::$app->request->post()["selection"];
          $pool_node = DcmdServicePoolNode::find()->where(["svr_pool_id"=>$svr_pool_id])->asArray()->all();
          foreach($pool_node as $item) array_push($exist_nid, $item["nid"]);
        }
        $ngroups = "ngroup_id in (0";
        foreach($node_group as $key=>$value) $ngroups = $ngroups.",".$value;
        $ngroups = $ngroups.")";
        $ngroups = $ngroups." and nid not in (0";
        foreach($exist_nid as $k=>$v) $ngroups = $ngroups.",".$v;
        $ngroups = $ngroups.")";
        $query = DcmdNode::find()->where($ngroups);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $searchModel = new DcmdNodeSearch();
        return $this->render('show-node-list', [
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
          'app_id' => $app_id,
          'svr_id' => $svr_id,
          'svr_pool_id' => $svr_pool_id,
       ]);
        
   }
  
   public function actionAddNode() {
     $app_id = Yii::$app->request->post()["app_id"];
     $svr_id = Yii::$app->request->post()["svr_id"];
     $svr_pool_id = Yii::$app->request->post()["svr_pool_id"];
     $success_msg = "未选择设备";
     if (array_key_exists("selection", Yii::$app->request->post())){
       $success_msg = "成功添加以下设备:";
       $query = DcmdNode::find(Yii::$app->request->post()["selection"])->asArray()->all();
       $tm =  date('Y-m-d H:i:s');
       foreach($query as $item) {
         $server_pool_node = new DcmdServicePoolNode();
         $server_pool_node->svr_pool_id = $svr_pool_id;
         $server_pool_node->svr_id = $svr_id;
         $server_pool_node->app_id = $app_id;
         $server_pool_node->nid = $item['nid'];
         $server_pool_node->ip = $item['ip'];
         $server_pool_node->utime = $tm;
         $server_pool_node->ctime = $tm;
         $server_pool_node->opr_uid = 100;
         $server_pool_node->save();
         $success_msg .= $item['ip']." ";
       } 
     }
     Yii::$app->getSession()->setFlash('success', $success_msg);
     return $this->redirect(array('dcmd-service-pool/view','id'=>$svr_pool_id));;
   }
    /**
     * Updates an existing DcmdServicePoolNode model.
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
     * Deletes an existing DcmdServicePoolNode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $svr_pool_id=NULL)
    {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        if($svr_pool_id) return $this->redirect(['dcmd-service-pool/view', 'id'=>$svr_pool_id]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the DcmdServicePoolNode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdServicePoolNode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdServicePoolNode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
