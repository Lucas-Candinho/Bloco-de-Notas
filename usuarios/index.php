<?php
include '../coisas_do_db/db_connect.php';


$sql = "SELECT * FROM usuarios";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['enviar_usuario'])) {
        $nome_usuario = $_POST['nome_usuario'];
        $senha_usuario = $_POST['senha_usuario'];

        $sql = "INSERT INTO usuarios (nome_usuario, senha_usuario) 
                VALUES ('$nome_usuario', '$senha_usuario')";

        if ($conn->query($sql) === false) {
            echo "Erro: " . $sql . "<br>" . $conn->error;
        } else {
            header("Location: index.php");
        }

    } elseif (isset($_POST['editar_usuario'])) {
        if (isset($_POST['id_usuario']) && $_POST['id_usuario'] != '') {
            $id = $_POST['id_usuario'];
            $nome_usuario = $_POST['nome_usuario'];
            $senha_usuario = $_POST['senha_usuario'];

            $sql = "UPDATE usuarios SET nome_usuario='$nome_usuario', senha_usuario='$senha_usuario' 
                    WHERE id_usuario=$id";

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
        $id = $_GET['id_usuario'];

        // Check for related notas
        $checkNotas = "SELECT * FROM notas WHERE fk_usuario = '$id'";
        $resultNotas = $conn->query($checkNotas);

        if ($resultNotas->num_rows > 0) {
            echo "Não é possível excluir o usuário. Existem notas associadas a este usuário.";
        } else {
            $sql = "DELETE FROM usuarios WHERE id_usuario = '$id'";
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
    <title>Gerenciar Usuários</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <form method="POST" action="index.php">
        <label for="id_usuario">ID do Usuário (Adicionar apenas para edição)</label>
        <input type="number" id="id_usuario" name="id_usuario" />
        <label for="nome_usuario">Nome do Usuário</label>
        <input type="text" id="nome_usuario" name="nome_usuario" required />
        <label for="senha_usuario">Senha do Usuário</label>
        <input type="password" id="senha_usuario" name="senha_usuario" required />
        <button type="submit" name="enviar_usuario">Adicionar</button>
        <button type="submit" name="editar_usuario">Editar</button>
    </form>

    <?php
    if ($result->num_rows > 0) {
        echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nome Usuário</th>
                <th>Ações</th>
            </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id_usuario']}</td>
                    <td>{$row['nome_usuario']}</td>
                    <td>
                        <a href='index.php?id_usuario={$row['id_usuario']}&delete=1'>Excluir</a>
                    </td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "Nenhum usuário encontrado.";
    }
    $conn->close();
    ?>

    <a href="../index.php" class="back-button">Voltar ao Início</a>
</body>
</html>
