<?php


class Tag
{
    protected $name, $attributes = [], $body;
    protected $selfClosing = [
        'area', 'base', 'br', 'embed', 'hr', 'iframe', 'img', 'input',
        'link', 'meta', 'param', 'source', 'track'
    ];

    public function __construct($name, array $attributes = [])
    {
        $this->setName($name);
        $this->setAttribute($attributes);
    }

    protected function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setAttribute($key, $value = null)
    {

        if (is_array($key)) {
            foreach ($key as $k => $v)
                $this->setAttribute($k, $v);
        } else {
            $this->attributes[$key] = $value;
        }

    }

    protected function getAttributes()
    {
        return $this->attributes;
    }

    protected function setBody($body)
    {
        $this->body = $body;
    }


    protected function getBody()
    {
        if (!$this->isSelfClosing())
            return $this->body;

        return null;
    }

    public function appendBody($body)
    {
        $this->setBody($this->getBody() . $body);
    }

    public function prependBody($body)
    {
        $this->setBody($body . $this->getBody());
    }

    public function isSelfClosing(): bool
    {
        return in_array($this->getName(), $this->selfClosing);
    }

    public function start()
    {
        $tag = "<{$this->getName()}";
        foreach ($this->getAttributes() as $key => $attribute) {
            $tag .= " $key";
            if ($attribute != null)
                $tag .= "=\"$attribute\"";
        }
        return $tag . ($this->isSelfClosing() ? " />" : ">");
    }

    public function end()
    {
        if (!$this->isSelfClosing())
            return "</{$this->getName()}>";
        return null;
    }

    public function getString()
    {
        return $this->start() . $this->getBody() . $this->end();
    }

    public function __toString()
    {
        return $this->getString();
    }

    protected function getAttribute($key)
    {

//        if (isset($this->attributes[$key]))
//            return $this->attributes[$key]
//                else
//                    return null;
        return $this->attributes[$key] ?? null;
    }

    public function appendAttribute($key, $value)
    {
        return $this->setAttribute($key, $this->getAttribute($key) . $value);
    }

    public function prependAttribute($key, $value)
    {
        return $this->setAttribute($key, $value . $this->getAttribute($key));
    }

    public function addClass($class)
    {
        $classes = $this->classesAsArray();

        if (!$this->classExists($class))
            $classes[] = $class;

        $classes = implode(' ', $classes);
        $this->setAttribute('class', $classes);

        return $this;
    }

}
