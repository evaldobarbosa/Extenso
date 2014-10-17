<?php
/**
 * Biblioteca para escrita de números por extenso
 * @version 1.0
 * @author Evaldo Barbosa <evaldobarbosa@gmail.com>
 */
class Extenso {
	const MOEDA = 1;
	const NUMERO = 2;

	private static $numero;

	static $mil_sing = ['mil','milhão','bilhão','trilhão','quadrilhão','quintilhão'];
	static $mil_plur = ['mil','milhões','bilhões','trilhões','quadrilhões','quintilhões'];

	static $centenas = [
		1 => 'cento', 2 => 'duzentos', 3 => 'trezentos', 4 => 'quatrocentos',
		5 => 'quinhentos', 6 => 'seicentos', 7 => 'setecentos',
		8 => 'oitocentos', 9 => 'novecentos'
	];

	static $dezenas = [
		1 => 'dez', 2 => 'vinte', 3 => 'trinta', 4 => 'quarenta',
		5 => 'cinquenta', 6 => 'sessenta', 7 => 'setenta',
		8 => 'oitenta', 9 => 'noventa'
	];

	static $ateVinte = [
		11 => 'onze', 12 => 'doze', 13 => 'treze', 14 => 'quatorze',
		15 => 'quinze', 16 => 'dezesseis', 17 => 'dezessete',
		18 => 'dezoito', 19 => 'dezenove'
	];

	static $ateDez = [
		1 => 'um', 2 => 'dois', 3 => 'três', 4 => 'quatro', 5 => 'cinco',
		6 => 'seis', 7 => 'sete', 8 => 'oito', 9 => 'nove'
	];

	static function valor($valor) {
		self::$numero = number_format($valor,2,',','.');
	}

	static private function quebra() {
		return preg_split("/[,\.]+/", self::$numero);
	}

	static function numero($tipo) {
		$x = self::quebra();

		$cent = $x[ count($x)-1 ];
		unset( $x[ count($x)-1 ] );

		$x = array_reverse($x);

		foreach ($x as $key => $value) {
			$x[ $key ] = self::converte( $value );

			if ( $key > 0 ) {
				$x[ $key ] .= ( (int)$value == 1 )
					? " " . self::$mil_sing[ $key-1 ]
					: " " . self::$mil_plur[ $key-1 ];
			}
		}

		$x = array_filter($x, function($value) {
			return ( !empty( trim($value) ) );
		});

		$x = array_reverse($x);
		$cent = self::converte($cent);

		$dec = ( $cent !== 'um' )
			? 's'
			: null;

		$str = implode(" e ", $x );

		switch ($tipo) {
			case self::MOEDA:
				return ( strlen($cent) > 0 )
					? "{$str} reais e {$cent} centavo{$dec}"
					: $str;
				break;
			
			default:
				return ( strlen($cent) > 0 )
					? "{$str} e {$cent} centésimo{$dec}"
					: $str;
				break;
		}
	}

	private static function converte($v) {
		$v = str_pad($v, 3, '0', STR_PAD_LEFT);

		$c = self::centena($v);

		$d = self::dezena($v);
		
		$u = self::unidade($v);

		$p  = ( strlen($c) > 0 ) ? '1' : '0';
		$p .= ( strlen($d) > 0 ) ? '1' : '0';
		$p .= ( strlen($u) > 0 ) ? '1' : '0';

		switch ($p) {
			case '100':
				return $c;
				break;
			
			case '110':
				return "{$c} e {$d}";
				break;

			case '111':
				return "{$c} e {$d} e {$u}";
				break;

			case '011':
				return "{$d} e {$u}";
				break;

			case '001':
				return "{$u}";
				break;

			case '010':
				return "{$d}";
				break;

			case '101':
				return "{$c} e {$u}";
				break;
		}
	}

	private static function centena($v) {
		$s  = substr($v, 0, 1);
		$s1 = substr($v, 1, 2);

		if ( $s == '0' ) {
			return null;
		}

		if ( $s1 == '00' ) {
			if ( $s == '1' ) {
				return 'cem';
			}
		} else {
			return self::$centenas[ (int)$s ];
		}
	}

	private static function dezena(&$v) {
		$d = '';

		$s = substr( $v, 1, 2 );

		if ( isset( self::$dezenas[ $s[0] ] ) ) {
			if ( (int)$s % 10 == 0 ) {
				$v = '000';
			}
			$d = self::$dezenas[ $s[0] ];
		}

		if ( $s > 10 && $s < 20 ) {
			$v = '000';
			$d = self::$ateVinte[ $s ];
		}

		return $d;
	}

	private static function unidade($v) {
		$u = '';

		$s = substr( $v, 2, 1 );

		if ( $s !== '0' ) {
			$u = self::$ateDez[ (int)$s ];
		}

		return $u;
	}
}