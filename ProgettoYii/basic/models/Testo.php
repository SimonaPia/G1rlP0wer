<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Testo extends Model
{
    private string $data;
	private string $soggetti;

	public function setSoggetti($soggetti)
	{
		$this->soggetti=$soggetti;
	}

	public function setData($data)
	{
		$this->data=$data;
	}

	public function getSoggetti()
	{
		return $this->soggetti;
	}
}