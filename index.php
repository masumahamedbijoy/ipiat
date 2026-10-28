<?php
require_once 'config.php';
if (!is_logged_in()) { header('Location: login.php'); exit; }
header('Location: dashboard.php');
exit;
