<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\httpclient\Client;

class Integration extends Model
{
    public $series;
    public $number;
    public $birthDate;
    public $pinfl;

    public function rules()
    {
        return [
            [['series', 'number', 'birthDate'], 'safe'],
            [['pinfl'], 'string', 'length' => 14],
        ];
    }

    private function getHttpClient()
    {
        return new Client([
            'baseUrl' => 'https://payme.z7.uz',
            'requestConfig' => [
                'format' => Client::FORMAT_URLENCODED,
                'headers' => [
                    'Authorization' => 'Basic dGdmdToxMjMwMDEyMyE=',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie' => '_csrf-frontend=b43e39034f368e3e734f94e5cac75d416febb60b68073164de5f89c5ad01af72a%3A2%3A%7Bi%3A0%3Bs%3A14%3A%22_csrf-frontend%22%3Bi%3A1%3Bs%3A32%3A%22UJAX4iBtAqWwA1XRhLRUe0PVgyQNbBxk%22%3B%7D; advanced-frontend=af050d87d54908bb54ccfb19b04961cb'
                ],
            ],
            'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ]
        ]);
    }

    public function checkPassport()
    {
        try {
            $client = $this->getHttpClient();

            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl('integration/seria')
                ->setData([
                    'series' => $this->series,
                    'number' => $this->number,
                    'birthDate' => $this->birthDate
                ])
                ->send();

            if ($response->isOk) {
                return $response->data;
            }

            return false;

        } catch (\Throwable $e) {
            Yii::error("Passport check error: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }

    public function checkPinfl()
    {
        try {
            $client = $this->getHttpClient();

            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl('integration/pin')
                ->setData(['pinfl' => $this->pinfl])
                ->send();

            if ($response->isOk) {
                return $response->data;
            }

            return false;

        } catch (\Throwable $e) {
            Yii::error("PINFL check error: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }



    /**
     * ✅ Foydalanish (Controller misol):
     * 
     * $model = new \app\models\PersonVerificationForm([
     *     'series' => 'AC',
     *     'number' => '0309038',
     *     'birthDate' => '2002-05-05',
     *     'pinfl' => '60505027110012'
     * ]);

     * $result1 = $model->checkPassport();
     * $result2 = $model->checkPinfl();

     * echo "<pre>";
     * print_r($result1);
     * print_r($result2);
     * echo "</pre>";
     */
}