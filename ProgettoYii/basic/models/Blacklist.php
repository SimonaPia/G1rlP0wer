<?php

namespace app\models;

use Yii;
use yii\base\Model;

$blacklistFile = 'blacklist.txt';
// Verifica se il file di blacklist esiste, altrimenti crealo
if (!file_exists($blacklistFile)) {
    touch($blacklistFile);
}