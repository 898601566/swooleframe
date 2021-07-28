<?php

include_once "visitor.php";
// client
$elementA = new ConsumerBill(100);
$elementC = new IncomeBill(300);
$elementB = new ConsumerBill(300);
$elementD = new IncomeBill(800);
$visitor1 = new CPA();
$visitor2 = new Boss();

$os = new ObjectStructure();
$os->attach($elementA);
$os->attach($elementB);
$os->attach($elementC);
$os->attach($elementD);
$os->accept($visitor1);
$os->accept($visitor2);
$visitor2->getTotalConsumer();
$visitor2->getTotalIncome();
