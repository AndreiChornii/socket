
<?php
error_reporting(E_ALL);

echo "<h2>Соединение TCP/IP</h2>\n";

/* Получаем порт сервиса WWW. */
$service_port = getservbyname('http', 'tcp');
// $service_port = 10000;

/* Получаем IP-адрес целевого хоста. */
// $address = gethostbyname('http://10.0.1.1/');
$address = '10.0.1.1';

/* Создаём сокет TCP/IP. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "Не удалось выполнить socket_create(): причина: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "OK.\n";
}

echo "Пытаемся соединиться с '$address' на порту '$service_port'...";
$result = socket_connect($socket, $address, $service_port);
if ($result === false) {
    echo "Не удалось выполнить socket_connect().\nПричина: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}

// $in = "HEAD / HTTP/1.1\r\n";
// $in .= "Host: 10.0.1.1/info.php\r\n";
// $in .= "Connection: Close\r\n\r\n";

$in = "GET /info.php HTTP/1.1\r\n";
$in .= "Host: 10.0.1.1\r\n";
$in .= "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:121.0) Gecko/20100101 Firefox/121.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Connection: keep-alive
Upgrade-Insecure-Requests: 1
Pragma: no-cache
Cache-Control: no-cache";
$in .= "Connection: Close\r\n\r\n";

$out = '';

echo "Отправляем HTTP-запрос HEAD...";
socket_write($socket, $in, strlen($in));
echo "OK.\n";

echo "Читаем ответ:\n\n";
while ($out = socket_read($socket, 2048)) {
    echo $out;
}

echo "Закрываем сокет...";
socket_close($socket);
echo "OK.\n\n";
?>
