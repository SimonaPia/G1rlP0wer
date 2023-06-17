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
use app\models\Testo;
use app\models\Fonte;
use yii\httpclient\Client;
use yii\helpers\Url;
use yii\controllers\FonteController;
use GuzzleHttp\Client as GuzzleClient;

require __DIR__ . '/../vendor/autoload.php';

class GestioneNotiziaController extends Controller
{
    public function actionInserimento()
    {
        $link_notizia=Yii::$app->session->get('link');

        if (stripos($link_notizia, "http") !== false && stripos(substr($link_notizia, -7), ".") !== false) 
        {
            $categoria=$this->categoria();
        }        

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

        $controllo=0;

            if(strstr($contentType, 'image/jpeg'))
            {
                $soggetti=$this->ricercaSoggetti();
                
                $immagine=new Immagine();
                $immagine->metadati=$metaDati;
            }

        Yii::$app->session->set('controllo', $controllo);

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
        
        return $this->render('inserimento');
        }
    }

    //DA TESTARE
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
        $link_notizia=Yii::$app->session->get('link');

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
        $link_notizia=Yii::$app->session->get('link');

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


         if (stripos($link_notizia, "http") !== false) 
         {
             if(stripos(substr($link_notizia, -7), ".") !== false)
                 $categoria=$this->categoria($link_notizia);
 
             $soggetti=$this->ricercaSoggetti();
 
             $dataSoggetti=json_decode($soggetti, true);
             $entity=$dataSoggetti['entity_list'];
 
             $primo=$entity['0'];
             $secondo=$entity['1'];
             $terzo=$entity['2'];
             $quinto=$entity['5'];
 
             $principale=$primo['form'];
             $secondario1=$secondo['form'];
             $secondario2=$terzo['form'];
             $luogo=$quinto['form'];
 
             $sogg=array($principale, $secondario1, $secondario2, $luogo);
 
             $soggetti=json_encode($sogg);
 
             $tempo=$dataSoggetti['time_expression_list'];
             $primo=$tempo['0'];
             $data=$primo['actual_time'];
 
             $data=json_encode($data);
 
             $testo=new Testo();
             $testo->setSoggetti($soggetti);
             $testo->setData($data);
 
         }         
 
         // Creazione dell'istanza del controller di destinazione
         $controller = Yii::$app->createController('fonte')[0];

        // Chiamata alla funzione desiderata del controller di destinazione
        $controller->actionAnalisiFonte();
                  
         $messaggio=$this->segnalazione($link_notizia);

        return $this->render('analisi', ['jsonData' => json_encode($data), 'indice' => json_encode($indice), 'messaggio' => $messaggio]);
    }

    public function ricercaSoggetti()
    {
        $link_notizia=Yii::$app->session->get('notizia');

        $client = new GuzzleClient();

        $response = $client->post('http://api.meaningcloud.com/topics-2.0', [
            'multipart' => [
                [
                    'name'     => 'key',
                    'contents' => '67c9440dc405a5d91f525337bc88baaf'
                ],
                [
                    'name'     => 'url',
                    'contents' => $link_notizia
                ],
                [
                    'name'     => 'lang',
                    'contents' => 'it'  # 2-letter code, like en es fr ...
                ],
                [
                    'name'     => 'tt',
                    'contents' => 'a'                   # all topics
                ]        
            ]
        ]);

        $status = $response->getStatusCode();
        $body = json_decode($response->getBody()->getContents(), true);
        $soggetti=json_encode($body);

        return $soggetti;
    }  

    public function segnalazione($link_notizia)
    {
         
        $logFile = 'segnalazione.log';
            $timestamp = date('Y-m-d H:i:s');
            $logMessage = "[$timestamp] Segnalazione di notizia: $link_notizia" . PHP_EOL;
        
            // Apri il file di log in modalità append
            $handle = fopen($logFile, 'a');
            // Scrivi il messaggio di segnalazione nel file di log
            fwrite($handle, $logMessage);
            // Chiudi il file
            fclose($handle);
        
        $blacklist = array();
        
        $messaggio='';

        if($controllo==1)
        {

            // Aggiungi elementi alla blacklist
            $blacklist[] = 'https://staticfanpage.akamaized.net/wp-content/uploads/sites/34/2023/03/Screenshot-2023-03-26-alle-20.24.23.jpg';
        
            // Verifica se un elemento è presente nella blacklist
            $siteToCheck = $link_notizia;
            if (in_array($siteToCheck, $blacklist)) {
            $messaggio='Il sito è nella blacklist.';
            } else {
             $messaggio='Il sito non è nella blacklist.';
             }
        }
        return $messaggio;
    }

}