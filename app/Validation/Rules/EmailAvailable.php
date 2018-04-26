<?php


namespace App\Validation\Rules;

use App\Models\Koi;
use Respect\Validation\Rules\AbstractRule;

class EmailAvailable extends AbstractRule
{
    public function validate($input)
    {
        return Koi::where('email', $input)->count() === 0;
    }
}