<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * The house copy rules, enforced on save: no em dashes and no emoji.
 * Works on plain strings and on rich-editor state (nested arrays of text nodes).
 */
class CopyRules implements ValidationRule
{
    public const MESSAGE = 'No em dashes or emoji, please. Use a full stop or a comma.';

    /** Em dash plus the emoji and pictograph ranges (and the joiners that build emoji). */
    public const PATTERN = '/[\x{2014}\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}\x{2B00}-\x{2BFF}\x{1F1E6}-\x{1F1FF}\x{FE0F}\x{200D}\x{20E3}]/u';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (static::breaks($value)) {
            $fail(self::MESSAGE);
        }
    }

    public static function breaks(mixed $value): bool
    {
        if (is_array($value) || is_object($value)) {
            foreach ((array) $value as $item) {
                if (static::breaks($item)) {
                    return true;
                }
            }

            return false;
        }

        return is_string($value) && preg_match(self::PATTERN, $value) === 1;
    }
}
