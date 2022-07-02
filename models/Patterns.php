<?php
// Users
$regUsername = "^\w{2,25}$";
$regName = "^[A-ZČĆŽŠĐ][a-zčćžšđ]{2,15}$";
$regEmail = "^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$";
$regPass = "^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,30}$";
$regAddress = "^[#.0-9a-zA-Z\s,-]{2,50}$";
$regPhone = "^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$";
// Gloves
$regGloveName = "^[\w ]{2,50}$";
$regPrice = "^\d{1,8}(?:\.\d{1,4})?$";
$regSubject = "(^[\w](( \w+)|(\w*))*$)|(^\w$)";
