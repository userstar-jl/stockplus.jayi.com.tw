<?php
class GV{
    private static $_instance = null;
    public static $id = 0;
    public static function getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new GV();
        }
        return self::$_instance;
    }
}
?>