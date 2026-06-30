<?php

namespace App\Libraries;

use App\Models\ProdutoModel;

class ProdutoCatalog
{
    public function getProdutos(): array
    {
        $produtosJson = $this->getProdutosFromJson();

        if (!empty($produtosJson)) {
            return $produtosJson;
        }

        $produtoModel = new ProdutoModel();
        return $produtoModel->findAll();
    }

    public function getProdutosFromJson(): array
    {
        $path = WRITEPATH . 'produtos.json';

        if (!is_file($path)) {
            return [];
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            return [];
        }

        $data = json_decode($contents, true);
        if (!is_array($data)) {
            return [];
        }

        if (isset($data['produtos']) && is_array($data['produtos'])) {
            $data = $data['produtos'];
        }

        if (!is_array($data)) {
            return [];
        }

        foreach ($data as $index => $produto) {
            if (!isset($produto['id']) || empty($produto['id'])) {
                $data[$index]['id'] = $index + 1;
            }

            if (!isset($data[$index]['stock_atual'])) {
                $data[$index]['stock_atual'] = 100;
            }

            if (!isset($data[$index]['quantidade'])) {
                $data[$index]['quantidade'] = 0;
            }
        }

        return $data;
    }

    public function salvarProdutos(array $produtos): bool
    {
        $path = WRITEPATH . 'produtos.json';

        $payload = json_encode(['produtos' => array_values($produtos)], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($payload === false) {
            return false;
        }

        return file_put_contents($path, $payload . PHP_EOL) !== false;
    }

    public function atualizarProduto(int $id, array $dados): ?array
    {
        $produtos = $this->getProdutosFromJson();

        foreach ($produtos as $index => $produto) {
            if ((int) ($produto['id'] ?? 0) === $id) {
                $produtos[$index] = array_merge($produto, $dados, ['id' => $id]);

                if (!$this->salvarProdutos($produtos)) {
                    return null;
                }

                return $produtos[$index];
            }
        }

        return null;
    }
}
