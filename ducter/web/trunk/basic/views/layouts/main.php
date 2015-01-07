<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php
  if(Yii::$app->user->isGuest)  {
    header("Location:index.php?r=site/login");
    exit;
  }
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'Dcmd',
                'brandUrl' => "#",///Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-left'],
                'items' => [
                    [
			'label' => '设备管理',
			'items' => [
			  ['label' => '设备池子','url'=>'?r=dcmd-node-group/index'],
			  ['label' => '设备', 'url' => '?r=dcmd-node/index'],
                          ['label' => '未注册的设备', 'url' => '?r=dcmd-invalid-agent/index'],
			  ['label' => '控制中心', 'url' => '?r=dcmd-center/index'],
			],
                    ],
                    [
                        'label' => '产品管理',
                        'items' => [
                          ['label' => '产品','url'=>'?r=dcmd-app/index'],
                          ['label' => '服务', 'url' => '?r=dcmd-service/index'],
                          ['label' => '服务池子', 'url' => '?r=dcmd-service-pool/index'],
                          ['label' => '服务池子设备', 'url' => '?r=dcmd-service-pool-node/index'],
                        ],
                    ],
                    [
                        'label' => '任务',
                        'items' => [
                          ['label' => '任务脚本管理','url'=>'?r=dcmd-task-cmd/index'],
                          ['label' => '任务模板管理', 'url' => '?r=dcmd-task-template/index'],
                          ['label' => '任务', 'url' => '?r=dcmd-task/index'],
                          ['label' => '历史任务', 'url' => '?r=dcmd-task-history/index'],
                        ],
                    ],
                    [
                        'label' => '命令',
                        'items' => [
                          ['label' => '命令脚本管理','url'=>'#'],
                          ['label' => '重复命令管理', 'url' => '#'],
                        ],
                    ],
                    [
                        'label' => '权限',
                        'items' => [
                          ['label' => '用户','url'=>'?r=dcmd-user/index'],
                          ['label' => '用户组', 'url' => '?r=dcmd-group/index'],
                          ['label' => '修改口令', 'url' => '#'],
                        ],
                    ],
                    Yii::$app->user->isGuest ?
                        ['label' => 'Login', 'url' => ['/site/login']] :
                        ['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                            'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']],
                ],
            ]);
            NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; DCMD <?= date('Y') ?></p>
            <p class="pull-right">DCMD</p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
