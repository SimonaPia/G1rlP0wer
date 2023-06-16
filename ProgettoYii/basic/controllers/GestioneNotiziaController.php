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

class GestioneNotiziaController extends Controller
{
    public function actionInserimento()
    {
        $link_notizia=Yii::$app->session->get('notizia');

        if (stripos($link_notizia, "https") !== false) {
             $categoria=$this->categoria();
         }        
        
        //$dataCategoria=json_decode($categoria, true);

        if(!empty($categoria))
        {
            $data1 = $categoria['data'];

            $attributes = $data1['attributes'];
            $lastHtpResponseHeaders = $attributes['last_http_response_headers'];
            $contentType=$lastHtpResponseHeaders['Content-Type'];

            $lastAnalysisStats=$attributes['last_analysis_stats'];
            $harmless=$lastAnalysisStats['harmless'];
            $malicious=$lastAnalysisStats['malicious'];
            $suspicious=$lastAnalysisStats['suspicious'];
            $undetected=$lastAnalysisStats['undetected'];
            $timeout=$lastAnalysisStats['timeout'];

            $categories=$attributes['categories'];
            $argomenti=implode(', ', $categories);

            $indice=($harmless+$malicious+$suspicious+$undetected+$timeout)/5;

            $incongruenze=$this->incongruenze($indice);

            $notizia=new Notizia();

            $notizia->Notizia=$link_notizia;
            $notizia->Indice=$indice;
            $notizia->Categoria=$contentType;
            $notizia->Argomento=$argomenti;
            $notizia->Incongruenze=$incongruenze;


            $redirectUrl = Url::to(['gestione-notizia/analisi', 'indice' => $indice]);

            if ($notizia->save()) {
                return $this->redirect($redirectUrl);
            }
            else 
            {
                // Salvataggio fallito, visualizza gli errori
                $errors = $notizia->getErrors();
                var_dump($errors);
            }
        }
        /*else
        {
            $client = new Client();

            $url='https://factchecktools.googleapis.com/v1alpha1/claims:search';

            $params=array(
                'key'=>'AIzaSyCoqCv86VXhWoIdCZnRLAEnrf6D62SU9MM',
                'query'=>$link_notizia
            );

            $stringaQuery=http_build_query($params);

            $requestUrl=$url.'?'.$stringaQuery;

            $response = $client->createRequest()
                        ->setMethod('GET')
                        ->setUrl($requestUrl)
                        ->send();

             if ($response->getStatusCode() == 200) {
                 $data = $response->getData();
             } else {
                 echo 'Richiesta fallita con ' . $response->getStatusCode() . ': ' . $response->getContent();
             }

             $claims = $data['claims'];
            $claim = $claims[0];

            $claimReview = $claim['claimReview'];
            $review = $claimReview[0];

            $publisher = $review['publisher'];
            $publisherName = $publisher['name'];
            $publisherSite = $publisher['site'];

            $url = $review['url'];
            $title = $review['title'];
            $reviewDate = $review['reviewDate'];

            $textualRating = $review['textualRating'];
            $risultato=json_encode($textualRating);

            $languageCode = $review['languageCode'];

            //$this->analisiNotizia($url);
        }*/
        
        $indice=50;
        $redirectUrl = Url::to(['gestione-notizia/analisi', 'indice' => $indice]);
        return $this->redirect($redirectUrl);

        return $this->render('inserimento');
    }

    public function analisiNotizia($url)
    {

        $client = new Client();

        $url1='https://api.diffbot.com/v3/analyze';

        $params=array(
                'url'=>$url,
                'token'=>'5909c4af6a124abb1eed516fd982d2be'
            );

            $stringaQuery=http_build_query($params);

            $requestUrl=$url1.'?'.$stringaQuery;

            echo $requestUrl;

            $response = $client->createRequest()
                        ->setMethod('GET')
                        ->setUrl($requestUrl)
                        ->send();

             if ($response->getStatusCode() == 200) {
                 $data = $response->getData();
             } else {
                 echo 'Richiesta fallita con ' . $response->getStatusCode() . ': ' . $response->getContent();
             }
    }

    public function incongruenze($indice)
    {
        if($indice<20)
            return true;
        else
            return false;
    }

    public function categoria()
    {
        $link_notizia=Yii::$app->session->get('notizia');

        $urlId = rtrim(strtr(base64_encode($link_notizia), '+/', '-_'), '=');
    
        $apiKey = '7f131cfc0c77133d0ce81e4cea38e7acdb524ea930e443f31c6ddb9dd158829d';

        $url='https://www.virustotal.com/api/v3/urls/'.$urlId;

        $client = new Client(['baseUrl' => 'https://www.virustotal.com/api/v3/urls/']);
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

         return $responseData;
    }

    public function actionAnalisi($indice)
    {
        $link_notizia=Yii::$app->session->get('notizia');

        $client = new Client();

        $url='https://factchecktools.googleapis.com/v1alpha1/claims:search';

        $params=array(
            'key'=>'AIzaSyCoqCv86VXhWoIdCZnRLAEnrf6D62SU9MM',
            'query'=>$link_notizia
        );

        $stringaQuery=http_build_query($params);

        $requestUrl=$url.'?'.$stringaQuery;

        $response = $client->createRequest()
                    ->setMethod('GET')
                    ->setUrl($requestUrl)
                    ->send();

         if ($response->getStatusCode() == 200) {
             $data = $response->getData();
         } else {
             echo 'Richiesta fallita con ' . $response->getStatusCode() . ': ' . $response->getContent();
         }

         if (stripos($link_notizia, "https") !== false) {
             $categoria=$this->categoria($link_notizia);
             $this->analisi();
         }         


         return $this->render('analisi', ['jsonData' => json_encode($data), 'indice' => json_encode($indice)]);
    }

    public function analisi()
	{
		$link_notizia=Yii::$app->session->get('notizia');
    
        $apiKey = '7f131cfc0c77133d0ce81e4cea38e7acdb524ea930e443f31c6ddb9dd158829d';

        
        $domain = parse_url($link_notizia, PHP_URL_HOST);
        $domain_parts = explode(".", $domain);
        $domain = $domain_parts[count($domain_parts)-2] . "." . $domain_parts[count($domain_parts)-1];

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

        $fonte=new Fonte();

        $fonte->Fonte=$domain;
        $fonte->Indice=$indice;
        $fonte->Argomento=$argomenti;
        /*$fonte->Indice=$indice;
        $notizia->Categoria=$contentType;
        $notizia->Argomento=$argomenti;
        $notizia->Incongruenze=$incongruenze;*/


        //$redirectUrl = Url::to(['fonte/visualizza');

        if ($fonte->save()) {
            //return $this->redirect($redirectUrl);
        }
        else 
        {
            // Salvataggio fallito, visualizza gli errori
            $errors = $fonte->getErrors();
            var_dump($errors);
        }
        
        //return $this->render('inserimento');
	}

    

}
