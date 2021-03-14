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

use Yii;

class InitController extends Controller
{
    public function actionIndex()
    {
        $currencylayerParams = Yii::$app->params['currencylayer'];
        $client = new Client(['baseUrl' => $currencylayerParams['url']]);

        $response = $client->get(
            'list',
            [
                'access_key' => $currencylayerParams['access_key']
            ]
        )->send()->getData();

        if(!$response['success']) {
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $activCount = 5;
        $currencylayerParams = Yii::$app->params['currencylayer'];

        $transaction = CurrencyList::getDb()->beginTransaction();
        try {
            foreach($response['currencies'] as $shortName => $fullName) {
                $model = new CurrencyList();
                $model->name = $fullName;
                $model->short_name = $shortName;
                if (--$activCount >= 0 || $shortName == $currencylayerParams['base_currency']) {
                    $model->is_using = true;
                }
                $model->save();
            }
            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            return ExitCode::UNSPECIFIED_ERROR;
        } catch(\Throwable $e) {
            $transaction->rollBack();
            return ExitCode::UNSPECIFIED_ERROR;
        }

        return ExitCode::OK;
    }
}
