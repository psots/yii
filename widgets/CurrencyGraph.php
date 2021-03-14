<?php
namespace app\widgets;

use app\models\CurrencyExchangeRateForm;
use app\models\CurrencyList;

use Yii;

class CurrencyGraph extends \yii\bootstrap\Widget
{

    /**
     * {@inheritdoc}
     */
    public function run()
    {

        $currencylayerParams = Yii::$app->params['currencylayer'];

        $currencyList = CurrencyList::find()
            ->where( ['is_using' => true] )
            ->select(['id', 'short_name', 'name'])
            ->asArray()
            ->all();

        $currencySelectList = [];
        $baseCurrencySelectList = [];
        foreach ($currencyList as $key => $value) {
            $value['short_name'] != $currencylayerParams['base_currency']
                ? $currencySelectList[$value['id']] = $value['name']
                : $baseCurrencySelectList[$value['id']] = $value['name'];
        }

        $data = [
            'baseCurrencyType' => $baseCurrencySelectList,
            'currencyType' => $currencySelectList,
            'date' => (new \DateTime())->format('Y-m-d'),
            'dataset' => json_encode([
                'type' => 'line',
                'options' => [
                    'height' => 50,
                    'width' => 400
                ],
                'data' => (new CurrencyExchangeRateForm())
                    ->getDayData(
                        'day',
                        (new \DateTime())->format('Y-m-d'),
                        array_key_first($baseCurrencySelectList),
                        array_key_first($currencySelectList)
                    )
            ])
        ];
        return $this->render('currencyGraph',[
            'model' => $data 
        ]);
    }
}
