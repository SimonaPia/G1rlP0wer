<?php

namespace app\models;

use Yii;
use yii\base\Model;

$blacklistFile = 'blacklist.txt';
// Verifica se il file di blacklist esiste, altrimenti crealo
if (!file_exists($blacklistFile)) {
    touch($blacklistFile);
<<<<<<< HEAD
}
=======
}

>>>>>>> 4133c098a6bbac69331ab3e826822db5ccd86679
