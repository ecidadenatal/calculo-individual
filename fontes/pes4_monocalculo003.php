<?php

if ( isset($processamento_background) ) {

  define("REGEX_ERRO", "|alert\(.(.*).\)|U");

  ob_start();
  header("Content-type: application/json",true);

  register_shutdown_function(function() {

    $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1');
    $E_FATAL  = E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_RECOVERABLE_ERROR;
    $error    = error_get_last();

    $sMensagemBanco = pg_last_error();
    $retorno = (object)array(
      'sucesso' => true,
      'mensagem' => utf8_encode("Processado com sucesso."),
      "erro" => $error
    );
    if (!empty($error) && ( ( $error['type'] & $E_FATAL ) || !empty($sMensagemBanco) ) )  {

      $sErroTecnico = "Mensagem: " . $error['message'];
      $sErroTecnico.= "\nLinha:    " . $error['line'];
      $sErroTecnico.= "\nFonte:    " . $error['file'];

      $retorno = (object)array(
        'sucesso' => false,
        'erro_tecnico' => base64_encode(utf8_encode($sErroTecnico)),
      );

      if ( db_getsession("DB_id_usuario") <> 1) {
        unset($retorno->erro_tecnico);
      }
    }


    $texto =  ob_get_contents();

    $retorno->alertas = array();

    preg_match_all(REGEX_ERRO, $texto, $aOcorrencias);

    foreach ($aOcorrencias[1] as $sValor) {
      $retorno->alertas[] = str_replace("\\n","\n", utf8_encode($sValor) );
    }
    ob_end_clean();
    echo json_encode($retorno);
  });
}
require_once modification('pes4_gerafolha002.php');
