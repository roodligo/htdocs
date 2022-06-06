<?php
if (session_id() == '' || !isset($_SESSION)) {
    session_start();
    include_once "conexao.php";

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST['telefone'];
    $hashsenha = trim(password_hash($_POST['senha'], PASSWORD_DEFAULT));
    $sexo = $_POST["sexo"];
    $cep = $_POST["cep"];
    $rua = $_POST["rua"];
    $numero = $_POST["numero_casa"];
    $complemento = $_POST["complemento"];
    $bairro = $_POST["bairro"];
    $cidade = $_POST["cidade"];
    $estado = $_POST["uf"];

    if (isset($_POST['aceitanews'])) {
        $aceita_news = 1;
    } else {
        $aceita_news = 0;
    }

    $sql = "SELECT * FROM cad_cliente WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if ($num >= 1) { ?>
        <script type="text/javascript">
            alert('Email já cadastrado!');
            window.location.href = "../html/login-cadastro.html";
        </script>
    <?php
    } else {
        $string = "INSERT INTO cad_cliente VALUES";
        $string .= "(NULL,'$nome','$email','$telefone','$hashsenha','$sexo','$cep','$rua','$numero','$complemento','$bairro','$cidade','$estado','$aceita_news', 0)";
        mysqli_query($conn, $string) or die("Erro ao Cadastrar");
    ?>
        <script type="text/javascript">
            alert('Cadastro Efetuado com Sucesso!');
            window.location.href = "../index.html";
        </script>
<?php
        $_SESSION['email'] = $email;
        $_SESSION['senha'] = $senha;
    }
}
?>