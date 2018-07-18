<?php


namespace App\Validation\Rules;

use App\Models\Koi;
use Respect\Validation\Rules\AbstractRule;

class KoiUsernameAvailable extends AbstractRule
{
    public function validate($input)
    {
        return Koi::where('username', $input)->where('pond_id', $_SESSION['pond_id'])->count() === 0;
    }
}