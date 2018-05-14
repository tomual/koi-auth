<?php


namespace App\Validation\Rules;

use App\Models\Gardener;
use Respect\Validation\Rules\AbstractRule;

class EmailAvailable extends AbstractRule
{
    public function validate($input)
    {
        return Gardener::where('email', $input)->count() === 0;
    }
}