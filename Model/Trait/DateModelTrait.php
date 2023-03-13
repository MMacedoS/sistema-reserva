<?php

trait DateModelTrait {

    public function addDayInDate($date = null, $intervalo)
    {
        $date = new DateTime($date);
        date_add(
            $date,  
            date_interval_create_from_date_string("$intervalo days")
        );
        return $date->format('Y-m-d H:i:s');
        
    }

}