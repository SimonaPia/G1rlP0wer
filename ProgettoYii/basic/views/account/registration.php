<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RegistrationForm */
/** @var yii\bootstrap5\ActiveForm $form */

$this->title = 'Registrazione';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class='registration'>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to register:</p>

    <div class="registration-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'nome')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'cognome') ?>

        <?= $form->field($model, 'email') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Registrati', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <div class="offset-lg-1" style="color:#999;">
            Se non ti sei registrata/o allora clicca su <a href='http://localhost:8080/index.php?r=account/login'>Login</a>.<br>
            To modify the username/password, please check out the code <code>app\models\User::$users</code>.
        </div>
    </div>
</div>
