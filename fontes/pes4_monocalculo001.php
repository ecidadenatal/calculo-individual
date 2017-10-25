<?php
require_once modification('pes4_gerafolha001.php');
?>
<script>
  function abreProcessamentoCalculo() {

    document.form1.action             = 'pes4_monocalculo002.php';

    var oJanela = js_OpenJanelaIframe(
      "",
      "db_calculo",
      "",
      "Cálculo Financeiro Individual",
      true
    );

    oJanela.setAltura("calc(100% - 10px)");
    oJanela.setLargura("calc(100% - 10px)");
    oJanela.hide = function () {

      if ( $('Jandb_calculo') ) {
        $('Jandb_calculo').remove();
      }
      delete(window.db_calculo);

      document.form1.action = '';
      document.form1.target = '';
    }

    document.form1.target = 'IFdb_calculo';
    document.form1.submit();

  }
</script>
