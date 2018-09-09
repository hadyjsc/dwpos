<?php

namespace Marquine\Etl\Transformers;

class Trim implements TransformerInterface
{
    /**
     * Transformer columns.
     *
     * @var array
     */
    public $columns;

    /**
     * The trim type.
     *
     * @var string
     */
    public $type = 'both';

    /**
     * The trim mask.
     *
     * @var string
     */
    public $mask = " \t\n\r\0\x0B";

    /**
     * Execute a transformation.
     *
     * @param array $items
     * @return array
     */
    public function transform($items)
    {
        $this->normalizeType();

        return array_map(function($row) {
            if ($this->columns) {
                foreach ($this->columns as $column) {
                    $row[$column] = call_user_func($this->type, $row[$column], $this->mask);
                }
            } else {
                foreach ($row as $column => $value) {
                    $row[$column] = call_user_func($this->type, $value, $this->mask);
                }
            }

            return $row;
        }, $items);
    }

    /**
     * Normalize the trim function name.
     *
     * @return void
     */
    protected function normalizeType()
    {
        switch ($this->type) {
            case 'ltrim':
            case 'start':
            case 'left':
                $this->type = 'ltrim';
                break;

            case 'rtrim':
            case 'end':
            case 'right':
                $this->type = 'rtrim';
                break;

            case 'trim':
            case 'all':
            case 'both':
                $this->type = 'trim';
                break;

            default:
                $this->type = 'trim';
        }
    }
}
