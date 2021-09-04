<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pair extends Model
{
    use HasFactory;

    protected $table = 'count_the_pair';

    protected $casts = [
        'pairs' => 'array',
    ];

    public function countThePair($array, $array_size, $left_range, $right_range)
    {
        $count      = 0;
        $product    = 1;

        $count_pair = null;
        $pair       = null;

        for($i = 0 ; $i<$array_size ; $i++)
        {
            for($j = $i+1; $j<$array_size; $j++)
            {
                $product = $array[$i] * $array[$j];
                if($product >= $left_range && $product <= $right_range)
                {
                    $count++;
                    $pair.= "[ '".$array[$i]. "','" .$array[$j]."' ]";
                }
            }
        }
        $count_pair = [
            'final_pair'    => $pair,
            'final_count'   => $count,
        ];

        return $count_pair;
    }
}
