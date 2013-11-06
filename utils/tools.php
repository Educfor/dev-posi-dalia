<?php

/**
 * Description of tools
 *
 * @author Nicolas Beurion
 */

class Tools {
    
    
    
    
    static function toggleDate($date, $type = "fr")
    {
        if ($type == "us")
        {
            $tabDate = explode("/", $date);
            $day = $tabDate[0];
            $month = $tabDate[1];
            $year = $tabDate[2];

            $toggleDate = $year."-".$month."-".$day;
        }
        else if ($type == "fr")
        {
            
        }
        
        echo "\$toggleDate = ".$toggleDate."<br />";
        return  $toggleDate;
    }
    
}


?>
