<?php

namespace app\models;

use Yii;

class Immagine extends Models
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