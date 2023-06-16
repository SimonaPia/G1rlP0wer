<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Notizia $model */
/** @var ActiveForm $form */

$this->title = 'G1rl P0wer';
?>

<div class="site-index">
   
    <div class="jumbotron text-center bg-trasparents">
        <p class='titolo'>Speteguless</p>
    </div>

    <div class="body-content">
        <h2>Inserisci il link della notizia</h2>

        <?php $form=ActiveForm::begin(); ?>
        
        <?= $form->field($model, 'Notizia')->textInput(['class'=>'ricerca']) ?>
        <?= Html::submitButton('Verifica notizia', ['class'=>'verifica']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>
