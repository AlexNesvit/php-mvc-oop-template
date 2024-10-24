# Interview Prep PHP + Laravel

**[Description en français](README.md)**

Платформа подготовки к собеседованиям PHP + Laravel.

## Этапы разработки и объяснение

### 1. Создание структуры проекта

Мы начали с организации структуры проекта по модели MVC (Model-View-Controller). Это позволяет разделить логику приложения на три компонента:

- **Модель (Model):** Отвечает за управление данными и взаимодействие с базой данных.
- **Представление (View):** Отображает данные пользователю.
- **Контроллер (Controller):** Управляет бизнес-логикой и координирует взаимодействие между моделью и представлением.

#### Структура проекта:

![alt text](<assets/images/structure.png>)

### 2. Подключение к базе данных

Мы использовали PHP и PDO для создания безопасного подключения к базе данных MySQL. Файл конфигурации `config.php` содержит необходимую информацию для подключения к базе данных.

#### Шаги по созданию базы данных:

1. Открыть **PHPMyAdmin** через интерфейс MAMP.
2. Создать базу данных под названием `php_laravel_proprep`.
3. Создать необходимые таблицы для приложения (пользователи, вопросы, уроки и т.д.).

#### Код для подключения к базе данных:

```php
<?php
// Данные для подключения к базе данных
$db_host = 'localhost';
$db_name = 'php_laravel_proprep';
$db_user = 'root';
$db_pass = 'root';

// Подключение к базе данных
try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Ошибка подключения: ' . $e->getMessage());
}

Объяснение кода:

	•	$db_host, $db_name, $db_user, $db_pass : Эти переменные содержат информацию для подключения (хост, имя базы данных, пользователь и пароль).
	•	new PDO(…): Эта строка создает новое подключение к базе данных с использованием расширения PDO.
	•	setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION) : Включаем режим ошибок с выбросом исключений.
	•	catch(PDOException $e) : Если возникает ошибка подключения, она перехватывается и выводится сообщение об ошибке.

3. Объяснение основных компонентов

Файл index.php (точка входа)

Файл public/index.php — это точка входа приложения. Он обрабатывает запросы пользователей и направляет приложение к правильному контроллеру.

<?php
// Запуск сессии
session_start();

// Загрузка конфигураций
require_once '../config/config.php';

// Установка языка (по умолчанию французский)
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'] ?? 'fr';

// Загрузка переводов
$translations = include "../lang/{$lang}.php";

// Автозагрузчик для автоматической подгрузки классов
spl_autoload_register(function ($class) {
    require_once "../app/core/{$class}.php";
});

// Инициализация приложения
$app = new App();

Объяснение кода:

	•	session_start() : Запускает PHP-сессию для хранения информации о пользователе, такой как выбранный язык.
	•	require_once ‘../config/config.php’ : Загружает конфигурации проекта, включая подключение к базе данных.
	•	$_SESSION[‘lang’] : Эта переменная хранит выбранный пользователем язык.
	•	spl_autoload_register() : Автоматически подгружает необходимые классы для работы приложения.
	•	$app = new App() : Инициализирует приложение, вызывая класс App, который управляет маршрутизацией.

4. Управление маршрутизацией с помощью App.php

Файл App.php в папке core отвечает за маршрутизацию. Он анализирует URL и направляет пользователя к правильному контроллеру и методу.

<?php
// Основной класс приложения

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // Определяет, какой контроллер использовать
        if (file_exists("../app/controllers/{$url[0]}Controller.php")) {
            $this->controller = "{$url[0]}Controller";
            unset($url[0]);
        }

        require_once "../app/controllers/{$this->controller}.php";
        $this->controller = new $this->controller;

        // Определяет, какой метод контроллера использовать
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Параметры
        $this->params = $url ? array_values($url) : [];

        // Вызов контроллера и метода
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // Функция для разбора URL
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return ['Home'];
    }
}

<?php
// Основной класс приложения

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // Определяет, какой контроллер использовать
        if (file_exists("../app/controllers/{$url[0]}Controller.php")) {
            $this->controller = "{$url[0]}Controller";
            unset($url[0]);
        }

        require_once "../app/controllers/{$this->controller}.php";
        $this->controller = new $this->controller;

        // Определяет, какой метод контроллера использовать
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Параметры
        $this->params = $url ? array_values($url) : [];

        // Вызов контроллера и метода
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // Функция для разбора URL
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return ['Home'];
    }
}

Объяснение кода:

	•	$controller, $method, $params : Эти переменные содержат контроллер, метод и параметры, извлеченные из URL.
	•	parseUrl() : Разбирает URL для определения контроллера и метода, которые нужно использовать.
	•	call_user_func_array : Вызывает метод контроллера с переданными параметрами.

Следующие шаги

	1.	Создание моделей:
Мы создадим модели для взаимодействия с базой данных.
	2.	Разработка контроллеров:
Контроллеры будут управлять логикой приложения и взаимодействием между представлениями и моделями.
	3.	Реализация полной поддержки мультиязычности:
Платформа будет позволять переключаться между французским и русским языками через пользовательский интерфейс.

Автор

	•	Alex NESVIT — разработчик проекта.

Лицензия

Проект распространяется под лицензией MIT.

---

☕ Support Me
<p align="center">
<a href="https://www.buymeacoffee.com/alexnesvit"><img alt="Coffee" src="https://img.shields.io/badge/Buy_Me_A_Coffee-FFDD00?style=for-the-badge&logo=buy-me-a-coffee&logoColor=black" /></a></align=>
</p>
