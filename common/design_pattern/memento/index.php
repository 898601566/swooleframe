<?php

include_once "memento.php";
// client
/* 创建目标对象 */
$org = new Originator();
$org->setState('knight is health');
$org->showState();

/* 创建备忘 */
$memento = $org->createMemento();

/* 通过Caretaker保存此备忘 */
$caretaker = new Caretaker();
$caretaker->setMemento($memento);
echo 'knight meet a monster<br/>';
/* 改变目标对象的状态 */
$org->setState('knight is hurt');
$org->showState();
echo 'knight use grail<br/>';
$org->restoreMemento($memento);
$org->showState();
echo 'knight meet a boss<br/>';
/* 改变目标对象的状态 */
$org->setState('knight is death');
$org->showState();
echo 'knight chager money<br/>';
/* 还原操作 */
$org->restoreMemento($caretaker->getMemento());
$org->showState();
?>
