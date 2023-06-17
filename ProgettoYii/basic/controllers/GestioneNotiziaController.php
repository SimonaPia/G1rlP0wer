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
<<<<<<< HEAD
        $link_notizia=Yii::$app->session->get('link');

        $categoria=$this->categoria();
        //$dataCategoria=json_decode($categoria, true);

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

        $controllo=0;

        if(Yii::$app->request->post('segnala' && 'b1')!==null)
        {
            $controllo=1;
        }

        $redirectUrl = Url::to(['gestione-notizia/analisi', 'indice' => $indice, 'controllo' => $controllo]);

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


=======
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
                echo 'ciao';
                $this->ricercaSoggetti();
            }

            $this->ricercaSoggetti();

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
>>>>>>> b2fafa1eb30e01264581f1636d39b9bace8f1561
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
<<<<<<< HEAD
        $link_notizia=Yii::$app->session->get('link');
=======
        $link_notizia=Yii::$app->session->get('notizia');
>>>>>>> b2fafa1eb30e01264581f1636d39b9bace8f1561

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

<<<<<<< HEAD
    public function actionAnalisi($indice, $controllo)
    {
        $link_notizia=Yii::$app->session->get('link');
=======
    public function actionAnalisi($indice)
    {
        $link_notizia=Yii::$app->session->get('notizia');
>>>>>>> b2fafa1eb30e01264581f1636d39b9bace8f1561

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

<<<<<<< HEAD
         $categoria=$this->categoria();
         $messaggio=$this->analisi($controllo);
       

    return $this->render('analisi', ['jsonData' => json_encode($data), 'indice' => json_encode($indice),'messaggio'=>$messaggio]);
    }

    public function analisi($controllo)
	{
		$link_notizia=Yii::$app->session->get('link');
=======
         if (stripos($link_notizia, "https") !== false) {
             $categoria=$this->categoria($link_notizia);
             $this->analisi();
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
        var_dump($json_response);
        echo json_encode($json_response);

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
        ];*/

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

        // Verifica se la richiesta è andata a buon fine
        if ($response->isOk) {
        // Ottieni il corpo della risposta come stringa
        $responseBody = $response->content;

        // Decodifica il corpo della risposta JSON
        $data = json_decode($responseBody, true);

        echo json_encode($data);
        } else {
        // La richiesta ha restituito un errore
        echo 'Errore nella richiesta: ' . $response->statusCode;
        }*/
    }

    public function analisi()
	{
		$link_notizia=Yii::$app->session->get('notizia');
>>>>>>> b2fafa1eb30e01264581f1636d39b9bace8f1561
    
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
<<<<<<< HEAD


        function segnalazione($link_notizia)
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
        }
        
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
=======
	}

    
>>>>>>> b2fafa1eb30e01264581f1636d39b9bace8f1561

}
