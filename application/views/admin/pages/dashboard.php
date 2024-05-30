<?php
    
        $user = $this->ion_auth->user()->row();
        echo $user->first_name;
        echo 'hi I am admin';
    
?>