<?php

namespace Romkamix\XmlIterator;

use Iterator;
use DOMDocument;
use SimpleXMLElement;
use XMLReader;

class XmlIterator implements Iterator
{
    protected $src;
    protected $tag;
    protected $xmlreader;
    protected $doc;
    protected $current;
    protected $key;

    public function __construct(string $src, string $tag)
    {
        $this->src = $src;
        $this->tag = $tag;
        $this->doc = new DOMDocument();
        $this->xmlreader = new XMLReader;
    }

    public function rewind(): void
    {
        $this->key = 0;
        $this->xmlreader->open($this->src);

        while ($this->xmlreader->read() && !$this->valid());
    }

    public function current(): ?SimpleXMLElement
    {
        return simplexml_import_dom($this->doc->importNode($this->xmlreader->expand(), true));
    }

    public function key(): int
    {
        return $this->key;
    }

    public function next(): void
    {
        if ($this->xmlreader->next($this->tag)) {
            $this->key++;
        }
    }

    public function valid(): bool
    {
        return $this->xmlreader->name === $this->tag;
    }
}
