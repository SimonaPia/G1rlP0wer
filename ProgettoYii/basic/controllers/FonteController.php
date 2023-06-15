<?php

namespace app\controllers;

class FonteController extends \Controller
{
	public function analisi()
	{
		$link_notizia=Yii::$app->session->get('link');

        //$url = "https://www.example.com/index.php?page=home";
        $domain = parse_url($link_notizia, PHP_URL_HOST);
        $domain_parts = explode(".", $domain);
        $domain = $domain_parts[count($domain_parts)-2] . "." . $domain_parts[count($domain_parts)-1];

        echo $domain;

        $fonte=new Fonte();

        $fonte->Fonte=$domain;
        /*$fonte->Indice=$indice;
        $notizia->Categoria=$contentType;
        $notizia->Argomento=$argomenti;
        $notizia->Incongruenze=$incongruenze;*/


        $redirectUrl = Url::to(['fonte/visualizza');

        if ($notizia->save()) {
            return $this->redirect($redirectUrl);
        }
        else 
        {
            // Salvataggio fallito, visualizza gli errori
            $errors = $notizia->getErrors();
            var_dump($errors);
        }
        
        //return $this->render('inserimento');
	}
}
