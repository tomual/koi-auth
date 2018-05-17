<?php


namespace App\Validation\Rules;

use App\Models\Gardener;
use Respect\Validation\Rules\AbstractRule;

class GardenerUsernameAvailable extends AbstractRule
{
    public function validate($input)
    {
        return Gardener::where('username', $input)->count() === 0;
    }
}