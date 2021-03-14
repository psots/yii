<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\CurrencyExchangeRate;
use app\models\CurrencyExchangeRateForDay;

class CurrencyExchangeRateForm extends Model
{

    public function getDayData($type, $date, $currencyFrom, $currencyTo)
    {
        $data = $labels = [];
        switch($type) {
            case 'day':
                if ($this->isValidDateFormat($date)) {
                    foreach( (array)$this->getCurrencyDay($currencyFrom, $currencyTo, $date) as $row => $value) {
                        if (strpos($row, 'h') === 0) {
                            array_push($data, $value);
                        }
                    }
                    $labels = range(0, count($data) - 1);
                }
            break;
            case 'week':
                $date = explode(':', $date);
                if (
                    $this->isValidDateFormat($date[0]) || 
                    $this->isValidDateFormat($date[1])
                ) {
                    foreach( (array)$this->getCurrencyRange($currencyFrom, $currencyTo, $date[0], $date[1]) as $row => $value) {
                        array_push($data, $value['rate']);
                        array_push($labels, $value['exchange_date']);
                    }
                }
            break;
            case 'month':
                if ($this->isValidDateFormat($date, 'Y-m')) {
                    $date = \DateTime::createFromFormat('Y-m', $date);
                    foreach(
                        (array)$this->getCurrencyRange(
                            $currencyFrom,
                            $currencyTo,
                            $date->modify('first day of this month')->format('Y-m-d'),
                            $date->modify('last day of this month')->format('Y-m-d')
                        )
                    as $row => $value)
                    {
                        array_push($data, $value['rate']);
                        array_push($labels, $value['exchange_date']);
                    }
                }
            break;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [   
                    'label' => 
                        CurrencyList::findOne(['id' => $currencyFrom])->short_name
                        . '/' . CurrencyList::findOne(['id' => $currencyTo])->short_name,
                    'data' => $data
                ]
            ]
        ];
    }

    private function getCurrencyDay(int $currencyIdFrom, int $currencyIdTo, string $date) {
        return CurrencyExchangeRate::find()
            ->where(['exchange_date' => date($date)])
            ->andWhere( ['currency_id_from' => $currencyIdFrom] )
            ->andWhere( ['currency_id_to' => $currencyIdTo] )
            ->asArray()
            ->one();
    }

    private function getCurrencyRange(int $currencyIdFrom, int $currencyIdTo, string $dateFrom, string $dateTo) {
        return CurrencyExchangeRateForDay::find()
            ->where( ['between', 'exchange_date', date($dateFrom), date($dateTo) ])
            ->andWhere( ['currency_id_from' => $currencyIdFrom] )
            ->andWhere( ['currency_id_to' => $currencyIdTo] )
            ->select(['exchange_date', 'rate'])
            ->orderBy([
                'exchange_date' => SORT_ASC,
            ])
            ->asArray()
            ->all();
    }

    private function isValidDateFormat(string $date, string $format = 'Y-m-d')
    {
        $dateObj = \DateTime::createFromFormat($format, $date);
        return $dateObj && $dateObj->format($format) == $date;
    }
}