<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Immagine extends Model
{
	private string $metadati;
	private string $soggetti;

	public function setSoggetti($soggetti)
	{
		$this->soggetti=$soggetti;
	}

	public function setMetadati($metadati)
	{
		$this->metadati=$metadati;
	}

	public function getSoggetti()
	{
		return $this->soggetti;
	}
}