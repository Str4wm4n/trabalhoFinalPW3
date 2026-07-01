<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\ProdutoCatalog;
use App\Models\PedidoModel;
use App\Models\PedidoProdutoModel;
use App\Models\ProdutoModel;

class ApiController extends BaseController
{
    use ResponseTrait;

    public function api_status()
    {
        return $this->respond([], 200, "Api funcionando");
    }
    
    public function get_produtos()
    {
        $catalog = new ProdutoCatalog();
        $produtos = $catalog->getProdutos();
        return $this->respond($produtos, 200);
    }

    public function listar_imagens_produtos()
    {
        $imgPath = FCPATH . 'img' . DIRECTORY_SEPARATOR;
        if (!is_dir($imgPath)) {
            return $this->respond([], 200);
        }

        $arquivos = scandir($imgPath);
        if ($arquivos === false) {
            return $this->respond([], 200);
        }

        $permitidos = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
        $imagens = [];

        foreach ($arquivos as $arquivo) {
            if ($arquivo === '.' || $arquivo === '..') {
                continue;
            }

            $fullPath = $imgPath . $arquivo;
            if (!is_file($fullPath)) {
                continue;
            }

            $ext = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
            if (!in_array($ext, $permitidos, true)) {
                continue;
            }

            $imagens[] = [
                'arquivo' => $arquivo,
                'url' => base_url('img/' . $arquivo),
            ];
        }

        usort($imagens, static fn($a, $b) => strcmp($a['arquivo'], $b['arquivo']));

        return $this->respond($imagens, 200);
    }

    public function atualizar_produto($id)
    {
        $dados = $this->request->getJSON(true) ?? [];
        $stockAtual = isset($dados['stock_atual']) ? (int) $dados['stock_atual'] : null;
        $quantidade = isset($dados['quantidade']) ? (int) $dados['quantidade'] : null;

        if ($stockAtual === null || $quantidade === null) {
            return $this->failValidationErrors('Stock atual e quantidade são obrigatórios.');
        }

        $catalog = new ProdutoCatalog();
        $produtoAtualizado = $catalog->atualizarProduto((int) $id, [
            'stock_atual' => max(0, $stockAtual),
            'quantidade' => max(0, $quantidade),
        ]);

        if (!$produtoAtualizado) {
            return $this->failNotFound('Produto não encontrado ou não foi possível atualizar.');
        }

        return $this->respond([
            'status' => true,
            'produto' => $produtoAtualizado,
        ], 200);
    }

    public function criar_produto()
    {
        $dadosJson = [];
        $contentType = strtolower($this->request->getHeaderLine('Content-Type'));
        if (str_contains($contentType, 'application/json')) {
            $dadosJson = $this->request->getJSON(true) ?? [];
        }

        $nome = trim((string) ($this->request->getPost('nome') ?? $dadosJson['nome'] ?? ''));
        $descricao = trim((string) ($this->request->getPost('descricao') ?? $dadosJson['descricao'] ?? ''));
        $categoria = trim((string) ($this->request->getPost('categoria') ?? $dadosJson['categoria'] ?? ''));
        $preco = (float) ($this->request->getPost('preco') ?? $dadosJson['preco'] ?? -1);
        $stockAtual = (int) ($this->request->getPost('stock_atual') ?? $dadosJson['stock_atual'] ?? 0);
        $quantidade = (int) ($this->request->getPost('quantidade') ?? $dadosJson['quantidade'] ?? 0);
        $imagem = trim((string) ($this->request->getPost('imagem') ?? $dadosJson['imagem'] ?? ''));

        $imagemArquivo = $this->request->getFile('imagem_arquivo');
        if ($imagemArquivo && $imagemArquivo->isValid() && !$imagemArquivo->hasMoved()) {
            $permitidos = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
            $ext = strtolower((string) $imagemArquivo->getExtension());

            if (!in_array($ext, $permitidos, true)) {
                return $this->failValidationErrors('Formato de imagem nao suportado.');
            }

            $imgDir = FCPATH . 'img' . DIRECTORY_SEPARATOR;
            if (!is_dir($imgDir) && !mkdir($imgDir, 0775, true) && !is_dir($imgDir)) {
                return $this->failServerError('Nao foi possivel preparar a pasta de imagens.');
            }

            $novoNome = $imagemArquivo->getRandomName();
            if (!$imagemArquivo->move($imgDir, $novoNome)) {
                return $this->failServerError('Nao foi possivel salvar a imagem enviada.');
            }

            $imagem = base_url('img/' . $novoNome);
        }

        if ($nome === '' || $descricao === '' || $categoria === '' || $imagem === '') {
            return $this->failValidationErrors('Nome, descricao, categoria e imagem sao obrigatorios.');
        }

        if ($preco < 0) {
            return $this->failValidationErrors('Preco invalido.');
        }

        if ($stockAtual < 0 || $quantidade < 0) {
            return $this->failValidationErrors('Stock atual e quantidade nao podem ser negativos.');
        }

        $catalog = new ProdutoCatalog();
        $novoProduto = $catalog->criarProduto([
            'nome' => $nome,
            'descricao' => $descricao,
            'categoria' => $categoria,
            'preco' => $preco,
            'imagem' => $imagem,
            'stock_atual' => $stockAtual,
            'quantidade' => $quantidade,
        ]);

        if (!$novoProduto) {
            return $this->failServerError('Nao foi possivel criar o produto.');
        }

        return $this->respondCreated([
            'status' => true,
            'produto' => $novoProduto,
        ]);
    }

