<?php
/*
 * --------------------------------------------------------------------------
 * @file      web0null_attribute_price_updater.js.php
 * @date      18.10.17
 *
 *
 * LICENSE:   Released under the GNU General Public License
 * --------------------------------------------------------------------------
 */
//BOF - web0null_attribute_price_updater
if (defined('MODULE_WEB0NULL_ATTRIBUTE_PRICE_UPDATER_STATUS') && MODULE_WEB0NULL_ATTRIBUTE_PRICE_UPDATER_STATUS == 'true') {
  if (basename($PHP_SELF) == FILENAME_PRODUCT_INFO) {
?>
<script type="text/javascript">
var attributePriceUpdater;
(function ($) {
  attributePriceUpdater = {
    calculate: function (This) {
      var viewAdditional = <?php echo MODULE_WEB0NULL_ATTRIBUTE_PRICE_UPDATER_ADDITIONAL; ?>,
          updateOrgPrice = <?php echo MODULE_WEB0NULL_ATTRIBUTE_PRICE_UPDATER_UPDATE_PRICE; ?>,
          summe = 0,
          attrvpevalue = 0,
          symbolLeft = '',
          symbolRight = '',
          data = This.data('attrdata'),
          el = $('div[id^="optionen' + data.pid + '"] select').length ? ' option:selected' : ' input:checked';
      $.each($('div[id^="optionen' + data.pid + '"]' + el), function () {
        if (!$(this).parents('div[id^="optionen' + data.pid + '"] [id^="pmatrix_v"]').attr('style')) {
          data = $(this).data('attrdata');
          if (data.aprice != 0) {
            if (data.prefix == '-') {
              summe -= data.aprice;
            } else if (data.prefix == '+') {
              summe += data.aprice;
            } else if (data.prefix == '=') {
              summe += data.aprice - data.gprice;
            }
            attrvpevalue += data.attrvpevalue;
          }
        }
      });
      var zwischensumme = summe;
      var newPrice = (Math.round((summe + data.gprice) * 100) / 100).toFixed(2).toString().replace(/[.]/, ',');
      var oldPrice = (Math.round((summe + data.oprice) * 100) / 100).toFixed(2).toString().replace(/[.]/, ',');
      var newOptionsPrice = zwischensumme.toFixed(2).toString().replace(/[.]/,',');
      
      if (data.vpevalue !== false) {
        var newVpePrice = (Math.round((summe + data.gprice) / (data.vpevalue + attrvpevalue) * 100) / 100).toFixed(2).toString().replace(/[.]/, ',');
      }
      if (data.cleft) {
        symbolLeft = data.cleft + '&nbsp;';
      }
      if (data.cright) {
        symbolRight = '&nbsp;' + data.cright;
      }
      if (viewAdditional) {
        //$('div[id^="optionen' + data.pid + '"] .calculatePriceUpdater span.cuPrice').html('&nbsp;' + symbolLeft + newPrice + symbolRight);
        $('#optionsprice' + data.pid).html(symbolLeft + newOptionsPrice + symbolRight);
        $('#totalprice' + data.pid).html(symbolLeft + newPrice + symbolRight);   
        if (data.vpevalue !== false) {
          $('div[id^="optionen' + data.pid + '"] .calculatePriceUpdater span.cuVpePrice').html(symbolLeft + newVpePrice + symbolRight + data.protext + data.vpetext);
        }
      } else {
        $(".calculatePriceUpdater").remove();
      }
      if (updateOrgPrice) {
        <?php if (strpos(CURRENT_TEMPLATE, 'tpl_modified') !== false) { ?>
        $('.pd_summarybox .pd_price .standard_price').html(symbolLeft + newPrice + symbolRight);
        $('.pd_summarybox .pd_price .new_price').html(data.onlytext + symbolLeft + newPrice + symbolRight);
        $('.pd_summarybox .pd_price .old_price').html(data.insteadtext + symbolLeft + oldPrice + symbolRight);
        if (data.vpevalue !== false) {
          $('.pd_summarybox .pd_vpe').html(symbolLeft + newVpePrice + symbolRight + data.protext + data.vpetext);
        }
        <?php } else { ?>
        $('.productprice .standard_price').html(symbolLeft + newPrice + symbolRight);
        $('.productprice .productNewPrice').html(data.onlytext + symbolLeft + newPrice + symbolRight);
        $('.productprice .productOldPrice').html(data.insteadtext + symbolLeft + oldPrice + symbolRight);
        if (data.vpevalue !== false){
          $('p.productVpePrice').html(symbolLeft + newVpePrice + symbolRight + data.protext + data.vpetext);
        }
        <?php } ?> 
      }
    },
    calculateAll: function () {
      $.each($('div[id^="optionen"] input[type=radio]:checked, div[id^="optionen"] input[type=checkbox], div[id^="optionen"] option'), function () {
        attributePriceUpdater.calculate($(this));
      });
    }
  };
  $(document).ready(function () {
    attributePriceUpdater.calculateAll();
    $(".calculatePriceUpdater").show();
    $('div[id^="optionen"] select').on('change click', function () {
      attributePriceUpdater.calculate($('option', this));
    });
    $('div[id^="optionen"] input').on('change click', function () {
      attributePriceUpdater.calculate($(this));
    });
  });
})(jQuery);
</script>
<?php
  }
}
?>
<style type="text/css">
.calculatePriceUpdater {
  display    : none;
  margin     : 10px 2px;
  font-size  : 80%;
  line-height: 15px;
}  
</style>