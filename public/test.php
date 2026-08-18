<?php

session_start();

require_once '../app/Core/helpers.php';

echo config('name');

echo "<br>";

echo url('users');