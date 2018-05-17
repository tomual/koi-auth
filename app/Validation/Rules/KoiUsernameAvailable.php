<?php


namespace App\Validation\Rules;

use App\Models\Koi;
use Respect\Validation\Rules\AbstractRule;

class KoiUsernameAvailable extends AbstractRule
{
    public function validate($input)
    {
        return Koi::where('username', $input)->count() === 0;
    }
}