<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Login';
///$this->params['breadcrumbs'][] = $this->title;
?>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-param" content="_csrf">
    <title><?= Html::encode($this->title) ?></title>
    <link href="/dcmd/assets/19f3a78/css/bootstrap.css" rel="stylesheet">
<link href="/dcmd/css/site.css" rel="stylesheet"></head>
</head>
<style type="text/css">
body{background:#F0F0F0;font-family:'Helvetica Neue','Luxi Sans','DejaVu Sans','Microsoft Yahei','Tahoma';}
.wrapper{width:1100px;height:800px;position:absolute;top:50%;left:50%;margin-left:-300px;margin-top:-200px;}
</style>
<body>
<div class="site-login" >


    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'wrapper',],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username') ?>
    <br>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <br>
    <?= $form->field($model, 'rememberMe', [
        'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ])->checkbox() ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

  <!--  <div class="col-lg-offset-1" style="color:#999;">
        You may login with <strong>admin/admin</strong> or <strong>demo/demo</strong>.<br>
        To modify the username/password, please check out the code <code>app\models\User::$users</code>.
    </div>-->
</div>
</body>
</html>
