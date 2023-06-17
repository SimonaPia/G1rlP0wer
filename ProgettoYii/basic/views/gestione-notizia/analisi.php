<?php
/** @var yii\web\View $this */
use yii\controller\FonteController;
?>

<div class='notizia'>
<h1>gestione-notizia/inserimento</h1>

<div>
    <?php
        $data=json_decode($jsonData, true);

        if(empty($data))
        {
            if($indice<80)
                $risultato='notizia fake';
            else
                $risultato='notizia vera';
        }
        else 
        {
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
        }


        
        
    ?>
    <p>
<<<<<<< HEAD
        Il link inserito ÔøΩ <?= $risultato?>
        ed ha indice <?= $indice?>
        perci√≤ <?= $messaggio?>
=======
        Il link inserito Ë <?= $risultato?>
        ed ha indice <?= $indice?>

>>>>>>> b2fafa1eb30e01264581f1636d39b9bace8f1561
        
    </p>
</div>
</div>
