<?php

function log_access() {
        $access = fopen('log/access', 'a');
        $remote_addr = $_SERVER['REMOTE_ADDR'];
        $uri = $_SERVER['REQUEST_URI'];
        $cdate = date("r");

        $access_string = "$remote_addr requested $uri on $cdate\n";
        fwrite($access, $access_string);

        fclose($access);
}

?>