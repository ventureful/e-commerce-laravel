<?php
namespace App\Transformers;

use League\Fractal;
use App\Models\User;

/**
* UserTransformer class
* Author: trinhnv
* Date: 2021/01/12 10:34
*/
class UserTransformer extends Fractal\TransformerAbstract
{
    public function transform(User $item)
	{
		return $item->toArray();
	}
}
