<?php

namespace App\Tests;


use App\Services\slugger;
use PHPUnit\Framework\TestCase;

class SlugTest extends TestCase
{
    public function testSlugg(): void
    {
        $titre = "mon article seiko";
        $slug = new slugger();
        $titreSlug = $slug->slugify($titre,'-');
        $this->assertEquals($titreSlug,"mon-article-seiko");
        
    }
}
