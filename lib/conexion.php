<?php

class Conexion {

    private static $host = NULL;
    private static $dbname = NULL;
    private static $usuario = NULL;
    private static $clave = NULL;
    private static $pdo = NULL;
    private static $result = NULL;

    public static function conectar($host = "localhost", $dbname = MIBASE, $usuario = MIUSER, $clave = MIPASS) {
        self::$host = $host;
        self::$dbname = $dbname;
        self::$dbname = $dbname;
        self::$usuario = $usuario;
        self::$clave = $clave;

        try {
            self::$pdo = new PDO('mysql:host=' . self::$host . ';dbname=' . self::$dbname, self::$usuario, self::$clave, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
            return self::$pdo;
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public static function ejecutar($sql) {
        try {
            self::$result = self::$pdo->query($sql);
            if (!self::$result) {
                return null;
            }
        } catch (PDOException $e) {
            //echo 'SQL: ' . $sql . '</br></br>';
            //echo 'MESSAGE: ' . $e->getMessage() . '</br></br>';
            return null;
        }

        return self::$result;
    }
    
     public static function ejecutarSP($nombreProcedimiento, $params) {
        try {
            $parametros = implode(',', array_fill(0, count($params), '?'));
            $sql = "CALL $nombreProcedimiento($parametros)";
            //Conexion::verSP($nombreProcedimiento, $params);
            self::$result = self::$pdo->prepare($sql);

            $i = 1;
            foreach ($params as $param) {
                self::$result->bindValue($i++, $param);
            }

            return self::$result->execute();
        } catch (PDOException $e) {
            // Manejar excepciones
            echo 'ERROR: ' . $e->getMessage() . '</br></br>';
            //echo '<script>alert(' . $e->getMessage() . ');</script>';
            return null;
        }
    }

    public static function obtener($sql, $index = 'assoc', $matriz = NULL) {
        try {
            self::$result = self::$pdo->query($sql);
            if (self::$result === false) {
                return array();
            }
            foreach (self::$result as $fila) {
                $matriz[] = $index !== 'assoc' && $index !== 'index' ? $fila : get_array($fila, $index);
            }
            return $matriz;
        } catch (PDOException $e) {
            echo 'SQL: ' . $sql . '</br></br>';
            echo 'MESSAGE: ' . $e->getMessage() . '</br></br>';
        }
        return null;
    }

    public static function filas() {
        return self::$result->rowCount();
    }
}
