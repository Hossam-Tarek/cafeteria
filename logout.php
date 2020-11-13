<?php

session_start();
session_destroy();
header("Location: /cafeteria/index.php");
return;
