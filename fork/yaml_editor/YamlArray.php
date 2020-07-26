<?php


class YamlArray
{
    /**
     * @var array
     */
    private $array;

    /**
     * YamlArray constructor.
     * @param YamlFile $file
     */
    public function __construct(YamlFile $file)
    {
        $this->array = YamlParser::toArray($file);
    }

    /**
     * @param string $path
     * @param $value
     */
    public function set(string $path, $value)
    {
        $this->array = YamlParser::getArray(explode('.', $path), $value, $this->array);
    }

    /**
     * @param string $path
     * @return mixed|string|array
     * @throws PathNotFoundException
     */
    public function get(string $path)
    {
        $tab = explode('.', $path);

        $value = '';
        $f = true;
        foreach ($tab as $word) {
            if ($f) {
                if (isset($this->array[$word])) {
                    $value = $this->array[$word];
                    $f = false;
                } else {
                    throw new PathNotFoundException();
                }
            } else {
                if (isset($value[$word])) {
                    $value = $value[$word];
                } else {
                    throw new PathNotFoundException();
                }
            }
        }

        return $value;
    }

    /**
     * @return array
     */
    public function getArray(): array
    {
        return $this->array;
    }
}