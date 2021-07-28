<?php
namespace common\design_pattern\abstract_factory;
//client

$win_factory = new WinFactory();
$mac_factory = new MacFactory();
echo $win_factory->CreateComputer();
echo $win_factory->CreatePad();
echo $mac_factory->CreateComputer();
echo $mac_factory->CreatePad();
