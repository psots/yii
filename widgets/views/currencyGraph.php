<?php 
use kartik\select2\Select2;
?>
<div class="content">
<div  class="row">
        <div class="col-md-6">
            <?=
                Select2::widget([
                    'id' => 'baseCurrencyType',
                    'name' => 'baseCurrencyType',
                    'value' => '',
                    'data' => $model['baseCurrencyType'],
                    'disabled' => true
                ]);
            ?>
        </div>
        <div class="col-md-6">
            <?=
                Select2::widget([
                    'id' => 'currencyType',
                    'name' => 'currencyType',
                    'value' => '',
                    'data' => $model['currencyType']
                ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="btn-group" role="group" aria-label="...">
                <button type="button" id="option1" class="btn btn-default">Day</button>
                <button type="button" id="option2" class="btn btn-default">Week</button>
                <button type="button" id="option3" class="btn btn-default">Month</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="input-group date">
                <input type="text" id="datePicker" class="form-control">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-th"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <canvas
                id="myChart"
                width="400"
                height="100"
                url="<?php echo yii\helpers\Url::toRoute('/site/chart'); ?>"
            ></canvas>
        </div>
    </div>

    <?php
    $this->registerJs('var currentDate="'. $model['date'] .'"',  \yii\web\View::POS_HEAD);
    $this->registerJs('var dataset='. $model['dataset'] ,  \yii\web\View::POS_HEAD);
    $this->registerJsFile(
        Yii::$app->request->baseUrl.'/js/graph.js',
        [
            'depends' => [
                \yii\web\JqueryAsset::class,
                \zhuravljov\yii\widgets\DatePickerAsset::class,
                \zhuravljov\yii\widgets\MomentAsset::class,
                dosamigos\chartjs\ChartJsAsset::class
            ]
        ]); ?>
</div>