<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Origin: http://localhost:8083"); // Разрешите конкретный источник
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Разрешите методы
header("Access-Control-Allow-Headers: Content-Type"); // Разрешите заголовок

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Если это preflight запрос, просто завершите его здесь
    exit(0);
}

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "vuelara";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Регистрация пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'register') {
    $data = json_decode(file_get_contents("php://input"));
    
    $username = $conn->real_escape_string($data->username);
    $password = password_hash($data->password, PASSWORD_DEFAULT); // Хешируем пароль

    // Проверка на существование пользователя
    $checkSql = "SELECT * FROM users WHERE username='$username'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        echo json_encode(["error" => "Пользователь с таким именем уже существует!"]);
    } else {
        // Добавление нового пользователя
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Вы успешно зарегистрировались!"]);
        } else {
            echo json_encode(["error" => "Error: " . $conn->error]);
        }
    }
}

// Вход пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'login') {
    $data = json_decode(file_get_contents("php://input"));

    $username = $conn->real_escape_string($data->username);
    $password = $data->password;

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Проверка пароля
        if (password_verify($password, $user['password'])) {
            echo json_encode(["message" => "Вход в систему прошел успешно!", "user" => $user, "userExists" => true]);
        } else {
            echo json_encode(["error" => "Неверные учетные данные!", "userExists" => false]);
        }
    } else {
        echo json_encode(["error" => "Пользователь не найден!", "userExists" => false]);
    }
}

// Получение всех продуктов
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getProducts') {
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $products = [];
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode($products); // Возвращаем массив продуктов в формате JSON
    } else {
        echo json_encode([]); // Возвращаем пустой массив, если продуктов нет
    }
}

// Получение всех заказов пользователя
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getOrders') {
    $userId = intval($_GET['user_id']); // Предполагается, что передается id пользователя
    $sql = "SELECT * FROM orders WHERE user_id = '$userId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $orders = [];
        while ($order = $result->fetch_assoc()) {
            // Получение элементов заказа
            $order_id = $order['id'];
            $itemsSql = "SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = '$order_id'";
            $itemsResult = $conn->query($itemsSql);

            $items = [];
            while ($item = $itemsResult->fetch_assoc()) {
                $items[] = $item;
            }

            $order['items'] = $items; // Добавляем элементы к заказу
            $orders[] = $order; // Добавляем заказ в массив
        }
        echo json_encode($orders); // Возвращаем массив заказов в формате JSON
    } else {
        echo json_encode([]); // Возвращаем пустой массив, если заказов нет
    }
}

// Сохранение нового заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'createOrder') {
    $data = json_decode(file_get_contents("php://input"));

    // Предполагается, что данные содержат user_id и items
    $userId = $conn->real_escape_string($data->user_id);
    $items = $data->items;
    
    // Сначала вставим новый заказ
    $total = 0;
    foreach ($items as $item) {
        $total += $item->price * $item->quantity;
    }

    $sql = "INSERT INTO orders (user_id, total) VALUES ('$userId', '$total')";
    
    if ($conn->query($sql) === TRUE) {
        $orderId = $conn->insert_id; // Получаем ID нового заказа
        
        // Вставляем элементы заказа
        foreach ($items as $item) {
            $productId = $conn->real_escape_string($item->product_id);
            $quantity = $conn->real_escape_string($item->quantity);
            $price = $conn->real_escape_string($item->price);
            
            $itemSql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$orderId', '$productId', '$quantity', '$price')";
            $conn->query($itemSql);
        }

        echo json_encode(["message" => "Заказ успешно создан!", "order_id" => $orderId]);
    } else {
        echo json_encode(["error" => "Ошибка создания заказа: " . $conn->error]);
    }
}

$conn->close();
?>





