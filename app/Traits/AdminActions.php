<?php

namespace App\Traits;


trait AdminActions
{

    public function before($user) {
        if($user->isAdmin()) {
            return true;
        }
    }

}//end of trait
