<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

use yii\httpclient\Client;

use app\models\CurrencyList;

use app\models\CurrencyExchangeRate;

use Yii;

// Команда загрузки данных
class LoadController extends Controller
{
    public function actionIndex()
    {

        // TODO: Мой тарифный план не поддерживает смену базовой валюты (только USD)

        $currencylayerParams = Yii::$app->params['currencylayer'];

        $currencyList = CurrencyList::find()
            ->where( ['is_using' => true] )
            ->andWhere(['not', ['short_name' => $currencylayerParams['base_currency']]] )
            ->select(['id', 'short_name'])
            ->asArray()
            ->all();

        $currencyShortName = array_reduce(
            $currencyList,
            function($prev, $next) {
                array_push($prev, $next['short_name']);
                return $prev;
            },
            []
        );
        
        $client = new Client(['baseUrl' => $currencylayerParams['url']]);

        // foreach($currencyList as $currency) {
        //     $articleResponse = $client->get(
        //         'live',
        //         [
        //             'access_key' => $currencylayerParams['access_key'],
        //             'source' => $currency,
        //             'currencies' => implode(',', $currencyShortName)
        //         ]
        //     )->send()->getData();
        // }


        $response = $client->get(
            'live',
            [
                'access_key' => $currencylayerParams['access_key'],
                'currencies' => implode(',', $currencyShortName)
            ]
        )->send()->getData();


        if(!$response['success']) {
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $currentHour = date("H");
        $currentDate = date("Y-m-d");

        $baseCurrencyId = CurrencyList::findOne(['short_name' => $currencylayerParams['base_currency']])->id;

        foreach($response['quotes'] as $currencyName => $currencyValue) {
            $currencyName = mb_substr($currencyName, 3, 3);
            $currencyIdTo = array_reduce(
                $currencyList,
                function($prev, $next) use ($currencyName) {
                    if ($next['short_name'] == $currencyName) {
                        $prev = $next['id'];
                    }
                    return $prev;
                },
                NULL
            );
    
            $model = CurrencyExchangeRate::findOne(
                [
                    'exchange_date' => date("Y-m-d"),
                    'currency_id_from' => $baseCurrencyId,
                    'currency_id_to' => $currencyIdTo
                ]
            );

            if (!$model) {
                $model = new CurrencyExchangeRate();
                $model->exchange_date = $currentDate;
                $model->currency_id_from = $baseCurrencyId;
                $model->currency_id_to = $currencyIdTo;
            }
            $model["h$currentHour"] = $currencyValue;
            $model->save();
        }

        return ExitCode::OK;
    }
}
