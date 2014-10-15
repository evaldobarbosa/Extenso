<?php
require "Extenso.php";

$valor = [
	150005.01,
	201209.10,
	11204.10,
	100112.15,
	5444333222.11,
	6555444333222.11
];

echo "\n\nEXTENSO\n================\n";

foreach ($valor as $key => $value) {
	echo "VALOR: ", number_format($value,2,',','.'), "\n";
	
	Extenso::valor($value);
	echo Extenso::numero( Extenso::MOEDA ), "\n-------------\n";
}