<?php

session_start();
session_destroy();
header("Location: /cafeteria/login.php");
return;
