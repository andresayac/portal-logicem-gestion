<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;


class Hana
{
    private static $conn;

    private static function conectar()
    {
        $username = "SYSTEM";
        $password = "Asdf1234$";
        $conn = odbc_connect("Driver={HDBODBC};SERVERNODE=10.238.22.165:30015;DATABASE=LOGICEM;CHAR_AS_UTF8=1", $username, $password);
        self::$conn = $conn;
    }

    public static function query($sql)
    {
        try {
            self::conectar();
            $query = odbc_exec(self::$conn, $sql);
            $result = [];
            while ($row = odbc_fetch_array($query)) {
                $result[] =  $row;
            }
            odbc_close(self::$conn);
            return $result;
        } catch (\Throwable $th) {
            Log::error(__FILE__ . ':' . __LINE__ . ': ' . $th->getMessage());
            return false;
        }
    }
}
