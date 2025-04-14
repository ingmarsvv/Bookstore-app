<?php

namespace App\Helpers;

class BasicHelper 
{   
    //method returns an array for "Select tag" book filter options by month
    public static function getFilterYearMonth(int $yearsToDate): array
    {
        $months = ['Janvāris', 'Februāris', 'Marts', 'Aprīlis', 'Maijs', 'Jūnijs',
         'Jūlijs', 'Augusts', 'Septembris', 'Oktobris', 'Novembris', 'Decembris'];
        $year = date('Y');
        $month = date('n');
        $optionsSortByMonth = [];

        for($i = $year; $i >= $year - $yearsToDate; $i--){
            for($j = 11; $j >= 0; $j--){
                if ($i == $year && $j >= $month){
                    continue;
                } else {
                    array_push($optionsSortByMonth,[
                        'label' => $months[$j] . "/" . $i,
                        'value' => [$j + 1, $i],
                    ]); 
                }
            };
        };

        return $optionsSortByMonth;
    }
}








?>