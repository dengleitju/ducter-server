<?php

namespace app\controllers;

use Yii;
use app\models\DcmdTaskTemplate;
use app\models\DcmdApp;
use app\models\DcmdTaskCmdArg;
use app\models\DcmdTaskCmd;
use app\models\DcmdTaskTemplateServicePool;
use app\models\DcmdTaskTemplateSearch;
use app\models\DcmdTaskTemplateServicePoolSearch;
use app\models\DcmdService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
include dirname(__FILE__)."/../common/dcmd_util_func.php";


/**
 * DcmdTaskTemplateController implements the CRUD actions for DcmdTaskTemplate model.
 */
class DcmdTaskTemplateController extends Controller
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
     * Lists all DcmdTaskTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdTaskTemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $task_cmd = array();
        $query = DcmdTaskCmd::find()->asArray()->all();
        if($query) {
          foreach($query as $item) $task_cmd[$item['task_cmd_id']] = $item['task_cmd'];
        }
        $app = array();
        $query = DcmdApp::find()->asArray()->all();
        if($query) {
          foreach($query as $item) $app[$item['app_id']] = $item['app_name'];
        }
        $service = array();
        $query = DcmdService::find()->asArray()->all();
        if($query) {
          foreach($query as $item) $service[$item['svr_id']] = $item['svr_name'];
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'task_cmd' => $task_cmd,
            'app' => $app,
            'service' => $service,
        ]);
    }

    /**
     * Displays a single DcmdTaskTemplate model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $arg_content = $this->actionGetTaskTypeArg($model->task_cmd_id, xmltoarray($model->task_arg), "disabled"); 
        $searchModel = new DcmdTaskTemplateServicePoolSearch();
        $dataProvider = $searchModel->search(array('task_tmpt_id'=>$id));
        return $this->render('view', [
            'model' => $model,
            'arg_content' => $arg_content,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new DcmdTaskTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DcmdTaskTemplate();
        if (Yii::$app->request->post()) {  ///保存并返回
          $model->load(Yii::$app->request->post());
          $query = DcmdService::findOne($model->svr_id);
          $model->svr_name = $query['svr_name'];
          $query = DcmdTaskCmd::findOne($model->task_cmd_id); ////Yii::$app->request->post()['task_cmd_id']);
          $model->task_cmd = $query['task_cmd'];
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = 100;
          $arg = array();
          foreach(Yii::$app->request->post() as $k=>$v) {
            if(substr($k,0,3) == "Arg") $arg[substr($k,3)] = $v;
          }
          $model->task_arg = arrToXml($arg); 
          if($model->save()) {
            return $this->redirect(['view', 'id' => $model->task_tmpt_id]);   
          }
        } 
        ///新建
        ///获取产品信息
        $query = DcmdApp::find()->asArray()->all();
        $app = array(""=>"");
        if ($query) {
          foreach($query as $item) $app[$item['app_id']] = $item['app_name'];
        }
        ///获取任务脚本
        $query = DcmdTaskCmd::find()->asArray()->all();
        $task_cmd = array(""=>"");
        if($query) {
          foreach($query as $item) $task_cmd[$item['task_cmd_id']] = $item['ui_name'];
        }
        return $this->render('create', [
             'model' => $model,
             'app' => $app,
             'task_cmd' => $task_cmd,
        ]);
    }

    /**
     * Updates an existing DcmdTaskTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
          $model->load(Yii::$app->request->post());
          $query = DcmdService::findOne($model->svr_id);
          $model->svr_name = $query['svr_name'];
          $query = DcmdTaskCmd::findOne($model->task_cmd_id); ////Yii::$app->request->post()['task_cmd_id']);
          $model->task_cmd = $query['task_cmd'];
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = 100;
          $arg = array();
          foreach(Yii::$app->request->post() as $k=>$v) {
            if(substr($k,0,3) == "Arg") $arg[substr($k,3)] = $v;
          }
          $model->task_arg = arrToXml($arg);
          if ($model->save()) return $this->redirect(['view', 'id' => $model->task_tmpt_id]);
        } 
        ///获取产品信息
        $query = DcmdApp::find()->asArray()->all();
        $app = array(""=>"");
        if ($query) {
          foreach($query as $item) $app[$item['app_id']] = $item['app_name'];
        }
        ///获取任务脚本
        $query = DcmdTaskCmd::find()->asArray()->all();
        $task_cmd = array(""=>"");
        if($query) {
          foreach($query as $item) $task_cmd[$item['task_cmd_id']] = $item['task_cmd'];
        }

        $arg_content = $this->actionGetTaskTypeArg($model->task_cmd_id, xmltoarray($model->task_arg));
        return $this->render('update', [
             'model' => $model,
             'app' => $app,
             'task_cmd' => $task_cmd,
             'svr' => array($model->svr_id=>$model->svr_name),
             'arg_content' => $arg_content,
         ]);
        
    }

    /**
     * Deletes an existing DcmdTaskTemplate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        DcmdTaskTemplateServicePool::deleteAll('task_tmpt_id = '.$id);
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(['index']);
    }

    public function actionGetServices()
    {
      $app_id = Yii::$app->request->post()["app_id"];
      $query = DcmdService::find()->andWhere(['app_id'=>$app_id])->asArray()->all();
      $retcontent = "";
      if ($query) {
         foreach($query as $item) $retcontent .= $item["svr_id"].",".$item['svr_name'].";";
      }
      echo $retcontent;
      exit ;
    }


    public function actionGetServicePools()
    {
      $svr_id = Yii::$app->request->post()["svr_id"];
      $query = DcmdServicePool::find(['svr_id'=>$svr_id])->asArray()->all();
      $retcontent = "";
      if ($query) {
         foreach($query as $item) $retcontent .= $item["svr_pool_id"].",".$item['svr_pool'].";";
      }
      echo $retcontent;
      exit ;
    }

    public function actionGetTaskTypeArg($task_cmd_id, $arg = array(), $disabled="")
    {
      $query = DcmdTaskCmdArg::find(['task_cmd_id'=>$task_cmd_id])->asArray()->all(); 
      $content = "";
      if($query) {
        $content .= '<table class="table table-striped table-bordered detail-view">
             <tr> <td>参数名称</td>
             <td>是否可选</td>
             <td>值</td>
             </tr>';
       foreach($query as $item) {
        $content .=  "<tr><td>".$item['arg_name'].'</td>';
        $content .=  "<td>"; if($item['optional'] == 0) $content .= "否"; else $content .= "是"; $content .= "</td>";
        $content .= "<td style=\"display:none\">".$item['arg_name']."</td>";
        ///$content .= "<td><input name='Arg".$item['arg_name']."' type='text' $disabled ";
        if(array_key_exists($item['arg_name'], $arg)) {
          if ($disabled != "") $content .= "<td>".$arg[$item['arg_name']];
          else $content .= "<td><input name='Arg".$item['arg_name']."' type='text'  value='".$arg[$item['arg_name']]."' >";
        } else $content .= "<td><input name='Arg".$item['arg_name']."' type='text'  value='' >";
        $content .= "</td><tr>";
       }
       $content .= "</table>";
      }else{
       $content .= "无参数设定";
      }
      return $content;
    }
 

    /**
     * Finds the DcmdTaskTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdTaskTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdTaskTemplate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
