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
        <p class='titolo'>G1rl P0wer</p>
    </div>

    <div class="body-content">
        <h2>Inserisci il link della notizia</h2>

        <?php $form=ActiveForm::begin(); ?>
        
        <?= $form->field($model, 'Notizia')->textInput(['class'=>'ricerca']) ?>
        <?= Html::submitButton('Verifica notizia', ['class'=>'verifica']) ?>
        <?= Html::submitButton('Segnala notizia', ['name'=>'segnala', 'class'=>'verifica']) ?>
        <?php ActiveForm::end(); ?>

        $form = ActiveForm::begin();

        // Altri campi del form

        echo Html::submitButton('Bottone 1', ['name' => 'segnala', 'value' => 'b1', 'class' => 'btn btn-primary']);
        echo Html::submitButton('Bottone 2', ['name' => 'segnala', 'value' => 'b2', 'class' => 'btn btn-primary']);

        ActiveForm::end();
    </div>

    



</div>
