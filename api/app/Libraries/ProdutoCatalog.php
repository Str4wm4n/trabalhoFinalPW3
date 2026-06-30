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

    public function criarProduto(array $dados): ?array
    {
        $produtos = $this->getProdutosFromJson();

        $maiorId = 0;
        foreach ($produtos as $produto) {
            $idAtual = (int) ($produto['id'] ?? 0);
            if ($idAtual > $maiorId) {
                $maiorId = $idAtual;
            }
        }

        $stockAtual = max(0, (int) ($dados['stock_atual'] ?? 0));
        $quantidade = max(0, (int) ($dados['quantidade'] ?? 0));
        $preco = isset($dados['preco']) ? number_format((float) $dados['preco'], 2, '.', '') : '0.00';

        $novoProduto = [
            'id' => $maiorId + 1,
            'nome' => trim((string) ($dados['nome'] ?? '')),
            'descricao' => trim((string) ($dados['descricao'] ?? '')),
            'categoria' => trim((string) ($dados['categoria'] ?? '')),
            'preco' => $preco,
            'imagem' => trim((string) ($dados['imagem'] ?? '')),
            'stock_atual' => $stockAtual,
            'quantidade' => $quantidade,
            'disponivel' => $stockAtual > 0,
        ];

        $produtos[] = $novoProduto;

        if (!$this->salvarProdutos($produtos)) {
            return null;
        }

        return $novoProduto;
    }
}