    public function remover_produto($id)
    {
        $catalog = new ProdutoCatalog();
        $idProduto = (int) $id;

        if ($idProduto <= 0) {
            return $this->failValidationErrors('Produto invalido.');
        }

        $removido = $catalog->removerProduto($idProduto);
        if (!$removido) {
            return $this->failNotFound('Produto nao encontrado para remocao.');
        }

        return $this->respond([
            'status' => true,
            'message' => 'Produto removido do cardapio com sucesso.',
        ], 200);
    }

    public function checkout()
    {
        $dados = $this->request->getJSON(true);

        if (!$dados || !isset($dados['produtos']) || empty($dados['produtos'])) {
            return $this->failValidationErrors('Pedido inválido.');
        }

        $totemNumero = isset($dados['totem_numero'])
            ? (int) $dados['totem_numero']
            : (isset($dados['mesa_numero']) ? (int) $dados['mesa_numero'] : 0);

        if ($totemNumero < 1) {
            return $this->failValidationErrors('Totem invalido. Informe um identificador de totem valido.');
        }

        try {
            $pedidoModel = new PedidoModel();
            $pedidoProdutoModel = new PedidoProdutoModel();
            $catalog = new ProdutoCatalog();
            $produtosCatalogo = $catalog->getProdutos();
            $db = \Config\Database::connect();

            $db->transStart();
            $idPedido = $pedidoModel->insert([
                'status' => 'novo',
                'mesa_numero' => $totemNumero,
            ]);

            if (!$idPedido) {
                throw new \RuntimeException('Não foi possível criar o pedido.');
            }

            $produtosAtualizados = $produtosCatalogo;

            foreach ($dados['produtos'] as $produto) {
                $idProduto = (int) ($produto['id_produto'] ?? 0);
                $quantidade = max(1, (int) ($produto['quantidade'] ?? 1));
                $precoUnitario = (float) ($produto['preco_unitario'] ?? 0);

                $produtoCatalogo = null;
                foreach ($produtosAtualizados as $index => $itemCatalogo) {
                    if ((int) ($itemCatalogo['id'] ?? 0) === $idProduto) {
                        $produtoCatalogo = $itemCatalogo;
                        $stockAtual = (int) ($itemCatalogo['stock_atual'] ?? 0);
                        if ($quantidade > $stockAtual) {
                            throw new \RuntimeException('Estoque insuficiente para o produto ' . ($itemCatalogo['nome'] ?? 'selecionado') . '.');
                        }

                        $novoStock = max(0, $stockAtual - $quantidade);
                        $produtosAtualizados[$index]['stock_atual'] = $novoStock;
                        $produtosAtualizados[$index]['disponivel'] = $novoStock > 0;
                        break;
                    }
                }

                if ($produtoCatalogo === null) {
                    throw new \RuntimeException('Produto não encontrado no catálogo.');
                }

                $pedidoProdutoModel->insert([
                    'id_pedido' => $idPedido,
                    'id_produto' => $idProduto,
                    'quantidade' => $quantidade,
                    'preco_unitario' => $precoUnitario
                ]);
            }

            if (!$catalog->salvarProdutos($produtosAtualizados)) {
                throw new \RuntimeException('Não foi possível atualizar o estoque.');
            }

            $db->transComplete();

            if ($db->transStatus() == false) {
                throw new \RuntimeException('Erro ao cadastrar pedido.');
            }

            return $this->respondCreated([
                'status'    => true,
                'id_pedido' => $idPedido,
                'totem_numero' => $totemNumero,
                'mesa_numero' => $totemNumero,
            ]);
        } catch (\Throwable $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function get_pedidos()
    {
        $pedidoModel = new PedidoModel();
        $pedidos = $pedidoModel->orderBy('id', 'DESC')->findAll();
        return $this->respond($pedidos, 200);
    }

    public function get_pedido_detalhes($id)
    {
        $pedidoModel = new PedidoModel();
        $pedido = $pedidoModel->find($id);

        if (!$pedido) {
            return $this->failNotFound('Pedido não encontrado.');
        }

        $pedidoProdutoModel = new PedidoProdutoModel();
        $catalog = new ProdutoCatalog();
        $produtosCatalogo = $catalog->getProdutos();

        $itens = $pedidoProdutoModel
            ->where('id_pedido', $id)
            ->findAll();

        foreach ($itens as &$item) {
            $item['produto_nome'] = $this->resolverNomeProduto(
                $item['id_produto'] ?? null,
                $produtosCatalogo
            );
        }
        unset($item);

        $pedido['itens'] = $itens;
        return $this->respond($pedido, 200);
    }

    public function produtos_com_estoque()
    {
        $catalog = new ProdutoCatalog();
        $produtos = $catalog->getProdutos();

        foreach ($produtos as &$produto) {
            $stockAtual = (int) ($produto['stock_atual'] ?? 0);
            $produto['disponivel'] = $stockAtual > 0;
            $produto['quantidade'] = (int) ($produto['quantidade'] ?? 0);
            $produto['stock_atual'] = $stockAtual;
        }
        unset($produto);

        return $this->respond($produtos, 200);
    }

    private function resolverNomeProduto($idProduto, array $produtosCatalogo): string
    {
        foreach ($produtosCatalogo as $produto) {
            if ((int) ($produto['id'] ?? 0) === (int) $idProduto) {
                return (string) ($produto['nome'] ?? 'Produto');
            }
        }

        return 'Produto';
    }

    public function atualizar_status($id)
    {
        $dados = $this->request->getJSON(true);
        $status = $dados['status'] ?? null;

        if (!$status) {
            return $this->failValidationErrors('Status não informado.');
        }

        $pedidoModel = new PedidoModel();
        if ($pedidoModel->update($id, ['status' => $status])) {
            return $this->respond(['status' => true, 'message' => 'Status atualizado.']);
        }

        return $this->failServerError('Erro ao atualizar status.');
    }
}
