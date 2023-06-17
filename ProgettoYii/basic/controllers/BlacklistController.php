<?php

namespace app\controllers;
use yii\g1rlp0wer\Query;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Notizia;
use app\models\Fonte;
use yii\httpclient\Client;
use yii\helpers\Url;

class BlacklistController {
    private $model;
    private $view;

    public function __construct($model, $view) {
        $this->model = $model;
        $this->view = $view;
    }

    public function addToBlacklist($url) {
        // Aggiunge l'URL alla blacklist
        $this->model->addToBlacklist($url);
    }

    public function checkBlacklist($url) {
        // Verifica se l'URL è presente nella blacklist
        $isInBlacklist = $this->model->isInBlacklist($url);
        // Visualizza il risultato utilizzando la view
        $this->view->displayResult($isInBlacklist);
    }
}
