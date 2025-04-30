<?php
require_once '../../includes/conexao.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("
        SELECT s.id, s.data_ida, s.data_volta, 
               u.nome AS solicitante_nome, 
               um.nome AS motorista_nome, 
               s.status
        FROM solicitacoes s
        JOIN usuarios u ON s.solicitante_id = u.id
        JOIN motoristas m ON s.motorista_id = m.id
        JOIN usuarios um ON m.usuario_id = um.id
    ");

    $viagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $eventos = [];

    foreach ($viagens as $v) {
        $eventos[] = [
            'id' => $v['id'],
            'title' => "Motorista: {$v['motorista_nome']} | Solicitante: {$v['solicitante_nome']}",
            'start' => $v['data_ida'],
            'end' => $v['data_volta'],
            'color' => match ($v['status']) {
                'finalizado' => '#28a745',
                'pendente' => '#ffc107',
                default => '#17a2b8'
            }
        ];
    }

    echo json_encode($eventos);
} catch (Exception $e) {
    echo json_encode(['erro' => 'Erro ao buscar eventos: ' . $e->getMessage()]);
}
