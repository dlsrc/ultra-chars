<?php declare(strict_types=1);
/**
 * (c) 2005-2024 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra\Chars;

enum Transform: int {
	case Upper       = MB_CASE_UPPER;
	case Lower       = MB_CASE_LOWER;
	case Title       = MB_CASE_TITLE;
	case Fold        = MB_CASE_FOLD;
	case UpperSimple = MB_CASE_UPPER_SIMPLE;
	case LowerSimple = MB_CASE_LOWER_SIMPLE;
	case TitleSimple = MB_CASE_TITLE_SIMPLE;
	case FoldSimple  = MB_CASE_FOLD_SIMPLE;
	case Nothing     = -1;

	public const self Small = self::Lower;

	public function __invoke(string $string): string {
        if (-1 == $this->value) {
			return $string;
		}

		return mb_convert_case($string, $this->value);
    }
}
