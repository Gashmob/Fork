<?php

namespace Fork\Yaml_Editor;

abstract class YamlParser
{
    const TAB = '  ';

    /**
     * @param YamlFile $file
     * @return array
     */
    public static function toArray(YamlFile $file)
    {
        $array = [];
        $f = $file->getFile();

        $path = '';
        $nbTabP = 0;
        $value = '';
        $list = [];
        while ($line = fgets($f)) {
            $nbTab = self::countTabulation($line);
            $name = self::getName($line);

            if ($nbTab == $nbTabP) {
                if (self::isListElement($line)) {
                    $list[] = self::getValue($line);
                } elseif (count($list) > 0) {
                    $array = self::set($path, $list, $array);
                    $list = [];
                } else {
                    if ($path != '') {
                        $array = self::set($path, $value, $array);
                        $path = self::removeLastPath($path);
                    }
                    $path .= strlen($path) > 0 ? ".$name" : $name;
                }
            } elseif ($nbTab > $nbTabP) {
                if (self::isListElement($line)) {
                    $list[] = self::getValue($line);
                }
                $path .= strlen($path) > 0 ? ".$name" : $name;
            } else { // $nbTab < $nbTabP
                $array = self::set($path, $value, $array);
                $n = $nbTabP - $nbTab;
                $path = self::removeLastPath($path, $n + 1);
                $path .= strlen($path) > 0 ? ".$name" : $name;
            }

            $value = self::getValue($line);
            $nbTabP = $nbTab;
        }

        if (count($list) > 0) {
            $array = self::set($path, $list, $array);
        }
        if ($value != '') {
            $array = self::set($path, $value, $array);
        }

        return $array;
    }

    /**
     * @param YamlArray $yamlArray
     * @return string
     */
    public static function toYaml(YamlArray $yamlArray)
    {
        $array = $yamlArray->getArray();

        return self::arrayToYaml($array, 0);
    }

    // _.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-.

    /**
     * @param string $path
     * @param $value
     * @param array $array
     * @return array
     */
    private static function set($path, $value, array $array)
    {
        return self::getArray(self::cut($path, '.'), $value, $array);
    }

    /**
     * @param array $path
     * @param $value
     * @return array
     */
    public static function setArray(array $path, $value)
    {
        if (count($path) == 1) {
            $result[$path[0]] = $value;
        } elseif (count($path) > 1) {
            $result[$path[0]] = self::setArray(array_slice($path, 1), $value);
        } else { // count($path) == 0
            $result = [$value];
        }

        return $result;
    }

    /**
     * @param string $line
     * @return mixed|string
     */
    private static function getName($line)
    {
        return self::isValue($line) ? '' : self::cut(trim($line), ':')[0];
    }

    /**
     * @param string $line
     * @return string
     */
    private static function getValue($line)
    {
        $l = trim($line);

        if (self::isListElement($l)) {
            $t = self::cut($l, ' ');
            $l = count($t) > 1 ? $t[1] : '';
        } else {
            $t = self::cut($l, ':');
            $l = count($t) > 1 ? $t[1] : '';
        }

        $l = trim($l);

        if (self::isString($l) && strlen($l) > 1) {
            $l = substr($l, 1, strlen($l) - 2);
        }


        return trim($l);
    }

    /**
     * @param array $path
     * @param $value
     * @param array $array
     * @return array
     */
    public static function getArray(array $path, $value, array $array)
    {
        if (count($path) == 1) {
            $array[$path[0]] = $value;
        } elseif (count($path) > 0) {
            if (isset($array[$path[0]])) {
                $array[$path[0]] = self::getArray(array_slice($path, 1), $value, $array[$path[0]]);
            } else {
                $array = array_merge($array, self::setArray($path, $value));
            }
        }

        return $array;
    }

    /**
     * @param string $line
     * @return bool
     */
    private static function isValue($line)
    {
        return !strstr($line, ':');
    }

    /**
     * @param string $line
     * @return bool
     */
    private static function isString($line)
    {
        $l = trim($line);
        $result = false;

        if (strlen($l) >= 2 && self::isValue($l)) {
            $result = $l[0] == '"' && $l[strlen($l) - 1] == '"';
        }

        return $result;
    }

    /**
     * @param string $line
     * @return bool
     */
    private static function isListElement($line)
    {
        $l = trim($line);
        return self::isValue($line) && (strlen($l) > 0 ? $l[0] == '-' : false);
    }

    /**
     * @param string $line
     * @return int
     */
    private static function countTabulation($line)
    {
        $result = 0;

        if (strlen($line) > 0) {
            $i = 0;
            $c = $line[$i];

            while ($c == ' ' && $i < strlen($line)) {
                $result++;

                $i++;
                $c = $line[$i];
            }
        }

        return $result / 2;
    }

    /**
     * @param string $sentence
     * @param string $delimiter
     * @return array
     */
    private static function cut($sentence, $delimiter)
    {
        $result = [];
        $word = '';

        for ($i = 0; $i < strlen($sentence); $i++) {
            $c = $sentence[$i];
            if ($c == $delimiter && strlen($word) > 0) {
                $result[] = $word;
                $word = '';
            } else {
                $word .= $c;
            }
        }

        if (strlen($word) > 0) {
            $result[] = $word;
        }

        return $result;
    }

    /**
     * @param string $path
     * @param int $n
     * @return string
     */
    private static function removeLastPath($path, $n = 1)
    {
        $t = self::cut($path, '.');

        $result = '';

        for ($i = 0; $i < count($t) - $n; $i++) {
            $result .= strlen($result) > 0 ? ".$t[$i]" : $t[$i];
        }

        return $result;
    }

    // _.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-.

    /**
     * @param string $value
     * @return bool
     */
    private static function isNumber($value)
    {
        $numbers = '1234567890';

        $result = true;
        $i = 0;
        while ($i < strlen($value) && $result) {
            $c = $value[$i];
            if (!strstr($numbers, $c)) {
                $result = false;
            }

            $i++;
        }

        return $result;
    }

    /**
     * @param int $nbTab
     * @return string
     */
    private static function getTab($nbTab)
    {
        $result = '';

        while ($nbTab > 0) {
            $result .= self::TAB;
            $nbTab--;
        }

        return $result;
    }

    private static function arrayToYaml(array $array, $nbTab)
    {
        $result = '';

        foreach ($array as $name => $value) {
            $result .= self::getTab($nbTab);
            if (is_array($value)) {
                $result .= "$name:\n" . self::arrayToYaml($value, $nbTab + 1);
            } else {
                $result .= "$name: ";
                if (self::isNumber($value)) {
                    $result .= "$value\n";
                } else {
                    $result .= "\"$value\"\n";
                }
            }
        }

        return $result;
    }
}