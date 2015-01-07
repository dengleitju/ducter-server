<?php

namespace app\controllers;

use Yii;
use app\models\DcmdTaskServicePool;
use app\models\DcmdTaskServicePoolSearch;
use app\models\DcmdTaskNodeSearch;
use app\models\DcmdTask;
use app\models\DcmdService;
use app\models\DcmdApp;
use app\models\DcmdTaskNode;
use app\models\DcmdCenter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
include_once dirname(__FILE__)."/../common/dcmd_util_func.php";
require_once (dirname(__FILE__)."/../common/interface.php");
/**
 * DcmdNodeController implements the CRUD actions for DcmdNode model.
 */
class DcmdTaskAsyncController extends Controller
{
    public $enableCsrfValidation = false;
    public function updateTask($task_id, $timeout, $auto, $concurrent_rate)
    {
      $query = DcmdCenter::findOne(['master'=>1]);      
      if ($query) {
         list($host, $port) = split(':', $query["host"]);
         $reply = execTaskCmd($host, $port, $task_id, 100, 16, NULL, NULL, NULL, NULL, $concurrent_rate,
          $timeout, $auto);
         if ($reply->getState() == 0) Yii::$app->getSession()->setFlash('success', '更新成功!');
         else Yii::$app->getSession()->setFlash('error', '更新失败:'.$reply->getErr());
      }else Yii::$app->getSession()->setFlash('error', '更新失败:无法连接center!');
    }
    public function actionMonitorTask($task_id)
    {
       if(Yii::$app->request->post()) {
         $timeout = Yii::$app->request->post()['timeout'];
         $auto = Yii::$app->request->post()['auto'];
         $concurrent_rate = Yii::$app->request->post()['concurrent_rate'];
         $this->updateTask($task_id, $timeout, $auto, $concurrent_rate); ////var_dump(Yii::$app->request->post());exit;
       }
       $task = DcmdTask::findOne($task_id);
       ///获取产品名称
       $query = DcmdService::findOne($task->svr_id);
       $app_id = $query->app_id;
       $query = DcmdApp::findOne($app_id);
       $app_name = $query->app_name;
       $ret = xml_to_array($task->task_arg);
       $args = "";
       if(is_array($ret['env'])) 
         foreach($ret['env'] as $k=>$v) $args .= $k.'='.$v." ; ";
       return $this->render('monitor', 
                [ 'task_id'=>$task_id,
                 'task' => $task,
                 'args' => $args,
                 'app_name' => $app_name,
                 'app_id'=>$app_id,]);  
    }

