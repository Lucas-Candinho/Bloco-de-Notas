<?php
include '../coisas_do_db/db_connect.php';

$sqlCategorias = "SELECT * FROM categorias";
$resultCategorias = $conn->query($sqlCategorias);

$sqlUsuarios = "SELECT * FROM usuarios";
$resultUsuarios = $conn->query($sqlUsuarios);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['enviar_nota'])) {
        $titulo_nota = $_POST['titulo_nota'];
        $conteudo_nota = $_POST['conteudo_nota'];
        $fk_usuario = $_POST['fk_usuario'];
        $senha_usuario = $_POST['senha_usuario'];
        $fk_categoria = $_POST['fk_categoria'];

        $sqlVerificarSenha = "SELECT * FROM usuarios WHERE id_usuario='$fk_usuario' AND senha_usuario='$senha_usuario'";
        $resultVerificarSenha = $conn->query($sqlVerificarSenha);

        if ($resultVerificarSenha->num_rows > 0) {
            $data_criacao_nota = date('Y-m-d');

            if (isset($_POST['id_nota'])) {

                $id_nota = $_POST['id_nota'];
                $sql = "UPDATE notas SET titulo_nota='$titulo_nota', conteudo_nota='$conteudo_nota', fk_usuario='$fk_usuario', fk_categoria='$fk_categoria' WHERE id_nota='$id_nota'";
            } else {

                $sql = "INSERT INTO notas (titulo_nota, conteudo_nota, data_criacao_nota, fk_usuario, fk_categoria) 
                        VALUES ('$titulo_nota', '$conteudo_nota', '$data_criacao_nota', '$fk_usuario', '$fk_categoria')";
            }

            if ($conn->query($sql) === false) {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            } else {
                header("Location: index.php");
            }
        } else {
            echo "Senha incorreta para o usuário selecionado.";
        }
    }
}

$sqlNotas = "SELECT * FROM notas";
$resultNotas = $conn->query($sqlNotas);

$notaParaEditar = null;
if (isset($_GET['id_nota'])) {
    $id_nota = $_GET['id_nota'];
    $sqlNota = "SELECT * FROM notas WHERE id_nota='$id_nota'";
    $resultNota = $conn->query($sqlNota);
    $notaParaEditar = $resultNota->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Notas</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <h1><?php echo $notaParaEditar ? "Editar Nota" : "Adicionar Nota"; ?></h1>
    <form method="POST" action="index.php">
        <?php if ($notaParaEditar): ?>
            <input type="hidden" name="id_nota" value="<?php echo $notaParaEditar['id_nota']; ?>" />
        <?php endif; ?>
        <label for="titulo_nota">Título da Nota</label>
        <input type="text" id="titulo_nota" name="titulo_nota" value="<?php echo $notaParaEditar['titulo_nota'] ?? ''; ?>" required />
        
        <p><label for="conteudo_nota">Conteúdo da Nota:</label></p>
        <textarea id="conteudo_nota" name="conteudo_nota" rows="4" cols="50" required><?php echo $notaParaEditar['conteudo_nota'] ?? ''; ?></textarea>

        <label for="fk_usuario">Usuário</label>
        <select id="fk_usuario" name="fk_usuario" required>
            <option value="">Selecione um usuário</option>
            <?php
            if ($resultUsuarios->num_rows > 0) {
                while ($row = $resultUsuarios->fetch_assoc()) {
                    $selected = isset($notaParaEditar) && $notaParaEditar['fk_usuario'] == $row['id_usuario'] ? "selected" : "";
                    echo "<option value='{$row['id_usuario']}' $selected>{$row['nome_usuario']}</option>";
                }
            }
            ?>
        </select>

        <label for="senha_usuario">Senha do Usuário</label>
        <input type="password" id="senha_usuario" name="senha_usuario" required />

        <label for="fk_categoria">Categoria</label>
        <select id="fk_categoria" name="fk_categoria" required>
            <option value="">Selecione uma categoria</option>
            <?php
            if ($resultCategorias->num_rows > 0) {
                while ($row = $resultCategorias->fetch_assoc()) {
                    $selected = isset($notaParaEditar) && $notaParaEditar['fk_categoria'] == $row['id_categoria'] ? "selected" : "";
                    echo "<option value='{$row['id_categoria']}' $selected>{$row['nome_categoria']}</option>";
                }
            }
            ?>
        </select>

        <button type="submit" name="enviar_nota"><?php echo $notaParaEditar ? "Atualizar Nota" : "Adicionar Nota"; ?></button>
    </form>

    <h2>Notas Existentes</h2>
    <?php
    if ($resultNotas->num_rows > 0) {
        echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Conteúdo</th>
                <th>Usuário</th>
                <th>Categoria</th>
                <th>Ações</th>
            </tr>";
        while ($row = $resultNotas->fetch_assoc()) {
            $usuarioSql = "SELECT nome_usuario FROM usuarios WHERE id_usuario = {$row['fk_usuario']}";
            $usuarioResult = $conn->query($usuarioSql);
            $usuario = $usuarioResult->fetch_assoc();

            $categoriaSql = "SELECT nome_categoria FROM categorias WHERE id_categoria = {$row['fk_categoria']}";
            $categoriaResult = $conn->query($categoriaSql);
            $categoria = $categoriaResult->fetch_assoc();

            echo "<tr>
                    <td>{$row['id_nota']}</td>
                    <td>{$row['titulo_nota']}</td>
                    <td>{$row['conteudo_nota']}</td>
                    <td>{$usuario['nome_usuario']}</td>
                    <td>{$categoria['nome_categoria']}</td>
                    <td>
                        <a href='index.php?id_nota={$row['id_nota']}'>Editar</a> | 
                        <a href='index.php?id_nota={$row['id_nota']}&delete=1'>Excluir</a>
                    </td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "Nenhuma nota encontrada.";
    }

    $conn->close();
    ?>

    <a href="../index.php" class="back-button">Voltar ao Início</a>
</body>
</html>
