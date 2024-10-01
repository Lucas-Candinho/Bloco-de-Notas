<?php
include '../coisas_do_db/db_connect.php';

$sql = "SELECT * FROM categorias";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['enviar_categoria'])) {
        $nome_categoria = $_POST['nome_categoria'];

        $sql = "INSERT INTO categorias(id_categoria, nome_categoria) VALUE (null, '$nome_categoria')";

        if ($conn->query($sql) === false) {
            echo "Erro: " . $sql . "<br>" . $conn->error;
        } else {
            header("Location: index.php");
        }

    } elseif (isset($_POST['editar_categoria'])) {
        if (isset($_POST['id_categoria']) && $_POST['id_categoria'] != '') {
            $id = $_POST['id_categoria'];
            $nome_categoria = $_POST['nome_categoria'];

            $sql = "UPDATE categorias SET nome_categoria='$nome_categoria' WHERE id_categoria=$id";

            if ($conn->query($sql) === TRUE) {
                echo "Registro atualizado com sucesso";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }

            header("Location: index.php");
        } else {
            echo "Para editar, informe o ID";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['delete'])) {
        $id = $_GET['id_categoria'];

        // Check for related notas
        $checkNotas = "SELECT * FROM notas WHERE fk_categoria = '$id'";
        $resultNotas = $conn->query($checkNotas);

        if ($resultNotas->num_rows > 0) {
            echo "Não é possível excluir a categoria. Existem notas associadas a esta categoria.";
        } else {
            $sql = "DELETE FROM categorias WHERE id_categoria = '$id'";
            if ($conn->query($sql) === false) {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            } else {
                header("Location: index.php");
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Categorias</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <form method="POST" action="index.php">
        <label for="id_categoria">ID Categoria (Adicionar apenas para edição)</label>
        <input type="number" id="id_categoria" name="id_categoria" />
        <label for="nome_categoria">Nome da Categoria</label>
        <input type="text" id="nome_categoria" name="nome_categoria" />
        <button type="submit" name="enviar_categoria">Adicionar</button>
        <button type="submit" name="editar_categoria">Editar</button>
    </form>

    <?php
    if ($result->num_rows > 0) {
        echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nome Categoria</th>
                <th>Ações</th>
            </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id_categoria']}</td>
                    <td>{$row['nome_categoria']}</td>
                    <td>
                        <a href='index.php?id_categoria={$row['id_categoria']}&delete=1'>Excluir</a>
                    </td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "Nenhum registro encontrado.";
    }
    $conn->close();
    ?>

    <a href="../index.php" class="back-button">Voltar ao Início</a>
</body>
</html>
