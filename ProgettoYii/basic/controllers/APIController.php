<?php

namespace app\controllers;

class APIController extends yii\rest\Controller
{
    public function actionVerifica()
    {
        
        return $this->render('verifica');
    }

}
