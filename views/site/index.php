<?php
use dosamigos\chartjs\ChartJs;
use yii\widgets\DetailView;
use app\models\CurrencyList;
use app\models\CurrencyExchangeRateForm;
use app\widgets\CurrencyGraph;
/* @var $this yii\web\View */

$this->title = 'Yii finance';
$model = new CurrencyExchangeRateForm();
?>


<div class="site-index">

    <div class="body-content">

    <?= CurrencyGraph::widget(); ?>
    </div>
</div>
