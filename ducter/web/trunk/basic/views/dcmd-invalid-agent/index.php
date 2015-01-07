<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdAppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '非法Agent列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-app-index">
<table class="table table-striped table-bordered">
<tbody><tr><td>IP</td><td>操作</td></tr>
<?php
 foreach($ips as $k=>$v) {
  echo "<tr data-key='6'><td>$v</td><td>add</td></tr>";
 } 
?>
</tbody></table>
</div>
