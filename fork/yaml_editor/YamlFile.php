<?php


class YamlFile
{
    /**
     * @var resource
     */
    private $file;

    /**
     * YamlFile constructor.
     * @param string $filename
     * @throws NotYamlFileException
     */
    public function __construct($filename)
    {
        if ($this->getExtension($filename) == 'yml') {
            if (file_exists($filename)) $this->file = fopen($filename, 'r+t');
            else $this->file = fopen($filename, 'x+t');
        } else throw new NotYamlFileException();
    }

    /**
     * @return YamlArray
     */
    public function getYamlArray()
    {
        return new YamlArray($this);
    }

    /**
     * @param YamlArray $array
     */
    public function setYamlArray(YamlArray $array)
    {
        $s = YamlParser::toYaml($array);
        ftruncate($this->file, 0);
        fwrite($this->file, $s);
    }

    /**
     * @return resource
     */
    public function getFile()
    {
        return $this->file;
    }

    // _.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-.

    /**
     * @param string $filename
     * @return string
     */
    private function getExtension($filename)
    {
        $extension = "";
        $i = strlen($filename) - 1;
        $c = $filename[$i];
        while ($c != '.' || $i == 0) {
            $extension = $c . $extension;

            $i--;
            $c = $filename[$i];
        }

        return $extension;
    }
}