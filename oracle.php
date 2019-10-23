<?php

$oracle_bd = "(DESCRIPTION = 
  (ADDRESS_LIST = 
    (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.0.200)(PORT = 1521)) 
  ) 
  (CONNECT_DATA = 
    (SERVICE_NAME = consinco) 
  ) 
) ";


$ora_conexao = oci_connect('consinco', 'consinco', $oracle_bd);

if (!$ora_conexao) {
  $e = oci_error();
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  die();
}


