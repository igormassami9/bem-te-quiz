<?php
require_once('verificacao.php'); 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $conn = new mysqli("127.0.0.1:3306", "u383961694_admin24", "Bemtequiz123@", "u383961694_bemtequiz");

    
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
    $_SESSION["user_id"] = $row["id"]; 
    $_SESSION["username"] = $row["username"];
    header("Location: home.php");
    exit();
} else {
    echo "Senha incorreta.";
}

    } else {
        echo "Usuário não encontrado.";
    }

    $conn->close();
}
?>
