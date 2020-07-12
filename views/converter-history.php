<?php
use app\services\Helper;
?>

<div class="cpc-converter-history-wrap">
    <span class="cpc-converter-history-title">Recently Converted</span>
    <div class="cpc-converter-history-content">
        <?php foreach ($history as $key => $row) { ?>
            <div class="cpc-converter-history-row">
                <div class="cpc-converter-history-row-base">
                    <span><?php echo floatval($row->requested_count); ?></span>
                    <span><?php echo $row->converted_from; ?></span>
                    <span>to</span>
                    <span><?php echo floatval($row->rate); ?></span>
                    <span><?php echo $row->converted_to; ?></span>
                </div>
                <div class="cpc-converter-history-dots"></div>
                <div class="cpc-converter-history-when">
                    <span><?php echo Helper::timeElapsedString($row->request_time); ?></span>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
