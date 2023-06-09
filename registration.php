<?php

require_once('init.php');
require_once('helpers.php');
require_once('functions.php');

$layout = include_template('register.php', ['title' => 'Регистрация']);

/*Получаем данные из формы регистрации*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $new_user['login'] = htmlspecialchars($_POST['name']);
    $new_user['email'] = $_POST['email'];
    $password = $_POST['password'];

    $errors =[];

    /*Валидируем поле 'email'*/
    if ($new_user['email'] == '') {
        $errors['email'] = 'Введите e-mail';
    } elseif (filter_var($new_user['email'], FILTER_VALIDATE_EMAIL) == false) {
        $errors['email'] = 'E-mail введён некорректно';
    } elseif (email_exists($new_user['email'])) {
        $errors['email'] = 'E-mail принадлежит другому пользователю';
    }
    /*Валидируем поле 'password'*/
    if ($password == '') {
        $errors['password'] = 'Введите пароль';
    } else {
        $new_user['password'] = password_hash($password, PASSWORD_DEFAULT);
    }
    /*Валидируем поле 'login'*/
    if ($new_user['login'] == '') {
        $errors['name'] = 'Введите имя';
    } elseif (iconv_strlen($new_user['login']) > 32) {
        $errors['name'] = 'Длинна поля превышает максимально допустимую (32 символа)';
    }
    $errors = array_filter($errors);

    if (count($errors)) {
        $layout = include_template('register.php', ['errors' => $errors, 'title' => 'Регистрация']);
    } else {
        $sql = "INSERT INTO users (login, email, password, registration_date) VALUES (?, ?, ?, NOW())";

        $stmt = db_get_prepare_stmt($con, $sql, $new_user);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            header("Location: index.php");
        } else {
            $page_content = include_template('error.php', ['error' => mysqli_error($con)]);
            $layout = include_template('layout.php', [
                'page_content' => $page_content,
                'title' => 'Ошибка',
                'user' => $user
            ]);
        }
    }
}
print($layout);
