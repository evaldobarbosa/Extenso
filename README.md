Extenso
=======

Biblioteca que escreve números por extenso

#Como usar
	<?php
	require 'Extenso.php';

	<?php
	$numero = 6555444333222.11;

	//Informando à biblioteca o valor a ser escrito
	Extenso::valor($value);

	//Especificando o tipo de retorno (moeda ou número)
	$novo = Extenso::numero( Extenso::MOEDA );

	echo $novo;
