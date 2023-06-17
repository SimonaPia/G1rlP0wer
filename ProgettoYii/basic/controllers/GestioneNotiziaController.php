<?php

namespace app\controllers;

use yii\g1rlp0wer\Query;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Notizia;
use app\models\Immagine;
use app\models\Fonte;
use yii\httpclient\Client;
use yii\helpers\Url;
use yii\controllers\FonteController;

class GestioneNotiziaController extends Controller
{
    public function actionInserimento()
    {
        $link_notizia=Yii::$app->session->get('notizia');

        if (stripos($link_notizia, "https") !== false) {
             $categoria=$this->categoria();
             echo 'ciao';
         }        
        
        //$dataCategoria=json_decode($categoria, true);

        echo 'ciao';

        if(!empty($categoria))
        {
            $data1 = $categoria['data'];

            $attributes = $data1['attributes'];
            $lastHtpResponseHeaders = $attributes['last_http_response_headers'];
            $contentType=$lastHtpResponseHeaders['Content-Type'];
            $metaDati=$lastHtpResponseHeaders['Last-Modified'];

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

            echo $contentType;

            if(strstr($contentType, 'image/jpeg'))
            {
                $soggetti=$this->ricercaSoggetti();
                
                $immagine=new Immagine();
                $immagine->metadati=$metaDati;
            }

            
            $redirectUrl = Url::to(['gestione-notizia/analisi', 'indice' => $indice, 'soggetti' => $soggetti]);

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

    public function actionAnalisi($indice, $soggetti)
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
            // Creazione dell'istanza del controller di destinazione
            $controller = Yii::$app->createController('fonte')[0];

            // Chiamata alla funzione desiderata del controller di destinazione
            $controller->actionAnalisiFonte();
         }         


         return $this->render('analisi', ['jsonData' => json_encode($data), 'indice' => json_encode($indice)]);
    }

    public function ricercaSoggetti()
    {
        $link_notizia=Yii::$app->session->get('notizia');

        //$image_url = 'https://docs.imagga.com/static/images/docs/sample/japan-605234_1280.jpg';
        $api_credentials = array(
        'key' => 'acc_21314e94b826ef7',
        'secret' => 'b959fa4c880b462a844ba43daa8e09e9'
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.imagga.com/v2/tags?image_url='.$link_notizia);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_USERPWD, $api_credentials['key'].':'.$api_credentials['secret']);

        $response = curl_exec($ch);
        curl_close($ch);

        $json_response = json_decode($response);
        //var_dump($json_response);
        $soggetti=json_encode($json_response);
        

        /*$client = new Client();

        // URL dell'API di Google Cloud Vision
        $url = 'https://vision.googleapis.com/v1/images:annotate?key=AIzaSyCoqCv86VXhWoIdCZnRLAEnrf6D62SU9MM';

        // Costruisci l'array del corpo della richiesta
        $requestBody = [
            'requests' => [
                [
                    'image' => [
                        'source' => [
                            'imageUri' => $link_notizia,
                        ],
                    ],
                    'features' => [
                        [
                            'type' => 'LABEL_DETECTION',
                            'maxResults' => 10,
                        ],
                    ],
                ],
            ],
        ];

        // Effettua la richiesta POST utilizzando Guzzle
        /*$response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $requestBody,
        ]);*/

        // Esegui la richiesta POST
        /*$response = $client->createRequest()
                    ->setMethod('POST')
                    ->setUrl($url)
                    ->setData($requestBody)
                    ->send();

        $soggetti='';

        // Verifica se la richiesta è andata a buon fine
        if ($response->isOk) {
            // Ottieni il corpo della risposta come stringa
            $soggetti = $response->content;

            // Decodifica il corpo della risposta JSON
            //$data = json_decode($responseBody, true);

            //$soggetti=json_encode($data);
        } 
        else 
        {
        // La richiesta ha restituito un errore
        echo 'Errore nella richiesta: ' . $response->statusCode;
        }*/

        return $soggetti;
    }  

}
