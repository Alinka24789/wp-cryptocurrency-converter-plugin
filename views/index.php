<?php
  use app\ConverterView;

  wp_enqueue_style('cpc_converter_styles', CPC_PLUGIN_URL . 'public/css/style.css');
  wp_enqueue_script( 'cpc_converter_js', CPC_PLUGIN_URL . 'public/js/index.js', array( 'jquery' ), '1.0.0', true );
?>
<div class="cpc-converter-container">
        <?php
        ConverterView::view('converter-form')
        ?>

        <?php
        ConverterView::view('converter-history')
        ?>

</div>
