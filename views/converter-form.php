<?php

use app\ConverterView;

?>

<div class="cpc-converter-form-wrap" id="cpcConverterForm">
    <div id="cpcConverterInputFrom">
        <?php
        ConverterView::view('converter-input',
            ['currencies' => $currencies, 'currency' => $exchangeRate['from'], 'rate' => $exchangeRate['count']]);
        ?>
    </div>
    <div onclick="fetchRate(event, true)">
        <a class="cpc-converter-change-rate-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="22" viewBox="0 0 16 22">
                <path d="M0 6.875v-3.75h10.82V0L16 5l-5.18 5V6.875H0zM16 18.875v-3.75H5.18V12L0 17l5.18 5v-3.125H16z"></path>
            </svg>
        </a>
    </div>
    <div id="cpcConverterInputTo">
        <?php
        ConverterView::view('converter-input',
            ['currencies' => $currencies, 'currency' => $exchangeRate['to'], 'rate' => $exchangeRate['rate']]);
        ?>
    </div>
</div>
