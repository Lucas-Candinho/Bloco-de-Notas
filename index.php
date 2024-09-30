<?php
    include './coisas_do_db/db_connect.php';

    $sql = "SELECT notas.id_nota, notas.titulo_nota, usuarios.nome_usuario, categorias.nome_categoria, notas.data_criacao_nota FROM notas
            INNER JOIN usuarios ON usuarios.id_usuario = notas.fk_usuario
            INNER JOIN categorias ON categorias.nome_categoria = notas.fk_categoria";
    $result = $conn -> query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    
    <div id="form-data">
        
        <form method="POST" action="index.php">
            <h1>Main Screen</h1>
            <div class="button">
                <a href="./usuarios/index.php"><button>Adicionar Usuarios</button></a>
            </div>
            <div class="button">
                <a href="./notas/index.php"><button>Adicionar Notas</button></a>
            </div>
            <div class="button">
                <a href="./categorias/index.php"><button>Adicionar Categoria</button></a>
            </div>
            
        </form>
    </div>
    <section id="table">
    <?php
        if ($result -> num_rows > 0){
            echo "<table border='1'>
                <tr>
                    <th> ID </th>
                    <th> Titulo Nota </th>
                    <th> Criador Nota </th>
                    <th> Categoria Nota </th>
                    <th> Data de Criacao </th>
                </tr>";
                while($row = $result -> fetch_assoc()){
                    echo "<tr>
                            <td> {$row['id_nota']} </td>
                            <td> {$row['titulo_nota']} </td>
                            <td> {$row['nome_usuario']} </td>
                            <td> {$row['nome_categoria']} </td>
                            <td> {$row['data_criacao_nota']} </td>
        
                            <td>
                                <a href='index.php?id={$row['id_nota']}&delete=1'>Excluir</a>
                            </td>
                        </tr>";
                }
            echo "</table>";
        }else{
            echo "Nenhum registro encontrado.";
        }
        $conn -> close();
    ?>
    
    </section>
    
</body>
</html>