<?php

namespace app\controllers;

use Yii;
use app\models\DcmdTaskNode;
use app\models\DcmdService;
use app\models\DcmdApp;
use app\models\DcmdTaskCmdArg;
use app\models\DcmdTask;
use app\models\DcmdTaskTemplate;
use app\models\DcmdTaskSearch;
use app\models\DcmdServicePool;
use app\models\DcmdServicePoolSearch;
use app\models\DcmdTaskTemplateServicePool;
use app\models\DcmdServicePoolNode;
use app\models\DcmdTaskServicePool;
use app\models\DcmdCenter;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

include dirname(__FILE__)."/../common/dcmd_util_func.php";
include_once(dirname(__FILE__)."/../common/interface.php");
/**
 * DcmdTaskController implements the CRUD actions for DcmdTask model.
 */
class DcmdTaskController extends Controller
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
     * Lists all DcmdTask models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdTaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdTask model.
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
     * Creates a new DcmdTask model.
      If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($task_tmpt_id)
    {
        if (Yii::$app->request->post()) { ///提交新建任务
          $post_task = Yii::$app->request->post()['DcmdTask'];
          $dcmd_task = new DcmdTask();
          $dcmd_task->task_name = Yii::$app->request->post()['task_cmd_prv'].'-'.$post_task['task_name'];
          $dcmd_task->task_cmd  = $post_task['task_cmd'];
          $dcmd_task->depend_task_id = 0;
          $dcmd_task->depend_task_name = "NULL";
          $dcmd_task->svr_id = $post_task['svr_id'];
          $dcmd_task->svr_name = $post_task['svr_name'];
          $dcmd_task->svr_path = $post_task['svr_path'];
          $dcmd_task->tag = $post_task['tag'];
          $dcmd_task->update_env = $post_task['update_env'];
          $dcmd_task->update_tag = $post_task['update_tag'];
          $dcmd_task->state = 0;
          $dcmd_task->freeze = 0;
          $dcmd_task->valid = 1;
          $dcmd_task->pause = 0;
          $dcmd_task->err_msg = "NULL";
          $dcmd_task->concurrent_rate = $post_task['concurrent_rate'];
          $dcmd_task->timeout = $post_task['timeout'];
          $dcmd_task->auto = $post_task['auto'];
          $dcmd_task->process = $post_task['process'];
          $dcmd_task->utime =  date('Y-m-d H:i:s');
          $dcmd_task->ctime = $dcmd_task->utime;
          $dcmd_task->opr_uid = 100;
          $dcmd_task->comment = $post_task['comment'];
          $arg = array();
          foreach(Yii::$app->request->post() as $k=>$v) {
            if(substr($k,0,3) == "Arg") $arg[substr($k,3)] = $v;
          }
          $dcmd_task->task_arg = arrToXml($arg);  
          if($dcmd_task->save()) {
            ///保存服务池子信息
            if (array_key_exists("selection", Yii::$app->request->post())) {
              $svr_pool = Yii::$app->request->post()["selection"];
              foreach($svr_pool as $k=>$svr_pool_id) {
               $svr_query = DcmdService::findOne($dcmd_task->svr_id);
               $svr_pool_query = DcmdServicePool::findOne($svr_pool_id);
               $svr_pool_node_query = DcmdServicePoolNode::find()->andWhere(['svr_pool_id'=>$svr_pool_id])->asArray()->all();
               $dcmd_task_service_pool = new DcmdTaskServicePool();
               $dcmd_task_service_pool->task_id = $dcmd_task->task_id;
               $dcmd_task_service_pool->task_cmd = $dcmd_task->task_cmd;
               $dcmd_task_service_pool->svr_pool = $svr_pool_query['svr_pool'];
               $dcmd_task_service_pool->svr_pool_id = $svr_pool_id;
               $dcmd_task_service_pool->env_ver = $svr_pool_query['env_ver'];
               $dcmd_task_service_pool->repo = $svr_pool_query['repo'];
               $dcmd_task_service_pool->run_user = $svr_query['run_user'];
               $dcmd_task_service_pool->undo_node = count($svr_pool_node_query);
               $dcmd_task_service_pool->doing_node = 0;
               $dcmd_task_service_pool->finish_node = 0;
               $dcmd_task_service_pool->fail_node = 0;
               $dcmd_task_service_pool->ignored_fail_node = 0;
               $dcmd_task_service_pool->ignored_doing_node = 0;
               $dcmd_task_service_pool->state = 0;
               $dcmd_task_service_pool->utime = date('Y-m-d H:i:s');
               $dcmd_task_service_pool->ctime = $dcmd_task_service_pool->utime;
               $dcmd_task_service_pool->opr_uid = 100;
               $dcmd_task_service_pool->save();
              }
            }
            ///选择服务池子
            return $this->redirect(array('select-service-pool-node','task_id'=>$dcmd_task->task_id));;
          }
        }
        ///新建任务 
        $query = DcmdTaskTemplate::findOne($task_tmpt_id);
        $model = new DcmdTask();
        $app = "";
        $args = "";
        $task_cmd_prv = "";
        if($query) {
            $model->task_cmd = $query['task_cmd'];
            $model->depend_task_id = 0;
            $model->depend_task_name = "";
            $model->svr_id = $query['svr_id'];
            $model->svr_name = $query['svr_name'];
            $model->concurrent_rate = $query['concurrent_rate'];
            $model->timeout = $query['timeout'];
            $model->auto = $query['auto'];
            $model->process = $query['process'];
            $model->update_env = $query['update_env'];
            $task_cmd_prv  = $query['task_cmd'].'-'.date("YmdHis");
            $ret = DcmdService::findOne($model->svr_id);
            $args = $this->showTaskArg($query['task_arg'], $query['task_cmd_id']); 
            if($ret) {
              $app_id = $ret['app_id'];
              $ret = DcmdApp::findOne($app_id);
              if($ret) $app = $ret['app_name'];
            }   
        }
        ///获取任务模板的服务池子      
        $query = DcmdTaskTemplateServicePool::find()->andWhere(['task_tmpt_id'=>$task_tmpt_id])->asArray()->all();     
        $svr_pool = "svr_pool_id in (0";      
        foreach($query as $item) $svr_pool .= ",".$item['svr_pool_id'];      
        $svr_pool .=")";      
        $query = DcmdServicePool::find()->where($svr_pool);      
        $dataProvider =  new ActiveDataProvider([            
               'query' => $query,      
        ]);      
        $searchModel = new DcmdServicePoolSearch();     

        return $this->render('create', [
                 'searchModel' => $searchModel,         
                 'dataProvider' => $dataProvider,
                 'model' => $model,
                 'app' => $app, 
                 'args' => $args,
                 'task_cmd_prv' => $task_cmd_prv,
             ]);
    }

    private function showTaskArg($arg_xml, $task_cmd_id)
    {
       $content = "";
       ///
       $ar = xml_to_array($arg_xml);
       $args = array();
       if(array_key_exists('env', $ar)) $args = $ar['env'];
       $query = DcmdTaskCmdArg::find()->andWhere(['task_cmd_id' => $task_cmd_id])->asArray()->all();
       if ($query) { ///获取模板参数
         $content = '<table class="table table-striped table-bordered detail-view">
                    <tr><td>参数名称</td>
                    <td>是否可选</td>
                    <td>值</td></tr>';
         foreach($query as $item) {
           $content .=  "<tr><td>".$item['arg_name'].'</td>';
           $content .=  "<td>"; if($item['optional'] == 0) $content .= "否"; else $content .= "是"; $content .= "</td>";
           if(array_key_exists($item['arg_name'], $args)) $content .= "<td><input name='Arg".$item['arg_name']."' type='text'  value='".$args[$item['arg_name']]."' >";
           else $content .= "<td><input name='Arg".$item['arg_name']."' type='text'  value='' >";
           $content .= "</td><tr>";
         }      
         $content .= "</table>";
       }
       return $content;
    }
    /**
     * Updates an existing DcmdTask model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
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
     * Deletes an existing DcmdTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
   
    public function actionSelectServicePool($task_id, $task_tmpt_id)
    {
      $task = DcmdTask::findOne($task_id);
      ///获取任务模板的服务池子
      $query = DcmdTaskTemplateServicePool::find()->andWhere(['task_tmpt_id'=>$task_tmpt_id])->asArray()->all();
      $svr_pool = "svr_pool_id in (0";
      foreach($query as $item) $svr_pool .= ",".$item['svr_pool_id'];
      $svr_pool .=")";
      $query = DcmdServicePool::find()->where($svr_pool);
      $dataProvider =  new ActiveDataProvider([            
            'query' => $query,        
      ]);
      $searchModel = new DcmdServicePoolSearch();
      return $this->render('select_service_pool', [
         'searchModel' => $searchModel,
         'dataProvider' => $dataProvider,
         'task_id' => $task_id,
         'task_name' => $task->task_name,
         'task_cmd' => $task->task_cmd, 
      ]);
    }

    public function actionSelectServicePoolNode($task_id)
    {
       ///获取已有的设备ip
       $query = DcmdTaskNode::find()->andWhere(['task_id'=>$task_id])->asArray()->all();
       $exist_ips = array();
       foreach($query as $item) {
        array_push($exist_ips, $item['ip']);
       }
       $task_service_pool  = DcmdTaskServicePool::find()->andWhere(['task_id'=>$task_id])->asArray()->all();
       $service_pool = array();
       foreach($task_service_pool as $item) {
         $node = array();
         $node['svr_pool_id']=$item['svr_pool_id'];
         $node['svr_pool'] = $item['svr_pool'];
         $node['ip'] = array();
         $svr_pool_node = DcmdServicePoolNode::find()->andWhere(['svr_pool_id'=>$item['svr_pool_id']])->asArray()->all();
         foreach($svr_pool_node as $ip) {
           if(array_key_exists($ip['ip'], $exist_ips)) continue;
            array_push($node['ip'], $ip['ip']);
         }
         array_push($service_pool, $node);
       }
       return $this->render('select_service_pool_node', [
         'service_pool' => $service_pool,
         'task_id' => $task_id,
         'task_name' => "task_name",
       ]);
    }
 
    public function actionAddServicePoolNode()
    {
      /// var_dump(Yii::$app->request->post()); echo "=====";
      $task_id = Yii::$app->request->post()['task_id'];
      $svr_pool = array();
      $svr_pool_node = array();
      foreach(Yii::$app->request->post() as $k=>$v) {
        if (substr($k, 0, 6) == 'AppSvr') array_push($svr_pool, substr($k, 6));
      }
      foreach(Yii::$app->request->post() as $k=>$ip) {
        if (substr($k, 0, 11) == 'SvrPoolNode') { ///设备信息
          $pool_ip = substr($k, 11);
          foreach($svr_pool as $pool) {
            if(strpos($pool_ip, $pool) == 0) {
              if( array_key_exists($pool, $svr_pool_node))array_push($svr_pool_node[$pool],$ip);
              else $svr_pool_node[$pool] = array($ip);
              break;
            }
          }
        }
      }
      ///var_dump($svr_pool); echo "=======";
      ////var_dump($svr_pool_node);exit;
      $ret_msg = "";
      $task = DcmdTask::findOne($task_id);
      foreach($svr_pool_node as $pool => $ips) {
        foreach($ips as $ip) {
          $query = DcmdTaskNode::findOne(['task_id'=>$task_id, 'svr_pool'=>$pool, 'ip'=>$ip]);
          if($query) {
            $ret_msg .= $ip." : 已经存在 ";
          }else{
            $dcmd_task_node = new DcmdTaskNode();
            $dcmd_task_node->task_id = $task_id;
            $dcmd_task_node->task_cmd = $task->task_cmd;
            $dcmd_task_node->svr_pool = $pool;
            $dcmd_task_node->svr_name = $task->svr_name;
            $dcmd_task_node->ip = $ip;
            $dcmd_task_node->state = 0;
            $dcmd_task_node->ignored = 0;
            $dcmd_task_node->start_time = 0;
            $dcmd_task_node->finish_time = 0;
            $dcmd_task_node->process = "0";
            $dcmd_task_node->err_msg = "NULL";
            $dcmd_task_node->utime = date('Y-m-d H:i:s');
            $dcmd_task_node->ctime = $dcmd_task_node->utime;
            $dcmd_task_node->opr_uid = 100;
            $dcmd_task_node->save();
            $ret_msg .= $ip." : 添加成功 ";
          }
        }
      }
      Yii::$app->getSession()->setFlash('success', $ret_msg);
      $this->redirect(array('dcmd-task-async/monitor-task','task_id'=>$task_id));
    }
    function actionFinishTask() 
    {
        ///var_dump(Yii::$app->request->post());exit;  
        $msg = "";
       if(array_key_exists('selection', Yii::$app->request->post())) {
         $task_ids = Yii::$app->request->post()['selection'];
         $query = DcmdCenter::findOne(['master'=>1]);
         if ($query) {
           list($host, $port) = split(':', $query["host"]);
           foreach($task_ids as $tid) {
             $reply = execTaskCmd($host, $port, $tid, 100, 5);
             if($reply->getState() == 0) $msg .= $tid.":完成成功 ";
             else $msg .= $tid.":完成失败 ".$reply->getErr()." ";
           }
         }else $msg = "无法获取Center!";
       }
       Yii::$app->getSession()->setFlash('success', $msg);
       $this->redirect(array('index'));  
    }
    /**
     * Finds the DcmdTask model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdTask the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdTask::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