    public function actionServicePoolState($task_id)
    {
        $query = DcmdTaskServicePool::find()->andWhere(['task_id'=>$task_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $task = DcmdTask::findOne($task_id);
        $searchModel = new DcmdTaskServicePoolSearch();
        $this->layout=false;
        return $this->render('service_pool_state_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'task_id' => $task_id,
            'task' => $task,
        ]);

    }
 
    public function actionStateTable($task_id, $service_pool) 
    {
        $this->layout=false;
        return $this->render('state_table', ['task_id'=>$task_id, 'service_pool'=>$service_pool]);
    }
    

    public function actionInitSubTask($task_id, $svr_pool)
    {
        $con = array('task_id'=>$task_id, 'state'=>0);
        if($svr_pool != 'all') $con['svr_pool'] = $svr_pool;
        $query = DcmdTaskNode::find()->andWhere($con)->orderBy("utime desc")->limit(5);
        $dataProvider = new ActiveDataProvider([
          'query' => $query,
        ]);
        $searchModel = new DcmdTaskNodeSearch();
        $this->layout=false;
        return $this->render('init_sub_task_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'task_id' => $task_id,
            'svr_pool' => $svr_pool,
        ]);
    }
    ///running sub task list
    public function actionRunSubTaskList($task_id, $svr_pool)
    {
        $con = array('task_id'=>$task_id, 'state'=>1);
        if($svr_pool != 'all') $con['svr_pool'] = $svr_pool;
        $query = DcmdTaskNode::find()->andWhere($con)->orderBy("utime desc")->limit(5);
        $dataProvider = new ActiveDataProvider([
           'query' => $query,
        ]);
        $searchModel = new DcmdTaskNodeSearch();
        $this->layout=false;
        return $this->render('run_sub_task_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'task_id' => $task_id,
            'svr_pool' => $svr_pool,
        ]);
    }
   
    ///fail sub task list
    public function actionFailSubTaskList($task_id, $svr_pool)
    {
        $con = array('task_id'=>$task_id, 'state'=>3);
        if($svr_pool != 'all') $con['svr_pool'] = $svr_pool;
        $query = DcmdTaskNode::find()->andWhere($con)->orderBy("utime desc")->limit(5);
        $dataProvider = new ActiveDataProvider([
           'query' => $query,
        ]);
        $searchModel = new DcmdTaskNodeSearch();
        $this->layout=false;
        return $this->render('fail_sub_task_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'task_id' => $task_id,
            'svr_pool' => $svr_pool,
        ]);
    }

    ///success sub task list
    public function actionSuccessSubTaskList($task_id, $svr_pool)
    {
        $con = array('task_id'=>$task_id, 'state'=>2);
        if($svr_pool != 'all') $con['svr_pool'] = $svr_pool;
        $query = DcmdTaskNode::find()->andWhere($con)->orderBy("utime desc")->limit(5);
        $dataProvider = new ActiveDataProvider([
           'query' => $query,
        ]);
        $searchModel = new DcmdTaskNodeSearch();
        $this->layout=false;
        return $this->render('success_sub_task_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'task_id' => $task_id,
            'svr_pool' => $svr_pool,
        ]);
    }
    public function actionStartTask($task_id)
    {
      $query = DcmdCenter::findOne(['master'=>1]);
      if ($query) {
         list($host, $port) = split(':', $query["host"]);      
         $reply = execTaskCmd($host, $port, $task_id, 100, 1);
         if ($reply->getState() == 0) Yii::$app->getSession()->setFlash('success', '启动成功!');
         else Yii::$app->getSession()->setFlash('error', '启动失败:'.$reply->getErr());
      }else Yii::$app->getSession()->setFlash('error', '启动失败:无法连接center!');
      $this->redirect(array('monitor-task','task_id'=>$task_id));
    }
    public function actionPauseTask($task_id)
    {
      $query = DcmdCenter::findOne(['master'=>1]);
      if ($query) {
         list($host, $port) = split(':', $query["host"]);
         $reply = execTaskCmd($host, $port, $task_id, 100, 2);
         if ($reply->getState() == 0) Yii::$app->getSession()->setFlash('success', '停止成功!');
         else Yii::$app->getSession()->setFlash('error', '停止失败:'.$reply->getErr());
      }else Yii::$app->getSession()->setFlash('error', '停止失败:无法连接center!');
      $this->redirect(array('monitor-task','task_id'=>$task_id));
    }
    public function actionUpdateTask($task_id)
    {
      $query = DcmdCenter::findOne(['master'=>1]);
      $task = DcmdTask::findOne($task_id);
      if ($query) {
         list($host, $port) = split(':', $query["host"]);
         $reply = execTaskCmd($host, $port, $task_id, 100, 16, NULL, NULL, NULL, NULL, $task->concurrent_rate,
          $task->timeout, $task->auto);
         if ($reply->getState() == 0) Yii::$app->getSession()->setFlash('success', '更新成功!');
         else Yii::$app->getSession()->setFlash('error', '更新失败:'.$reply->getErr());
      }else Yii::$app->getSession()->setFlash('error', '更新失败:无法连接center!');
      $this->redirect(array('monitor-task','task_id'=>$task_id));
    }
    public function actionRedoTask($task_id)
    {
      $query = DcmdCenter::findOne(['master'=>1]);
      if ($query) {
         list($host, $port) = split(':', $query["host"]);
         $reply = execTaskCmd($host, $port, $task_id, 100, 10);
         if ($reply->getState() == 0) Yii::$app->getSession()->setFlash('success', '重做成功!');
         else Yii::$app->getSession()->setFlash('error', '重做失败:'.$reply->getErr());
      }else Yii::$app->getSession()->setFlash('error', '重做失败:无法连接center!');
      $this->redirect(array('monitor-task','task_id'=>$task_id));
    }
    public function actionResumeTask($task_id)
    {
      $query = DcmdCenter::findOne(['master'=>1]);
      if ($query) {
         list($host, $port) = split(':', $query["host"]);
         $reply = execTaskCmd($host, $port, $task_id, 100, 3);
         if ($reply->getState() == 0) Yii::$app->getSession()->setFlash('success', '继续成功!');
         else Yii::$app->getSession()->setFlash('error', '继续失败:'.$reply->getErr());
      }else Yii::$app->getSession()->setFlash('error', '继续失败:无法连接center!');
      $this->redirect(array('monitor-task','task_id'=>$task_id));
    }
    public function actionRetryTask($task_id)
    {
      $query = DcmdCenter::findOne(['master'=>1]);
      if ($query) {
         list($host, $port) = split(':', $query["host"]);
         $reply = execTaskCmd($host, $port, $task_id, 100, 4);
         if ($reply->getState() == 0) Yii::$app->getSession()->setFlash('success', '重试成功!');
         else Yii::$app->getSession()->setFlash('error', '重试失败:'.$reply->getErr());
      }else Yii::$app->getSession()->setFlash('error', '重试失败:无法连接center!');
      $this->redirect(array('monitor-task','task_id'=>$task_id));
    }
    public function actionFreezeTask($task_id)
    {
      $query = DcmdCenter::findOne(['master'=>1]);
      if ($query) {
         list($host, $port) = split(':', $query["host"]);
         $reply = execTaskCmd($host, $port, $task_id, 100, 14);
         if ($reply->getState() == 0) Yii::$app->getSession()->setFlash('success', '冻结成功!');
         else Yii::$app->getSession()->setFlash('error', '冻结失败:'.$reply->getErr());
      }else Yii::$app->getSession()->setFlash('error', '冻结失败:无法连接center!');
      $this->redirect(array('monitor-task','task_id'=>$task_id));
    }
    public function actionUnfreezeTask($task_id)
    {
      $query = DcmdCenter::findOne(['master'=>1]);
      if ($query) {
         list($host, $port) = split(':', $query["host"]);
         $reply = execTaskCmd($host, $port, $task_id, 100, 15);
         if ($reply->getState() == 0) Yii::$app->getSession()->setFlash('success', '解冻成功!');
         else Yii::$app->getSession()->setFlash('error', '解冻失败:'.$reply->getErr());
      }else Yii::$app->getSession()->setFlash('error', '解冻失败:无法连接center!');
      $this->redirect(array('monitor-task','task_id'=>$task_id));
    }
    public function actionStartSubtask($task_id, $subtask_id)
    {
      $query = DcmdCenter::findOne(['master'=>1]);
      if ($query) {
         list($host, $port) = split(':', $query["host"]);
         $reply = execTaskCmd($host, $port, $task_id, 100, 9, $subtask_id);
         if ($reply->getState() == 0) return "启动成功!";
         else return "启动失败:".$reply->getErr();
      }
      return "启动失败:无法获取center!";
    }
   
    public function actionStopSubtask($task_id, $subtask_id)
    {
      $query = DcmdCenter::findOne(['master'=>1]);
      if ($query) {
         list($host, $port) = split(':', $query["host"]);
         $reply = execTaskCmd($host, $port, $task_id, 100, 7, $subtask_id);
         if ($reply->getState() == 0) return "终止成功!";
         else return "终止失败:".$reply->getErr();
      }
      return "终止失败:无法获取center!";
    }
   
    public function actionRedoSubtask($task_id, $subtask_id)
    {
      $query = DcmdCenter::findOne(['master'=>1]);
      if ($query) {
         list($host, $port) = split(':', $query["host"]);
         $reply = execTaskCmd($host, $port, $task_id, 100, 12, $subtask_id);
         if ($reply->getState() == 0) return "重做成功!";
         else return "重做失败:".$reply->getErr();
      }
      return "重做失败:无法获取center!";
    }

    public function actionIgnoreSubtask($task_id, $subtask_id)
    {
      $query = DcmdCenter::findOne(['master'=>1]);
      if ($query) {
         list($host, $port) = split(':', $query["host"]);
         $reply = execTaskCmd($host, $port, $task_id, 100, 13, $subtask_id);
         if ($reply->getState() == 0) return "忽略成功!";
         else return "忽略失败:".$reply->getErr();
      }
      return "忽略失败:无法获取center!";
    }
 
    public function actionAgentSubtaskOut($task_id, $task_cmd, $subtask_id, $ip)
    {
        $task = DcmdTask::findOne($task_id);
        $task_name = $task->task_name;
        return $this->render('sub_task_output', [
            'task_id' => $task_id,
            'task_cmd' => $task_cmd,
            'task_name' => $task_name,
            'subtask_id' => $subtask_id,
            'ip' => $ip,
        ]); 
    } 

    public function actionSubtaskOutput($subtask_id, $ip, $os)
    {
      $query = DcmdCenter::findOne(['master'=>1]);
      if ($query) {
         list($host, $port) = split(':', $query["host"]);
         $reply =  getTaskOutput($host, $port, $subtask_id, $ip, $os);
         if ($reply->getState() == 0) {
           return '<div id="'.$reply->getOffset().'">'.str_replace("\n", "<br/>",$reply->getResult()).'</div>';
         }
         return "<div id='0'>".$reply->getErr()."</div>";
      } 
      return "<div id='0'>无法获取Center</div>";
    }

   public function actionExecSubtaskCmd($task_id, $subtask_id, $cmd_type)
   {
     if ($cmd_type == 7) return $this->actionStopSubtask($task_id, $subtask_id);
     if ($cmd_type == 9) return $this->actionStartSubtask($task_id, $subtask_id);
     if ($cmd_type == 12) return $this->actionRedoSubtask($task_id, $subtask_id);
     return "未知操作";
   } 
}
