<div class="cpc-converter-group-input">
    <div class="cpc-rate-value-wrap">
        <input type="text" placeholder="1" value="<?php echo $rate; ?>" onkeyup="handleRateChange(event)" class="cpc-rate-value" onchange="">
    </div>
    <div class="cpc-dropdown-box">
        <button onclick="openCurrenciesList(event)" class="cpc-dropdown-button">
            <span><?php echo $currency;?></span>
        </button>
        <div class="cpc-dropdown-content">
            <div class="cpc-dropdown-search-wrap">
                <input type="text" placeholder="Search.." class="cpc-dropdown-search" onkeyup="filterFunction(event)">
            </div>

            <ul class="cpc-dropdown-list">
                <?php foreach ($currencies as $key => $item) { ?>
                    <li data-currencysymbol="<?php echo $item->currency_symbol; ?>"
                        data-isactive="<?php echo ($item->currency_symbol == $currency) ? 1 : 0; ?>"
                        onclick="markAsActive(event); fetchRate(event)">
                        <?php echo $item->currency_symbol; ?> - <?php echo $item->currency_name ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
