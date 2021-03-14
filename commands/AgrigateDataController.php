<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

use app\models\CurrencyExchangeRate;
use app\models\CurrencyExchangeRateForDay;

class AgrigateDataController extends Controller
{

    public function actionIndex()
    {
        $currencyExchangeRate = CurrencyExchangeRate::find()
            ->where([
                'exchange_date' => date("Y-m-d"),
            ])
            ->all();

        foreach ($currencyExchangeRate as $key => $value) {
            $model = CurrencyExchangeRateForDay::findOne(
                [
                    'exchange_date' => date("Y-m-d"),
                    'currency_id_from' => $value->currency_id_from,
                    'currency_id_to' => $value->currency_id_to
                ]
            );
            if (!$model) {
                $model = new CurrencyExchangeRateForDay();
                $model->exchange_date = date("Y-m-d");
                $model->currency_id_from = $value->currency_id_from;
                $model->currency_id_to = $value->currency_id_to;
            }
            $max = 0;
            foreach( $value as $fieldKey => $field) {
                if (strpos($fieldKey, 'h') === 0 && $max < $field) {
                    $max = $field;
                }
            }

            $model->rate = $max;
            $model->save();
        }
        return ExitCode::OK;
    }
}
