<?php
wp_enqueue_style('cpc_converter_input_styles', CPC_PLUGIN_URL . 'public/css/converter-input.css');
?>

<div class="cpc-converter-group-input">
    <div class="cpc-rate-value-wrap">
        <input type="text" placeholder="1" class="cpc-rate-value" onchange="">
    </div>
    <div class="cpc-dropdown-box">
        <button onclick="openCurrenciesList(event)" class="cpc-dropdown-button">
            <img src="https://currencio.co/i/btc/logo/64x64/logo.png">
            <span>BTC</span>
        </button>
        <div class="cpc-dropdown-content">
            <div class="cpc-dropdown-search-wrap">
                <input type="text" placeholder="Search.." class="cpc-dropdown-search" onkeyup="filterFunction(event)">
            </div>

            <ul class="cpc-dropdown-list">
                <li>
                    <img src="https://currencio.co/i/btc/logo/64x64/logo.png">
                    USD US - Dollar
                </li>
                <li>
                    <img src="https://currencio.co/i/btc/logo/64x64/logo.png">
                    AUD - Australian Dollar
                </li>
                <li>
                    <img src="https://currencio.co/i/btc/logo/64x64/logo.png">
                    BRL - Brazil Real
                </li>
                <li>
                    <img src="https://currencio.co/i/btc/logo/64x64/logo.png">
                    CAD - Canadian Dollar
                </li>
            </ul>
        </div>
    </div>
</div>
