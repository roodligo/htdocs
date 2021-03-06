<?php
if (session_id() == '' || !isset($_SESSION)) {
    session_start();

    include_once "conexao.php";
}

if (isset($_SESSION['email'])) {
    $email = ($_SESSION['email']);
    $result1 = $conn->query("SELECT * FROM cad_cliente WHERE email = '$email' and adm = 1");
    $num = mysqli_num_rows($result1);
    if ($num == 1) {

        echo '<div class="container text-white"><h3>Pedidos: </h3></div>';
        $custo = 0;
        $total = 0;
        $cap_primeiropedido = $conn->query("SELECT min(codigo_pedido) as 'codigo_pedido' FROM pedidos");
        while ($cap1 = $cap_primeiropedido->fetch_object()) {
            $primeiropedido = $cap1->codigo_pedido;
        }
        $cap_ultimopedido = $conn->query("SELECT max(codigo_pedido) as 'codigo_pedido' FROM pedidos");
        while ($cap2 = $cap_ultimopedido->fetch_object()) {
            $ultimopedido = $cap2->codigo_pedido;
        }


        $codigopedido = $primeiropedido;
        while ($codigopedido <= $ultimopedido) {




            $sql3 = "SELECT
    pedidos.codigo_pedido as 'pedido',
    cad_cliente.nome as 'nomecliente',
    pedidos.status_pedido as 'status',
    cad_produto.nome as 'nome',
    pedidos_itens.quantidade as 'quantidade',
    ROUND((pedidos_itens.unitario),2) as 'unitario',
    ROUND((pedidos_itens.quantidade * pedidos_itens.unitario),2) as 'total',
    ROUND((total),2) as 'geral'
    from pedidos_itens
    inner join cad_produto 
    on cad_produto.codigo = pedidos_itens.codigo_produto
    inner join pedidos 
    on pedidos_itens.codigo_pedido = pedidos.codigo_pedido
    inner join cad_cliente 
    on cad_cliente.id = pedidos.codigo_cliente
    where pedidos.codigo_pedido = '$codigopedido'";

            $result3 = mysqli_query($conn, $sql3);
            if ($result3 === FALSE) {
                die('erro');
            } else {



                echo '<div class="card mt-3">';
                echo '<h4>Pedido:  ' . $codigopedido . '</h4>';
                echo '<table class="table table-responsive">';
                echo '<tr>';
                echo '<th>Descrição</th>';
                echo '<th>Qtd</th>';
                echo '<th>Unitário</th>';
                echo '<th>Total</th>';
                echo '</tr>';



                while ($obj3 = $result3->fetch_object()) {

                    echo '<tr>';
                    echo '<td>' . $obj3->nome . '</td>';
                    echo '<td>' . $obj3->quantidade . '</td>';
                    echo '<td>' . $obj3->unitario . '</td>';
                    echo '<td>' . $obj3->total . '</td>';
                    echo '</tr>';
                    $cliente = $obj3->nomecliente;
                    $total = $obj3->geral;

                    switch ($obj3->status) {
                        case 0:
                            $status = '<h4 class="text-info">Pedido Recebido</h4>';
                            $acao =   '<p><a class="text-reset" href="../php/altera_status_pedido.php?status=1&pedido=' . $codigopedido . '"><input type="submit" value="Separar" class="btn btn-outline-primary " /></a></p>';
                            $cancela = '<div>' . $acao . ' <a class="text-reset" href="../php/altera_status_pedido.php?status=4&pedido=' . $codigopedido . '"><input type="submit" value="Cancelar Pedido" class="btn btn-danger " /></a></div>';
                            break;
                        case 1:
                            $status = '<h4 class="text-primary">Pedido em Separação</h4>';
                            $acao =   '<p><a class="text-reset" href="../php/altera_status_pedido.php?status=2&pedido=' . $codigopedido . '"><input type="submit" value="Entregar" class="btn btn-outline-warning " /></a></p>';
                            $cancela = '<div>' . $acao . ' <a class="text-reset" href="../php/altera_status_pedido.php?status=4&pedido=' . $codigopedido . '"><input type="submit" value="Cancelar Pedido" class="btn btn-danger " /></a></div>';
                            break;
                        case 2:
                            $status = '<h4 class="text-warning">Pedido a Caminho</h4>';
                            $acao =   '<p><a class="text-reset" href="../php/altera_status_pedido.php?status=3&pedido=' . $codigopedido . '"><input type="submit" value="Confirmar Entrega" class="btn btn-outline-success " /></a></p>';
                            $cancela = '<div>' . $acao . ' <a class="text-reset" href="../php/altera_status_pedido.php?status=4&pedido=' . $codigopedido . '"><input type="submit" value="Cancelar Pedido" class="btn btn-danger " /></a></div>';
                            break;
                        case 3:
                            $status = '<h4 class="text-success">Pedido Entregue</h4>';
                            $acao = NULL;
                            $cancela = NULL;
                            break;
                        case 4:
                            $status = '<h4 class="text-danger">Pedido Cancelado</h4>';
                            $acao = NULL;
                            $cancela = NULL;
                            break;
                    }
                }
                echo '</table>';
                echo '<div class="mb-3"><h6>Total R$: ' . $total . '</h6></div>';
                echo '<div class="mb-3"><h6>Cliente: ' . $cliente . '</h6></div>';
                echo '<div>' . $status . '</div>';
                echo '<div>' . $cancela . '</div>';
                echo '<br>';
                echo ' </div>';
                $codigopedido++;
            }
        }
    }
} else {
    echo '<div class="card container"><h2>Faça login para acessar os Pedidos!</h2></div>';
}
