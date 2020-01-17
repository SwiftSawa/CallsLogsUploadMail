<?php
    // functipon to divide time with const
    function DivTime ($time, $divNum) {

        $con=explode(":",$time);

        $hours=$con[0]/$divNum;
        $minutes=$con[1]/$divNum;
        $secounds=$con[2]/$divNum;
        
        if(is_float($hours)== 1){
            $hrExp=explode(".",$hours);
            if(count($hrExp)!=1){
                $newMin=$hrExp[1]*60/(10**strlen($hrExp[1]));
                $minutes= $minutes + $newMin;
            }
            $hours=intval($hours);
            
        }
        if(is_float($minutes)==1){
            $minExp=explode(".",$minutes);
            if(count($minExp)!=1){
                $newSec=$minExp[1]*60/(10**strlen($minExp[1]));
                $secounds= $secounds + $newSec;
            }
            $minutes=intval($minutes);
            
        }
        if(is_float($secounds)){
            $secounds=intval($secounds);
            
        }
        
        return $hours.":".$minutes.":".$secounds;
    }
?>