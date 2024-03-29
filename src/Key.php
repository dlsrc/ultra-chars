<?php declare(strict_types=1);
/**
 * (c) 2005-2024 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra\Chars;

use Random\Randomizer;
use Ultra\Enum\Cases;
use Ultra\Fail;
use Ultra\State;
use Ultra\Status;
use Ultra\Result;

enum Key: string {
	use Cases;

	case BIG = '!QA,Z1q.az@WS<X2w>sx#ED:C3e;dc$RF{V4r}fv%TG[B5t]gb^YH-N6y=hn&UJ_M7u+jm*IK8i?k(OL9ol)P0p|/\\';
	case CHR = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
	case ABC = 'abcdefghijklmnopqrstuvwxyz1234567890';
	case ABU = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	case DEC = '0123456789';
	case HEX = '0123456789abcdef';
	case HUP = '0123456789ABCDEF';
	case ALP = 'qwertyuiopasdfghjklzxcvbnm';
	case ALU = 'QWERTYUIOPASDFGHJKLZXCVBNM';
	case ALM = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
	case ALL = 'abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMN123456789PQRSTUVWXYZ-_';
	case WDR = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM-_';
	case UCW = 'ABCDEFGHIJKLMN123456789PQRSTUVWXYZ';
	case GEN = '23456789abcdeghkmnpqsuvxyz-_!@$%*=+?ABCDEGHMNPQSUVXYZ';
	case PWD = '!@#$%^&*()[]{}abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMN123456789PQRSTUVWXYZ,.<>:;-=_+|?';
	case ASC = 'abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMN123456789PQRSTUVWXYZ';

	public static function __callStatic(string $name, array $args): State {
		if (!$case = self::getCaseByName($name)) {
			return new Fail(
				type: Status::Domain,
				message: 'An attempt to call a non-existent enumeration case.',
				file: __FILE__,
				line: __LINE__,
			);
		}

		$length   =    (int) ($args[0] ?? 32);
		$count    =    (int) ($args[1] ?? 1);
		$divisor  = (string) ($args[2] ?? '-');
		$exclude  = (string) ($args[3] ?? '');
		$nodigits =   (bool) ($args[4] ?? false);

		return new Result($case->gen($length, $count, $divisor, $exclude, $nodigits));
	}

	public function gen(
		int    $length   = 32,
		int    $count    = 1,
		string $divisor  = '-',
		string $exclude  = '',
		bool   $nodigits = false
	): string {
		if ($length < 1) {
			$length = 1;
		}

		if ($length > 64) {
			$length = 64;
		}

		if ($count < 1) {
			$count = 1;
		}

		if ($count > 64) {
			$count = 64;
		}

		if (!in_array($divisor, ['-', '/', '.', ' ', "\t", '#', '@', '&', '%', '+', '*', '\\'])) {
			$divisor = '-';
		}

		if (self::DEC == $this) {
			$nodigits = false;
		}

		$substr = [];
		$rm = new Randomizer();

		for ($i = 0; $i < $count; $i++) {
			while (true) {
				$substr[$i] = $rm->getBytesFromString($this->value, $length);

				if ('' != $exclude && str_contains($substr[$i], $exclude)) {
					continue;
				}

				if ($nodigits && ctype_digit($substr[$i])) {
					continue;
				}

				break;
			}
		}

		return implode($divisor, $substr);
	}

	public static function code2dec(string $string, string $salt = ''): string {
		$code = str_split($string);
		$dec  = '';
		$salt = self::HEX->value . $salt;
		$loop = str_split($salt);

		foreach ($code as $chr) {
			$kit    = self::splitConvert($salt);
			$dec    = $dec.$kit[$chr];
			$loop[] = array_shift($loop);
			$salt   = implode('', $loop);
		}

		return $dec;
	}

	public static function dec2code(string $digit, string $salt = ''): string {
		if (strlen($digit) % 2) {
			$digit = '0'.$digit;
		}

		$code  = '';
		$salt  = self::HEX->value . $salt;
		$loop  = str_split($salt);
		$digit = str_split($digit, 2);
	
		foreach ($digit as $dec) {
			$kit = self::splitConvert($salt);
			$kit = array_flip($kit);

			if (isset($kit[$dec])) {
				$code.= $kit[$dec];
			}
			else {
				$code.= $dec;
			}

			$loop[] = array_shift($loop);
			$salt   = implode('', $loop);
		}

  		return $code;
	}

	public static function splitConvert(string $salt): array {
		$conv = '          '.self::BIG->value;
		$split = array_unique(str_split($salt));

		foreach (array_keys($split) as $id) {
			if (str_contains($conv, $split[$id])) {
				$erase[$id] = '';
			}
			else {
				unset($split[$id]);
			}
		}

		if (empty($split)) {
			$kit = array_flip(str_split($conv));
		}

		$kit = array_flip(str_split(str_replace($split, $erase, $conv).implode('', $split), 1));

		unset($kit[' ']);
		return $kit;
	}

	public static function hideID(string $string, int $deep = 5, string $salt = ''): string {
		$code = '';
		$key = self::BIG;
		$len = mb_strlen($string);

		if ($deep < 1) {
			$deep = 1;
		}
		elseif ($deep > 9) {
			$deep = 9;
		}

		for ($id = (new Randomizer())->getInt(0, $deep), $i = 0; $i < ($deep + 1); $i++) {
			if ($id == $i) {
				$code.= $string;
			}
			else {
				$code.= $key->gen($len, 1,'','', true);
			}
		}

		$code =	$key->gen(1, 1,'','', true).$code.$id.$key->gen(1, 1,'','', true).$len;

		return Key::code2dec($code, $salt);
	}

	public static function findID(string $token, string $salt = ''): State {
		$token = Key::dec2code($token, $salt);
		
		if (0 == preg_match('/^\D(.+)\D(\d+)$/s', $token, $match)) {
			return new Fail(
				type: Status::Argument,
				message: 'Token is corrupted',
				file: __FILE__,
				line: __LINE__,
			);
		}

		$token = str_split($match[1], (int) $match[2]);
		$key   = array_key_last($token);
		$id    = $token[$key];

		if (!isset($token[$id])) {
			return new Fail(
				type: Status::Range,
				message: 'Token is corrupted',
				file: __FILE__,
				line: __LINE__,
			);
		}

		return new Result($token[$id]);
	}
}
