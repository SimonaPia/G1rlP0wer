<?php

namespace app\controllers;
use yii\g1rlp0wer\Query;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RegistrationForm;
use app\models\Notizia;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model=new Notizia();

        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            //prendo la variabile dal form
            $link_notizia=$model->Notizia;

            //setto una variabile di sessione per poterla usare in vari controller 
            Yii::$app->session->set('notizia', $link_notizia);

            //mi sposto in un'altra pagina
            return $this->redirect(['gestione-notizia/inserimento']);
        } 
        else 
        {
            // Il modulo non ha superato la validazione, visualizza gli errori di validazione
            $errors = $model->getErrors();
            foreach ($errors as $attribute => $errorMessages) 
            {
                foreach ($errorMessages as $errorMessage) 
                {
                    echo "$attribute: $errorMessage<br>";
                }
            }
        }
        return $this->render('index', ['model'=>$model]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
