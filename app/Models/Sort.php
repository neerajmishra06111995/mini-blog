<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sort extends Model
{
    use HasFactory;

    protected $table = 'array_sorts';

    public function sortAnArray($array)
    {
        for($i = 0; $i < count($array); $i++)
        {
            for($j = 0; $j < count($array)-1; $j ++)
            {
                if($array[$j] > $array[$j+1])
                {
                    $temp = $array[$j+1];
                    $array[$j+1]=$array[$j];
                    $array[$j]=$temp;
                }
            }
        }

        return $array;
    }
}
