<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php

$url = Yii::getAlias("@web") . '/img/';

?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body style="background:url(<?= $url ?>fake_news_modificata.png); background-repeat:no-repeat; background-position:center top;-webkit-background-size:">
<?php $this->beginBody() ?>

<header id="header">
    <ul>
        <li><a href='http://localhost:8080'>Home</a></li>
        <?php if (Yii::$app->session->get('isLoggedIn')): ?>
            <li><?= Html::a('Logout', ['account/logout'], ['data-method' => 'post']) ?></li>
            <li style='float:right'><a href='http://localhost:8080/index.php?r=account/DADEFINIRE' id='change-background'>Profilo</a></li>
        <?php else: ?>
            <li style='float:right'><a href='http://localhost:8080/index.php?r=account/login' id='change-background'>Login/Registrazione</a></li>
        <?php endif; ?>
    </ul>
</header>

<div>
    <?= $content ?>
</div>



<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
