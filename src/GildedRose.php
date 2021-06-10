<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @var Item[]
     */
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {

            switch ($item->name)
            {
                case ('Sulfuras, Hand of Ragnaros'):
                    break;

                case ('Aged Brie'):
                    if ($item->quality < 50)
                    {
                        $item->quality = $this->quality_more_one($item);

                        $item->sell_in = $this->sell_in_decreased_one($item);

                        if ($item->sell_in < 0)
                        {
                            $item->quality = $this->quality_more_one($item);
                        }
                    }
                    break;

                case ('Backstage passes to a TAFKAL80ETC concert'):

                    if ($item->quality < 50)
                    {
                        $item->quality = $this->quality_more_one($item);

                        if ($item->sell_in < 11 and $item->quality < 50 )
                        {
                            $item->quality = $this->quality_more_one($item);
                        }
                        if ($item->sell_in < 6 and $item->quality < 50)
                        {
                            $item->quality = $this->quality_more_one($item);
                        }
                    }

                    $item->sell_in = $this->sell_in_decreased_one($item);
                    if ($item->sell_in < 0)
                    {
                        $item->quality = $item->quality - $item->quality;
                    }

                    break;

                default:
                    if ($item->quality > 0)
                    {
                        $item->quality = $this->quality_decreased_one($item);

                        $item->sell_in = $this->sell_in_decreased_one($item);

                        if ($item->sell_in < 0)
                        {
                            $item->quality = $this->quality_decreased_one($item);
                        }
                    }

                    break;
            }

        }
    }

    public function quality_decreased_one($item)
    {
        $item->quality = $item->quality - 1;

        return $item->quality;

    }

    public function quality_more_one($item)
    {
        $item->quality = $item->quality + 1;

        return $item->quality;

    }

    public function sell_in_decreased_one($item)
    {
        $item->sell_in = $item->sell_in - 1;

        return $item->sell_in;

    }
}
