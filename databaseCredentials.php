<?php

class databaseCredentials
{
    //stores the host
    public $db_host;

    //stores the db name
    public $db_name;

    //stores the db user
    public $db_user;

    //stores the db user
    public $db_password;

    function __construct($db_host, $db_name, $db_user, $db_password)
    {
        $this->db_host     = $db_host;
        $this->db_name     = $db_name;
        $this->db_user     = $db_user;
        $this->db_password = $db_password;
    }

}