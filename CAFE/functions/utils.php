<?php

function check_if_option_in_array($list,$value){
    # usually you will convert string( 'sd ,fv , ada, aa' ) to list using explode(',' $list)
    
    if (in_array($value,array_values($list))){
        echo 'checked';
    }else{
    return '';

    };
};




?>