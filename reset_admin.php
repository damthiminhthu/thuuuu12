<?php
$plain = "123456";
$hash = password_hash($plain, PASSWORD_BCRYPT);
echo "Mã hoá bcrypt mới: <br>" . $hash;
?>
