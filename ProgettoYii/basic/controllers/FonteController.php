<?php

namespace app\controllers;

class FonteController extends \Controller
{
	public function analisi()
	{
<<<<<<< HEAD
		$link_notizia=Yii::$app->session->get('link');

        //$url = "https://www.example.com/index.php?page=home";
=======
		$link_notizia=Yii::$app->session->get('notizia');
    
        $apiKey = '7f131cfc0c77133d0ce81e4cea38e7acdb524ea930e443f31c6ddb9dd158829d';

        
>>>>>>> b2fafa1eb30e01264581f1636d39b9bace8f1561
        $domain = parse_url($link_notizia, PHP_URL_HOST);
        $domain_parts = explode(".", $domain);
        $domain = $domain_parts[count($domain_parts)-2] . "." . $domain_parts[count($domain_parts)-1];

<<<<<<< HEAD
        echo $domain;
=======
        $url='https://www.virustotal.com/api/v3/domains/'.$domain;

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->addHeaders(['x-apikey' => $apiKey])
            ->send();

        $responseData=0;

        if ($response->isOk) {
            $responseData = $response->data;
        } else {
            // Gestione dell'errore nella richiesta
            echo "Errore nella richiesta di ottenimento delle informazioni dell'URL.";
        }


        $data1 = $responseData['data'];

        $attributes = $data1['attributes'];
        //$lastHtpResponseHeaders = $attributes['last_http_response_headers'];
        //$contentType=$lastHtpResponseHeaders['Content-Type'];

        $lastAnalysisStats=$attributes['last_analysis_stats'];
        $harmless=$lastAnalysisStats['harmless'];
        $malicious=$lastAnalysisStats['malicious'];
        $suspicious=$lastAnalysisStats['suspicious'];
        $undetected=$lastAnalysisStats['undetected'];
        $timeout=$lastAnalysisStats['timeout'];

        $categories=$attributes['categories'];
        $argomenti=implode(', ', $categories);

        $indice=($harmless+$malicious+$suspicious+$undetected+$timeout)/5;
>>>>>>> b2fafa1eb30e01264581f1636d39b9bace8f1561

        $fonte=new Fonte();

        $fonte->Fonte=$domain;
<<<<<<< HEAD
=======
        $fonte->Indice=$indice;
        $fonte->Argomento=$argomenti;
>>>>>>> b2fafa1eb30e01264581f1636d39b9bace8f1561
        /*$fonte->Indice=$indice;
        $notizia->Categoria=$contentType;
        $notizia->Argomento=$argomenti;
        $notizia->Incongruenze=$incongruenze;*/


<<<<<<< HEAD
        $redirectUrl = Url::to(['fonte/visualizza');

        if ($notizia->save()) {
            return $this->redirect($redirectUrl);
=======
        //$redirectUrl = Url::to(['fonte/visualizza');

        if ($fonte->save()) {
            //return $this->redirect($redirectUrl);
>>>>>>> b2fafa1eb30e01264581f1636d39b9bace8f1561
        }
        else 
        {
            // Salvataggio fallito, visualizza gli errori
<<<<<<< HEAD
            $errors = $notizia->getErrors();
=======
            $errors = $fonte->getErrors();
>>>>>>> b2fafa1eb30e01264581f1636d39b9bace8f1561
            var_dump($errors);
        }
        
        //return $this->render('inserimento');
	}
}
