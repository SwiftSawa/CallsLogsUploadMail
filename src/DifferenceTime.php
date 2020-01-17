<?php 

function DifferenceTime($date1, $date2)  
{ 
    $diff = strtotime($date2) - strtotime($date1); 
    // 1 day = 24 hours 
    // 24 * 60 * 60 = 86400 seconds 
    return round($diff / 86400); 
} 
?> 