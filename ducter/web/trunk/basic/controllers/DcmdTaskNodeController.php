<?php

namespace app\controllers;

use Yii;
use app\models\DcmdTaskNode;
use app\models\DcmdTaskNodeSearch;
use app\models\DcmdTaskServicePool;
use app\models\DcmdServicePoolNode;
use app\models\DcmdServicePoolNodeSearch;
use app\models\DcmdCenter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
require_once (dirname(__FILE__)."/../common/interface.php");
/**
 * DcmdTaskNodeController implements the CRUD actions for DcmdTaskNode model.
 */
class DcmdTaskNodeController extends Controller
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
     * Lists all DcmdTaskNode models.
     * @return mixed
     */
    public function actionIndex($task_id, $svr_pool, $state=NULL, $ignored=NULL)
    {
        $searchModel = new DcmdTaskNodeSearch();
        $con = array();
        $con['DcmdTaskNodeSearch'] =  array('task_id'=>$task_id, 'svr_pool'=>$svr_pool);
        if($state != NULL) $con['DcmdTaskNodeSearch']['state'] = $state;
        if($ignored != NULL) $con['DcmdTaskNodeSearch']['ignored'] = $ignored;
        if(array_key_exists('DcmdTaskNodeSearch', Yii::$app->request->queryParams))
          $con['DcmdTaskNodeSearch'] = Yii::$app->request->queryParams['DcmdTaskNodeSearch'] +  $con['DcmdTaskNodeSearch'];
        $dataProvider = $searchModel->search($con, 20);
        $query = DcmdTaskServicePool::find()->andWhere(['task_id'=>$task_id])->asArray()->all();
        $svr_pool = array();
        foreach($query as $item) $svr_pool[$item['svr_pool']] = $item['svr_pool']; 

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'task_id' => $task_id,
            'svr_pool' => $svr_pool,
        ]);
    }

    public function actionAdd($task_id)
    {
        $task_id = Yii::$app->request->queryParams['task_id'];
        $svr_pool_array = array();
        $query = DcmdTaskServicePool::find()->andWhere(['task_id'=>$task_id])->asArray()->all();
        foreach($query as $item) $svr_pool_array[$item['svr_pool_id']] = $item['svr_pool']; 
        if (Yii::$app->request->post() && array_key_exists('selection', Yii::$app->request->post())) {
          $ret_msg = "";
          $query = DcmdCenter::findOne(['master'=>1]);
          if ($query) {
            list($host, $port) = split(':', $query["host"]);
            var_dump(Yii::$app->request->post());///exit;
            $task_id = Yii::$app->request->post()['task_id'];
            $select = Yii::$app->request->post()['selection'];
            foreach($select as $k=>$v) {
              list($ip, $svr_pool_id) = explode(',', $v);
              $reply = execTaskCmd($host, $port, $task_id, 100, 6, NULL, $ip, NULL, $svr_pool_array[$svr_pool_id]);
              if($reply->getState() == 0) $ret_msg.= $ip.":添加成功 ";
              else $ret_msg .= $ip.":添加失败:".$reply->getErr()." ";
            }
          }else $ret_msg = "添加失败:无法获取Center!";
          Yii::$app->getSession()->setFlash('success',$ret_msg);
        }
        ///echo "===";
        ////var_dump(Yii::$app->request->queryParams);
        ///获取已经存在池子及对应的ip
        $query = DcmdTaskNode::find()->andWhere(['task_id'=>$task_id])->asArray()->all();
        $exist_svr_pool_ip = array();
        foreach($query as $item) {
          if(array_key_exists($item['svr_pool'], $exist_svr_pool_ip)) {
            array_push($exist_svr_pool_ip[$item['svr_pool']], $item['ip']);
          }else {
            $exist_svr_pool_ip[$item['svr_pool']] = array($item['ip']);
          }
        }
        ///限制服务池子
        $query_con = "(1 = 0)";
        if (array_key_exists('DcmdServicePoolNodeSearch', Yii::$app->request->queryParams) &&
            array_key_exists('svr_pool_id', Yii::$app->request->queryParams['DcmdServicePoolNodeSearch']) &&
            Yii::$app->request->queryParams['DcmdServicePoolNodeSearch']['svr_pool_id'] != ''){
              $query_con .= " or (svr_pool_id = ".$Yii::$app->request->queryParams['DcmdServicePoolNodeSearch']['svr_pool_id']." and ip not in (0";
              ///排除该服务池子对应的ip
              $svr_pool = $svr_pool_array($Yii::$app->request->queryParams['DcmdServicePoolNodeSearch']['svr_pool_id']);
              if(array_key_exists($svr_pool, $exist_svr_pool_ip)) {
                 foreach($exist_svr_pool_ip[$svr_pool] as $ip) $query_con .= ",'".$ip."'";
              }
              $query_con .= "))";
        } else {
          foreach($svr_pool_array as $svr_pool_id =>$svr_pool) {
            $query_con .= " or (svr_pool_id = ".$svr_pool_id." and ip not in (0"; 
            if(array_key_exists($svr_pool, $exist_svr_pool_ip))
              foreach($exist_svr_pool_ip[$svr_pool] as $ip) $query_con .= ",'".$ip."'";
            $query_con .= "))";
          }
       }
///echo $query_con;exit;
        ///排除已经存在的ip
        $query = DcmdServicePoolNode::find()->where($query_con);
        $dataProvider = new ActiveDataProvider([ 
             'query' => $query,        
             'pagination' => [ 'pagesize' => 50],
        ]);       
        $searchModel = new DcmdServicePoolNodeSearch();
        $searchModel->load(Yii::$app->request->queryParams);
        return $this->render('add', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'task_id' => $task_id,
            'svr_pool' => $svr_pool_array,
        ]);

    }

    public function actionDel($task_id)
    {
        $svr_pool_array = array();
        $query = DcmdTaskServicePool::find()->andWhere(['task_id'=>$task_id])->asArray()->all();
        foreach($query as $item) $svr_pool_array[$item['svr_pool']] = $item['svr_pool'];
        if (Yii::$app->request->post() && array_key_exists('selection', Yii::$app->request->post())) {
          $ret_msg = "";
          $query = DcmdCenter::findOne(['master'=>1]);
          if ($query) {
            list($host, $port) = split(':', $query["host"]);
            $select = Yii::$app->request->post()['selection'];
            foreach($select as $subtask_id) {
              $reply = execTaskCmd($host, $port, $task_id, 100, 17, $subtask_id);
              if($reply->getState() == 0) $ret_msg.= $subtask_id.":删除成功 ";
              else $ret_msg .= $subtask_id.":删除失败:".$reply->getErr()." ";
            }
          }else $ret_msg = "删除失败:无法获取Center!";
          Yii::$app->getSession()->setFlash('success',$ret_msg);
        }
        $params = array();
        $params['DcmdTaskNodeSearch'] = array('task_id'=>$task_id);
        if(array_key_exists('DcmdTaskNodeSearch', Yii::$app->request->queryParams))
          $params['DcmdTaskNodeSearch'] = Yii::$app->request->queryParams['DcmdTaskNodeSearch'] + $params['DcmdTaskNodeSearch'];
        $searchModel = new DcmdTaskNodeSearch();
        $dataProvider = $searchModel->search($params, 20);
        return $this->render('delete', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'task_id' => $task_id, 
            'svr_pool' => $svr_pool_array,
        ]); 
    }
    /**
     * Displays a single DcmdTaskNode model.
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
     * Creates a new DcmdTaskNode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    private function actionCreate()
    {
        $model = new DcmdTaskNode();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->subtask_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DcmdTaskNode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    private function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->subtask_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Finds the DcmdTaskNode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdTaskNode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdTaskNode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
