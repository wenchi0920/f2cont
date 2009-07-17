<?php
echo (get_magic_quotes_gpc() ? stripslashes($_GET['link']) : $_GET['link']);
?>