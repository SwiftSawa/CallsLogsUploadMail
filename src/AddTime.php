<?php
    // functipon to add time
        function AddTime ($oldTime, $TimeToAdd) {
        $old=explode(":",$oldTime);
        $new=explode(":",$TimeToAdd);
    
        $hours=$old[0]+$new[0];
        $minutes=$old[1]+$new[1];
        $secound=$old[2]+$new[2];
    
        if($minutes > 59){
        $minutes=$minutes-60;
        $hours++;
        }else if($minutes<10){
            $minutes="0".$minutes;
        }else if($minutes == 0){
        $minutes = "00";
        }

        if($secound > 59){
            $secound=$secound-60;
            $secound++;
        }else if($secound<10){
            $secound="0".$secound;
        }else if($secound == 0){
            $secound = "00";
        }

        if($hours == 0){
            $hours = "00";
        }else if($hours<10){
            $hours="0".$hours;
        }
        $sum=$hours.":".$minutes.":".$secound;
        return $sum;
    }
?>