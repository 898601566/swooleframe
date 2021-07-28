<?php
include_once "responsibility_chain.php";
// client

$req_a = new ResponsibilityA();
$req_b = new ResponsibilityB();
$req_a = $req_a->setNext($req_b);
$req_a->check("A");
$req_a->check("B");
$req_a->check("C");
