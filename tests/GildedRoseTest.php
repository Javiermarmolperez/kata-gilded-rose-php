<?php

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function testFoo(): void
    {
        $items = [new Item('foo', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertSame('foo', $items[0]->name);
    }

    public function test_items_degradan_calidad()
    {
        $items = [new Item('foo', 5, 5)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(4, $items[0]->quality);
    }

    public function test_venta_pasada_calidad_degrada_doble()
    {
        $items = [new Item('foo', -1, 5)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(3, $items[0]->quality);
    }

    public function test_calidad_nunca_negativa()
    {
        $items = [new Item('foo', 5, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(0, $items[0]->quality);
    }

    public function test_aged_brie_incrementa_calidad()
    {
        $agedBrie = [new Item('Aged Brie', 4, 5)];
        $gildedRose = new GildedRose($agedBrie);
        $gildedRose->updateQuality();

        $this->assertEquals(6, $agedBrie[0]->quality);
    }

    public function test_calidad_nunca_mayor_de_50()
    {
        $agedBrie = [new Item('Aged Brie', 5, 50)];
        $gildedRose = new GildedRose($agedBrie);
        $gildedRose->updateQuality();

        $this->assertEquals(50, $agedBrie[0]->quality);
    }

    public function test_sulfuras_no_cambia()
    {
        $sulfuras = [new Item('Sulfuras, Hand of Ragnaros', 10, 10)];
        $gildedRose = new GildedRose($sulfuras);
        $gildedRose->updateQuality();

        $this->assertEquals(10, $sulfuras[0]->sell_in);
        $this->assertEquals(10, $sulfuras[0]->quality);
    }

    public static function backstage_reglas()
    {
        return array(
            "incr. 1 si sellIn > 10"            => array(11, 10, 11),
            "incr. 2 si 5 < sellIn <= 10 (max)" => array(10, 10, 12),
            "incr. 2 si 5 < sellIn <= 10 (min)" => array(6,  10, 12),
            "incr. 3 si 0 < sellIn <= 5 (max)"  => array(5,  10, 13),
            "incr. 3 si 0 < sellIn <= 5 (min)"  => array(1,  10, 13),
            "se pone a 0 si sellIn <= 0 (max)"  => array(0,  10, 0),
            "se pone a 0 si sellIn <= 0 (...)"  => array(-1, 10, 0)
        );
    }

    /**
     * @dataProvider backstage_reglas
     */
    public function test_backstage_passes_incrementan_calidad_cada_vez_mas($sell_in, $quality, $expected)
    {
        $backstage = [new Item('Backstage passes to a TAFKAL80ETC concert', $sell_in, $quality)];
        $gildedRose = new GildedRose($backstage);
        $gildedRose->updateQuality();

        $this->assertEquals($expected, $backstage[0]->quality);
    }

}
