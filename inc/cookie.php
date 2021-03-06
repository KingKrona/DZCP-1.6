<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

final class cookie {
    private static $cname = "";
    private static $val = [];
    private static $expires;
    private static $dir = '/';
    private static $site = '';
    public static $secure = false;

    /**
     * Setzt die Werte für ein Cookie und erstellt es.
     * @param $cname
     * @param bool $cexpires
     * @param string $cdir
     * @param string $csite
     */
    public final static function init($cname, $cexpires = false, $cdir = "/", $csite = ""){
        self::$cname = $cname;
        self::$expires = ($cexpires ? $cexpires : (time() + cookie_expires));
        self::$dir = $cdir;
        self::$site = $csite;
        self::$val = array();
        self::$secure = false;
        if(hasSecure()) {
            self::$secure = true;
        }

        self::extract();
    }

    /**
     * Extraktiert ein gespeichertes Cookie
     * @param string $cname
     */
    public final static function extract($cname = ""){
        $cname = (empty($cname) ? self::$cname : $cname);
        if (!empty($_COOKIE[$cname])) {
            $arr = json_decode($_COOKIE[$cname], true);
            if ($arr !== false && is_array($arr)) {
                foreach ($arr as $var => $val) {
                    $_COOKIE[$var] = $val;
                }
            }

            self::$val = $arr;
        }
    }

    /**
     * Liest und gibt einen Wert aus dem Cookie zurück
     *
     * @param $var
     * @return string
     */
    public final static function get($var){
        if (!isset(self::$val) || empty(self::$val)) return false;
        if (!array_key_exists($var, self::$val)) return false;
        return self::$val[$var];
    }

    /**
     * Setzt ein neuen Key und Wert im Cookie
     * @param $var
     * @param $value
     */
    public final static function put($var, $value){
        self::$val[$var] = $value;
        $_COOKIE[$var] = self::$val[$var];
        if (empty($value)) unset(self::$val[$var]);
    }

    /**
     * Leert das Cookie
     */
    public final static function clear(){
        self::$val = array();
        self::save();
    }

    /**
     * Speichert das Cookie
     */
    public final static function save(){
        $cookie_val = (empty(self::$val) ? '' : json_encode(self::$val, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP));
        if (strlen($cookie_val) > 4 * 1024)
            trigger_error("The cookie " . self::$cname . " exceeds the specification for the maximum cookie size.  Some data may be lost", E_USER_WARNING);

        setcookie(self::$cname, $cookie_val, self::$expires, self::$dir, self::$site, self::$secure, true);
    }
}