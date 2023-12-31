<?php
function connectToDatabase() {
   $servername = "127.0.0.1:3306";
 $username = "u383961694_admin24";
 $password = "Bemtequiz123@";
 $database = "u383961694_bemtequiz";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Conexão com o banco de dados falhou: " . $conn->connect_error);
    }

    return $conn;
}


function closeDatabaseConnection($conn) {
    $conn->close();
}

function fetchAll($conn, $sql) {
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = array();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();

    return $data;
}

function getAllQuestions() {
    $conn = connectToDatabase();
    $sql = "SELECT id, pergunta, id_categorias FROM perguntas";
    $questions = fetchAll($conn, $sql);
    closeDatabaseConnection($conn);
    return $questions;
}

function getAnswersByQuestionId($questionId) {
    $conn = connectToDatabase();
    $sql = "SELECT id, resposta, correta FROM respostas WHERE pergunta_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $questionId);
    $stmt->execute();

    $result = $stmt->get_result();
    $answers = array();

    while ($row = $result->fetch_assoc()) {
        $answers[] = $row;
    }

    $stmt->close();
    closeDatabaseConnection($conn);

    return $answers;
}

function getQuestionById($questionId) {
    $conn = connectToDatabase();
    $sql = "SELECT id, pergunta, id_categorias FROM perguntas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $questionId);
    $stmt->execute();

    $result = $stmt->get_result();
    $question = $result->fetch_assoc();

    $stmt->close();
    closeDatabaseConnection($conn);

    return $question;
}

function getRandomQuestionIds($count) {
    $conn = connectToDatabase();

    $sql = "SELECT id FROM perguntas";
    $result = $conn->query($sql);
    $availableQuestionIds = array();

    while ($row = $result->fetch_assoc()) {
        $availableQuestionIds[] = $row['id'];
    }

    shuffle($availableQuestionIds);

    $randomQuestionIds = array_slice($availableQuestionIds, 0, $count);

    $conn->close();

    return $randomQuestionIds;
}

function getCuriosidadeByQuestionId($questionId)
{
    $conn = connectToDatabase();
    $sql = "SELECT curiosidades FROM perguntas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $questionId);
    $stmt->execute();

    $result = $stmt->get_result();
    $curiosidade = $result->fetch_assoc();

    $stmt->close();
    closeDatabaseConnection($conn);

    if ($curiosidade) {
        return $curiosidade['curiosidades'];
    } else {
        return "Nenhuma curiosidade encontrada.";
    }
}


function countCorrectAnswers($userAnswers, $questionIds) {
    $correctAnswers = 0;
    
    foreach ($questionIds as $questionId) {
        $correctAnswer = getCorrectAnswerForQuestion($questionId);
        if (isset($userAnswers[$questionId]) && $userAnswers[$questionId] == $correctAnswer) {
            $correctAnswers++;
        }
    }
    
    return $correctAnswers;
}


?>