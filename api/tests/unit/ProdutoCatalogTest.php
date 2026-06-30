<?php

use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ProdutoCatalogTest extends CIUnitTestCase
{
    public function testLoadsProductsFromJsonFile(): void
    {
        $catalog = new \App\Libraries\ProdutoCatalog();
        $produtos = $catalog->getProdutosFromJson();

        $this->assertNotEmpty($produtos);
        $this->assertSame('Café Expresso', $produtos[0]['nome']);
        $this->assertSame('Bebidas', $produtos[0]['categoria']);
    }
}
