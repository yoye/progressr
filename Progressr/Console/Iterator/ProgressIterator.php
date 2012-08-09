<?php

namespace Progressr\Console\Iterator;

use Iterator;
use Symfony\Component\Console\Output\OutputInterface;

class ProgressIterator implements Iterator
{
    const FLAG_INFO = 1;    // Info output style
    const FLAG_BAR = 2;    // Bar output style

    /**
     * Progress output helper
     * 
     * @var Progress
     */
    protected $progress;

    /**
     * Iterator elements
     * 
     * @var array
     */
    protected $elements;

    /**
     * Flag for output style
     * 
     * @var int
     */
    protected $flag;

    /**
     *
     * @param OutputInterface $output
     * @param array $elements
     * @param int $flag 
     */
    public function __construct(OutputInterface $output, array $elements = array(), $flag = self::FLAG_INFO)
    {
        $this->elements = $elements;
        $this->progress = new Progress($output, count($elements));
        $this->flag = $flag;
    }

    /**
     * Set output style flag
     * 
     * @param int $flag 
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        reset($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->progress
            ->setCurrent($this->key() + 1);

        if ($this->flag === self::FLAG_BAR) {
            $this->progress->bar();
        } else {
            $this->progress->info();
        }

        return next($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->elements[$this->key()]);
    }

}
