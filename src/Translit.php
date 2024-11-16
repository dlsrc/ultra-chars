<?php declare(strict_types=1);
/**
 * (c) 2005-2024 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra\Chars;

abstract class Translit {
	public static function ru2en(string $str, Transform $caps = Transform::Small): string {
		$str = str_replace(
			[
				'эко', 'ЭКО', 'Эко',
				'экс', 'ЭКС', 'Экс', 'кс', 'КС', 'Кс',
				'ай', 'ей', 'ий', 'ой', 'уй', 'ый', 'эй', 'юй', 'яй',
				'АЙ', 'ЕЙ', 'ИЙ', 'ОЙ', 'УЙ', 'ЫЙ', 'ЭЙ', 'ЮЙ', 'ЯЙ',
				'ъе', 'ъё', 'ъю', 'ъя', 'ЪЕ', 'ЪЁ', 'ЪЮ', 'ЪЯ',
				'ье', 'ьё', 'ью', 'ья', 'ьи', 'ьо',
				'ЬЕ', 'ЬЁ', 'ЬЮ', 'ЬЯ', 'ЬИ', 'ЬО',
				'а', 'А', 'б', 'Б', 'в', 'В', 'г', 'Г', 'д', 'Д', 'е', 'Е',
				'ё', 'Ё', 'ж', 'Ж', 'з', 'З', 'и', 'И', 'й', 'Й', 'к', 'К',
				'л', 'Л', 'м', 'М', 'н', 'Н', 'о', 'О', 'п', 'П', 'р', 'Р',
				'с', 'С', 'т', 'Т', 'у', 'У', 'ф', 'Ф', 'х', 'Х', 'ц', 'Ц',
				'ч', 'Ч', 'ш', 'Ш', 'щ', 'Щ', 'ъ', 'Ъ', 'ы', 'Ы', 'ь', 'Ь',
				'э', 'Э', 'ю', 'Ю', 'я', 'Я',
			],
			[
				'eco', 'ECO', 'Eco',
				'ex', 'Ex', 'Ex', 'x', 'X', 'X',
				'ay', 'ey', 'y', 'oy', 'uy', 'yy', 'ey', 'yoy', 'yay',
				'AY', 'EY', 'Y', 'OY', 'UY', 'YY', 'EY', 'YOY', 'YAY',
				'je', 'jo', 'ju', 'ja', 'JE', 'JO', 'JU', 'JA',
				'je', 'jo', 'ju', 'ja', 'ji', 'jo',
				'JE', 'JO', 'JU', 'JA', 'JI', 'JO',
				'a', 'A', 'b', 'B', 'v', 'V', 'g', 'G', 'd', 'D', 'e', 'E',
				'yo', 'Yo', 'zh', 'Zh', 'z', 'Z', 'i', 'I', 'j', 'J', 'k', 'K',
				'l', 'L', 'm', 'M', 'n', 'N', 'o', 'O', 'p', 'P', 'r', 'R',
				's', 'S', 't', 'T', 'u', 'U', 'f', 'F', 'h', 'H', 'ts', 'Ts',
				'ch', 'Ch', 'sh', 'Sh', 'sch', 'Sch', '', '', 'y', 'Y', '', '',
				'e', 'E', 'yu', 'Yu', 'ya', 'Ya',
			],
			$str
		);

		return $caps($str);
	}

	public static function eu2en(string $str, Transform $caps = Transform::Small): string {
		$str = str_replace(
			[
				'Á','á','Ć','ć','É','é','Í','í','Ĺ','ĺ','Ń','ń','Ó','ó','Ŕ','ŕ','Ś','ś','Ú','ú','Ẃ','ẃ','Ý','ý','Ź','ź',
				'Â','â','Ĉ','ĉ','Ê','ê','Ĝ','ĝ','Ĥ','ĥ','Î','î','Ĵ','ĵ','Ô','ô','Ŝ','ŝ','Û','û',
				'À','à','È','è','Ì','ì','Ò','ò','Ù','ù',
				'Ä','ä','Ë','ë','Ï','ï','Ö','ö','Ü','ü','Ÿ','ÿ',
				'Ã','ã','ñ','Õ','õ',
				'ß','Ç','ç','ş',
				'Č','č','DŽ','Dž','dž','Đ','đ','Ž','ž','Š','š',
				'Æ','æ','Ø','ø','Å','å',
			],
			[
				'A','a','C','c','E','e','I','i','L','l','N','n','O','o','R','r','S','s','U','u','W','w','Y','y','Z','z',
				'A','a','C','c','E','e','G','g','H','h','I','i','J','j','O','o','S','s','U','u',
				'A','a','E','e','I','i','O','o','U','u',
				'A','a','E','e','I','i','O','o','U','u','Y','y',
				'A','a','n','O','o',
				'ss','C','c', 's',
				'Ch','ch','DG','Dg','dg','Dj','dj','Zh','zh','Sh','sh',
				'Ae','ae','O','o','A','a',
			],
			$str
		);

		return $caps($str);
	}
}
