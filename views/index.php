<?php
  use app\ConverterView;
?>
<div class="cpc-converter-container" style="display: none;">
    <h3>Cryptocurrency converter</h3>
        <?php
        ConverterView::view('converter-form', ['currencies' => $currencies, 'exchangeRate' => $exchangeRate])
        ?>

        <?php
        ConverterView::view('converter-history', ['history' => $history])
        ?>

</div>
