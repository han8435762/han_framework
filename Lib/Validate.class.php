<?php
class Validate {

    /**
     * 检查用户名是否符合规定
     *
     * @param STRING $username
     * @return  bool
     */
    public static function is_username($username) {
        $strlen = strlen($username);
        if(self::is_badword($username) || !preg_match("/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+$/", $username)) {
            return false;
        } elseif ( 20 <= $strlen || $strlen < 2 ) {
            return false;
        }

        return true;
    }

    /**
     * 检测输入中是否含有错误字符
     *
     * @param char $string
     * @return bool
     */
    public static function is_badword($string) {
        $badwords = array("\\", '&', ' ', "'", '"', '/', '*', ',', '<', '>', "\r", "\t", "\n", "#");
        foreach($badwords as $value) {
            if(strpos($string, $value) !== FALSE) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * 检查密码长度是否符合规定
     *
     * @param STRING $password
     * @return bool
     */
    public function is_password($password)    {
        $strlen = strlen($password);
        if($strlen >= 4 && $strlen <= 20) return true;
        return false;
    }

    /**
     * 判断email格式是否正确
     * @param $email
     */
    function is_email($email) {
        return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
    }

    /**
     * is_mobile
     *
     * @param  integer  $mobile
     * @return boolean
     */
    function is_mobile($mobile) {
        return $mobile && preg_match("/\d{11}/", $mobile);
    }

    /**
     * is_date
     *
     * @param  string  $str
     * @param  string  $format
     * @return boolean
     */
    function is_date($str, $format="Y-m-d"){
        $strArr = explode("-", $str);
        if(empty($strArr)){
            return false;
        }

        $newArr = array();
        foreach($strArr as $val) {
            if(strlen($val) < 2) {
                $val = "0" . $val;
            }
            $newArr[] = $val;
        }
        $str = implode("-", $newArr);
        $unixTime = strtotime($str);
        $checkDate = date($format, $unixTime);
        if($checkDate == $str) {
            return true;
        } else {
            return false;
        }
    }

    function is_postcode($postcode) {
        if(!preg_match("/[0-9]{6}/", trim($postcode))) {
            return false;
        }

        return true;
    }
}