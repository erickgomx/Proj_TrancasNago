<?php
// remover_sessao.php
session_start();
session_unset();
session_destroy();
header('Location: ../index.html');
exit;
