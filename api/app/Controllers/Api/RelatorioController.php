<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\PedidoProdutoModel;
use App\Models\PedidoModel;
use App\Libraries\ProdutoCatalog;
use CodeIgniter\API\ResponseTrait;

class RelatorioController extends BaseController
{
    use ResponseTrait;

    public function vendas()
    {
        $db = \Config\Database::connect();
        $dataInicio = $this->request->getGet('inicio');
        $dataFim = $this->request->getGet('fim');
        $limite = (int) ($this->request->getGet('limite') ?? 10);

        if ($limite <= 0) {
            $limite = 10;
        }

        if (!$dataInicio && !$dataFim) {
            $dataFim = date('Y-m-d');
            $dataInicio = date('Y-m-d', strtotime('-9 days'));
        }

        $sql = "
            SELECT
                p.id AS pedido_id,
                DATE(p.created_at) AS data,
                p.status,
                p.mesa_numero,
                SUM(pp.quantidade * pp.preco_unitario) AS total_vendas,
                SUM(pp.quantidade) AS total_itens
            FROM pedidos p
            INNER JOIN pedido_produtos pp ON pp.id_pedido = p.id
            WHERE 1=1
        ";

        $params = [];
        if ($dataInicio) {
            $sql .= " AND DATE(p.created_at) >= ?";
            $params[] = $dataInicio;
        }

        if ($dataFim) {
            $sql .= " AND DATE(p.created_at) <= ?";
            $params[] = $dataFim;
        }

        $sql .= " GROUP BY p.id, DATE(p.created_at), p.status, p.mesa_numero ORDER BY p.created_at DESC LIMIT ?";
        $params[] = $limite;

        $rows = $db->query($sql, $params)->getResultArray();

        $chartSql = "
            SELECT DATE(p.created_at) AS data, SUM(pp.quantidade * pp.preco_unitario) AS total_vendas
            FROM pedidos p
            INNER JOIN pedido_produtos pp ON pp.id_pedido = p.id
            WHERE 1=1
        ";

        $chartParams = [];
        if ($dataInicio) {
            $chartSql .= " AND DATE(p.created_at) >= ?";
            $chartParams[] = $dataInicio;
        }
        if ($dataFim) {
            $chartSql .= " AND DATE(p.created_at) <= ?";
            $chartParams[] = $dataFim;
        }

        $chartSql .= " GROUP BY DATE(p.created_at) ORDER BY DATE(p.created_at) ASC";
        $chartRows = $db->query($chartSql, $chartParams)->getResultArray();

        $mesaSql = "
            SELECT
                p.mesa_numero,
                COUNT(DISTINCT p.id) AS total_pedidos,
                SUM(pp.quantidade * pp.preco_unitario) AS total_vendas
            FROM pedidos p
            INNER JOIN pedido_produtos pp ON pp.id_pedido = p.id
            WHERE p.mesa_numero IS NOT NULL
        ";

        $mesaParams = [];
        if ($dataInicio) {
            $mesaSql .= " AND DATE(p.created_at) >= ?";
            $mesaParams[] = $dataInicio;
        }
        if ($dataFim) {
            $mesaSql .= " AND DATE(p.created_at) <= ?";
            $mesaParams[] = $dataFim;
        }

        $mesaSql .= " GROUP BY p.mesa_numero ORDER BY total_vendas DESC, total_pedidos DESC";
        $mesasRows = $db->query($mesaSql, $mesaParams)->getResultArray();

        $mesasRanking = array_map(static function ($row) {
            return [
                'mesa_numero' => (int) $row['mesa_numero'],
                'total_pedidos' => (int) $row['total_pedidos'],
                'total_vendas' => (float) $row['total_vendas'],
            ];
        }, $mesasRows);

        $labels = [];
        $dataPoints = [];
        foreach ($chartRows as $row) {
            $labels[] = $row['data'];
            $dataPoints[] = (float) $row['total_vendas'];
        }

        return $this->respond([
            'labels' => $labels,
            'vendas' => $dataPoints,
            'meta' => [
                'periodo' => $dataInicio || $dataFim ? 'Intervalo filtrado' : 'Últimos 10 dias',
                'total_vendido' => array_sum($dataPoints),
                'pedidos' => count($rows),
                'itens_vendidos' => array_sum(array_column($rows, 'total_itens')),
                'limite' => $limite,
                'inicio' => $dataInicio,
                'fim' => $dataFim,
                'mesa_lider' => $mesasRanking[0]['mesa_numero'] ?? null,
            ],
            'mesas_ranking' => $mesasRanking,
            'linhas' => array_map(static function ($row) {
                return [
                    'pedido_id' => (int) $row['pedido_id'],
                    'data' => $row['data'],
                    'valor_total' => (float) $row['total_vendas'],
                    'itens' => (int) $row['total_itens'],
                    'status' => $row['status'] ?? 'novo',
                    'mesa_numero' => isset($row['mesa_numero']) ? (int) $row['mesa_numero'] : null,
                ];
            }, $rows),
        ], 200);
    }

    public function consumo()
    {
        $db = \Config\Database::connect();
        $catalog = new ProdutoCatalog();
        $produtos = $catalog->getProdutos();

        $pedidoProdutos = $db->query("
            SELECT id_produto, SUM(quantidade) AS quantidade_vendida
            FROM pedido_produtos
            GROUP BY id_produto
        ")->getResultArray();

        $quantidadesVendidas = [];
        foreach ($pedidoProdutos as $item) {
            $quantidadesVendidas[(int) $item['id_produto']] = (int) $item['quantidade_vendida'];
        }

        $items = array_map(static function ($produto) use ($quantidadesVendidas) {
            $stockAtual = (int) ($produto['stock_atual'] ?? 0);
            $quantidadeVendida = (int) ($quantidadesVendidas[(int) ($produto['id'] ?? 0)] ?? 0);

            return [
                'id' => (int) ($produto['id'] ?? 0),
                'produto' => $produto['nome'] ?? 'Produto',
                'categoria' => $produto['categoria'] ?? null,
                'stock_atual' => $stockAtual,
                'quantidade' => $quantidadeVendida,
                'disponivel' => $stockAtual > 0,
                'imagem' => $produto['imagem'] ?? null,
            ];
        }, $produtos);

        return $this->respond([
            'periodo' => 'Catálogo atual',
            'itens' => $items,
        ], 200);
    }
}
