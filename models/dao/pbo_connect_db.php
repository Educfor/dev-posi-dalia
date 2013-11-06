<?php

class PDOConnectDB extends PDO
{
	
    public function __construct($file = 'models/dao/db_config.ini')
    {
        if (!$setting = parse_ini_file($file, TRUE))
        {
            throw new Exception("Impossible d'ouvrir " . $file . ".<br/>");
        }

        $dns = $setting['database']['driver'] . ":host=" . $setting['database']['host'];
        if (!empty($setting['database']['port']))
        {
            $dns .= ";port=" . $setting['database']['port'];
        }
        $dns .= ";dbname=" . $setting['database']['schema'];

        parent::__construct($dns, $setting['database']['username'], $setting['database']['password']);

    }
	
}

?>

