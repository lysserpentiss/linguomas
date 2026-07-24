<?php

// ЗАПУСК СЕССИИ
// session_start() — возобновляет сессию, чтобы получить данные авторизованного пользователя.
// Без этого мы не узнаем, кто залогинен.
session_start();
require_once 'db_connect.php';

// ============================================================
// ПРОВЕРКА АВТОРИЗАЦИИ
// ============================================================
// Если пользователь НЕ авторизован (нет сессии user_id) —
// перенаправляем на страницу входа.

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit; // Останавливаем выполнение скрипта
}

// Если авторизован — получаем его ID и имя из сессии
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// ============================================================
// ПОЛУЧЕНИЕ ДАННЫХ ПРОГРЕССА ИЗ БД
// ============================================================
// Подготавливаем SQL-запрос: выбрать прогресс пользователя !ПОКА НЕ ИСПОЛЬЗУЕТСЯ!
$progress_sql = "SELECT * FROM progress WHERE user_id = ?";
$progress_stmt = mysqli_prepare($link, $progress_sql);
// mysqli_prepare — подготавливает запрос к выполнению (защита от SQL-инъекций)

// Привязываем параметр user_id (i — integer)
mysqli_stmt_bind_param($progress_stmt, "i", $user_id);
mysqli_stmt_execute($progress_stmt); // Выполняем запрос
$progress_result = mysqli_stmt_get_result($progress_stmt); // Получаем результат
$progress = mysqli_fetch_assoc($progress_result); // Извлекаем строку в массив

// Если прогресса НЕТ (например, новый пользователь) — создаём запись
if (!$progress) {
    $insert_progress = "INSERT INTO progress (user_id, language, words_learned, days_streak) VALUES (?, 'es', 0, 0)";
    $insert_stmt = mysqli_prepare($link, $insert_progress);
    mysqli_stmt_bind_param($insert_stmt, "i", $user_id);
    mysqli_stmt_execute($insert_stmt);
    mysqli_stmt_close($insert_stmt);  // Закрываем запрос
    
// Повторно получаем прогресс (теперь уже есть)
    $progress_stmt = mysqli_prepare($link, $progress_sql);
    mysqli_stmt_bind_param($progress_stmt, "i", $user_id);
    mysqli_stmt_execute($progress_stmt);
    $progress_result = mysqli_stmt_get_result($progress_stmt);
    $progress = mysqli_fetch_assoc($progress_result);
}

// ============================================================
// ПОЛУЧЕНИЕ ИЗБРАННЫХ СЛОВ
// ============================================================
// JOIN: связываем таблицы favorites и words,
// чтобы получить слова, которые пользователь добавил в избранное
$fav_sql = "SELECT w.* FROM favorites f JOIN words w ON f.word_id = w.id WHERE f.user_id = ?";
$fav_stmt = mysqli_prepare($link, $fav_sql);
mysqli_stmt_bind_param($fav_stmt, "i", $user_id);
mysqli_stmt_execute($fav_stmt);
$fav_result = mysqli_stmt_get_result($fav_stmt);

// ============================================================
// ПОДСЧЁТ СЛОВ ПО ЯЗЫКАМ (ДЛЯ ДЕРЕВА ПРОГРЕССА)
// ============================================================
// Испанские слова в избранном
$es_sql = "SELECT COUNT(*) as count FROM favorites f JOIN words w ON f.word_id = w.id WHERE f.user_id = ? AND w.language = 'es'";
$es_stmt = mysqli_prepare($link, $es_sql);
mysqli_stmt_bind_param($es_stmt, "i", $user_id);
mysqli_stmt_execute($es_stmt);
$es_result = mysqli_stmt_get_result($es_stmt);
$es_row = mysqli_fetch_assoc($es_result);
$es_words = $es_row['count'] ?? 0;
mysqli_stmt_close($es_stmt);

// Португальские слова в избранном
$pt_sql = "SELECT COUNT(*) as count FROM favorites f JOIN words w ON f.word_id = w.id WHERE f.user_id = ? AND w.language = 'pt'";
$pt_stmt = mysqli_prepare($link, $pt_sql);
mysqli_stmt_bind_param($pt_stmt, "i", $user_id);
mysqli_stmt_execute($pt_stmt);
$pt_result = mysqli_stmt_get_result($pt_stmt);
$pt_row = mysqli_fetch_assoc($pt_result);
$pt_words = $pt_row['count'] ?? 0;
mysqli_stmt_close($pt_stmt);
?>
<html>
<head>
<title>linguomas - profile</title>
<link rel="stylesheet" type="text/css" href="style.css"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- чтобы на телефоне красиво было -->
<link rel="preconnect" href="https://fonts.googleapis.com"> <!-- шрифты -->
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,700&family=Nunito:wght@400;600;800&display=swap" rel="stylesheet">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <style>
	/* ---------- ШАПКА ПРОФИЛЯ ---------- */
        .profile-header {
            background: white;
            padding: 30px 0;
            border-bottom: 1px solid #eee;
        }
        .profile-header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .profile-greeting h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #2D2D2D;
        }
        .profile-greeting span {
            color: #B22222; /* Красное имя пользователя */
        }
		/* ---------- СТАТИСТИКА ---------- */
        .profile-stats {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            align-items: center;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: #B22222;
        }
        .stat-label {
            font-size: 14px;
            color: #888;
        }
        .profile-section {
            padding: 40px 0;
        }
		 /* ---------- СЕКЦИИ ПРОФИЛЯ ---------- */
        .profile-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            margin-bottom: 20px;
        }
		 /* ---------- КНОПКА ВЫХОДА ---------- */
        .logout-btn {
            padding: 10px 25px;
            background: #B22222;
            color: white;
            border: none;
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        .logout-btn:hover {
            background: #8B1A1A;
            transform: translateY(-2px);
        }

        /* КОЛЕСО ФОРТУНЫ */
        .wheel-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            padding: 30px;
            background: #FFF8F0;
            border-radius: 24px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.04);
        }
        .wheel-wrapper {
            position: relative;
            width: 250px;
            height: 250px;
        }
        .wheel {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: conic-gradient(#B22222 0% 20%, #F1C40F 20% 40%, #006437 40% 60%, #2980B9 60% 80%, #8E44AD 80% 100%);
            transition: transform 4s cubic-bezier(0.17, 0.67, 0.12, 0.99);
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            position: relative;
        }
        .wheel-pointer {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 40px;
            filter: drop-shadow(0 4px 10px rgba(0,0,0,0.2));
            z-index: 10;
        }
        .wheel-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 12px;
            color: #2D2D2D;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            line-height: 1.2;
        }
        .wheel-spin-btn {
            padding: 14px 40px;
            background: #B22222;
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .wheel-spin-btn:hover {
            background: #8B1A1A;
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(178, 34, 34, 0.3);
        }
        .wheel-spin-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        .wheel-task {
            font-size: 18px;
            padding: 15px 25px;
            background: white;
            border-radius: 16px;
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* ДЕРЕВО ПРОГРЕССА */
        .tree-container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        .tree-card {
            flex: 1;
            min-width: 200px;
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            text-align: center;
        }
        .tree-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            margin-bottom: 15px;
        }
        .tree-card .progress-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 800;
            color: white;
        }
        .tree-card .progress-circle.es {
            background: #B22222;
        }
        .tree-card .progress-circle.pt {
            background: #006437;
        }
        .tree-card .progress-days {
            font-size: 14px;
            color: #888;
        }
        .tree-card .progress-bar-bg {
            width: 100%;
            height: 8px;
            background: #eee;
            border-radius: 10px;
            margin-top: 10px;
            overflow: hidden;
        }
        .tree-card .progress-bar-fill {
            height: 100%;
            border-radius: 10px;
            width: 0%;
            transition: width 1s ease;
        }
        .tree-card .progress-bar-fill.es {
            background: #B22222;
        }
        .tree-card .progress-bar-fill.pt {
            background: #006437;
        }

        /* ИЗБРАННЫЕ СЛОВА */
        .fav-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .fav-item {
            background: white;
            padding: 15px 20px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
            border-left: 4px solid #B22222;
        }
        .fav-item.pt-fav {
            border-left-color: #006437;
        }
        .fav-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
        }
        .fav-word-info {
            display: flex;
            flex-direction: column;
        }
        .fav-word {
            font-weight: 700;
            font-size: 18px;
        }
        .fav-translation {
            font-size: 14px;
            color: #888;
        }
        .fav-remove {
            background: none;
            border: none;
            color: #B22222;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s;
            padding: 5px 8px;
            border-radius: 8px;
        }
        .fav-remove:hover {
            background: #FFF0E0;
            color: #8B1A1A;
            transform: scale(1.2);
        }
        .fav-empty {
            text-align: center;
            color: #888;
            padding: 40px 0;
            font-size: 16px;
            grid-column: 1 / -1;
        }
        .fav-empty a {
            color: #B22222;
            text-decoration: none;
            font-weight: 700;
        }
        .fav-empty a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .profile-header .container {
                flex-direction: column;
                text-align: center;
            }
            .profile-stats {
                justify-content: center;
            }
            .wheel-wrapper {
                width: 200px;
                height: 200px;
            }
            .wheel-center {
                width: 50px;
                height: 50px;
                font-size: 10px;
            }
        }
/* ===== ToDoList ===== */
.todo-container { max-width: 700px; margin: 0 auto; background: white; padding: 25px; border-radius: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); }
.todo-form { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; }
.todo-input { flex: 1; padding: 12px 18px; border: 2px solid #e0e0e0; border-radius: 14px; font-size: 16px; min-width: 150px; }
.todo-input:focus { border-color: #B22222; outline: none; }
.todo-date { padding: 12px 18px; border: 2px solid #e0e0e0; border-radius: 14px; font-size: 16px; }
.todo-priority { padding: 12px 18px; border: 2px solid #e0e0e0; border-radius: 14px; font-size: 16px; background: white; }
.todo-add-btn { padding: 12px 30px; background: #B22222; color: white; border: none; border-radius: 14px; font-weight: 700; cursor: pointer; transition: all 0.3s; }
.todo-add-btn:hover { background: #8B1A1A; transform: translateY(-2px); }

.todo-filters { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }
.todo-filter { padding: 6px 16px; border: 2px solid #e0e0e0; border-radius: 30px; background: white; font-weight: 600; cursor: pointer; transition: all 0.3s; color: #555; }
.todo-filter:hover { border-color: #B22222; color: #B22222; }
.todo-filter.active { border-color: #B22222; background: #B22222; color: white; }

.todo-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px; max-height: 400px; overflow-y: auto; padding-right: 5px; }
.todo-list::-webkit-scrollbar { width: 6px; }
.todo-list::-webkit-scrollbar-track { background: #f0f0f0; border-radius: 10px; }
.todo-list::-webkit-scrollbar-thumb { background: #B22222; border-radius: 10px; }

.todo-item { display: flex; align-items: center; gap: 12px; padding: 14px 18px; background: #FDF8F0; border-radius: 14px; transition: all 0.3s; border-left: 4px solid #B22222; }
.todo-item:hover { transform: translateX(4px); box-shadow: 0 4px 15px rgba(0,0,0,0.04); }
.todo-item .todo-checkbox { width: 20px; height: 20px; cursor: pointer; accent-color: #006437; }
.todo-item .todo-text { flex: 1; font-weight: 600; color: #333; }
.todo-item .todo-text.done { text-decoration: line-through; color: #aaa; }
.todo-item .todo-date-label { font-size: 13px; color: #888; margin-right: 10px; }
.todo-item .todo-priority-label { font-size: 14px; font-weight: 700; margin-right: 10px; }
.todo-item .todo-delete-btn { background: none; border: none; color: #B22222; font-size: 18px; cursor: pointer; transition: all 0.3s; }
.todo-item .todo-delete-btn:hover { transform: scale(1.2); color: #8B1A1A; }

.todo-item.priority-high { border-left-color: #B22222; }
.todo-item.priority-medium { border-left-color: #F1C40F; }
.todo-item.priority-low { border-left-color: #2ECC71; }

.todo-delete-selected { padding: 10px 25px; background: #B22222; color: white; border: none; border-radius: 30px; font-weight: 700; cursor: pointer; transition: all 0.3s; }
.todo-delete-selected:hover { background: #8B1A1A; transform: translateY(-2px); }
.todo-delete-selected:disabled { opacity: 0.5; cursor: not-allowed; }

.todo-empty { text-align: center; color: #aaa; padding: 30px 0; font-size: 16px; }
.todo-empty span { font-size: 40px; display: block; margin-bottom: 10px; }		
/* ===== ВКЛАДКИ ПРОФИЛЯ ===== */
.profile-tabs {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin: 20px 0 30px;
    flex-wrap: wrap;
}

.profile-tab {
    padding: 10px 28px;
    border: 2px solid #e0e0e0;
    border-radius: 50px;
    background: white;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
    color: #555;
}

.profile-tab:hover {
    border-color: #B22222;
    color: #B22222;
}

.profile-tab.active {
    border-color: #B22222;
    background: #B22222;
    color: white;
}

/* ===== КОНТЕНТ ВКЛАДОК ===== */
.tab-content {
    display: none;
    animation: fadeIn 0.5s ease;
}

.tab-content.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
/* ===== ToDoList ===== */
.todo-item .todo-select {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #B22222;
    flex-shrink: 0;
}

.todo-item .todo-text {
    flex: 1;
    font-weight: 600;
    color: #333;
    cursor: pointer; /* ← чтобы было понятно, что кликабельно */
    transition: all 0.3s;
    padding: 2px 6px;
    border-radius: 6px;
}

.todo-item .todo-text:hover {
    background: #FFF0E0;
}

.todo-item .todo-text.done {
    text-decoration: line-through;
    color: #aaa;
    cursor: default;
}

.todo-item .todo-text.done:hover {
    background: transparent;
}
		
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">🌎 LINGUOMAS</div>
            <nav>
                <ul>
                    <li><a href="index.html">Главная</a></li>
                    <li><a href="spanish.html">Испанский</a></li>
                    <li><a href="portugues.html">Португальский</a></li>
                    <li><a href="compare.html">Сравнить</a></li>
                    <li><a href="practice.php">Практика</a></li>
					<li><a href="practice_advanced.php">Продвинутая практика</a></li>
                    <li><a href="downloads.html">Шпаргалки</a></li>
                    <li><a href="profile.php" class="active">Профиль</a></li>
                    <li><a href="feedback.php">Обратная связь</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <!-- ===== ШАПКА ПРОФИЛЯ ===== -->
        <div class="profile-header">
            <div class="container">
                <div class="profile-greeting">
                    <h1>👋 Привет, <span><?php echo htmlspecialchars($username); ?></span>!</h1>
                    <p style="color:#888;">Добро пожаловать в твой личный кабинет</p>
                </div>
                <div class="profile-stats">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $progress['words_learned'] ?? 0; ?></div>
                        <div class="stat-label">всего слов</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $progress['days_streak'] ?? 0; ?></div>
                        <div class="stat-label">дней подряд</div>
                    </div>
                    <div class="stat-item">
                        <form action="logout.php" method="POST" style="margin:0;">
                            <button type="submit" class="logout-btn">🚪 Выйти</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<div class="profile-tabs">
    <button class="profile-tab active" data-tab="goals">🎯 Мои цели</button>
    <button class="profile-tab" data-tab="progress">📊 Прогресс</button>
    <button class="profile-tab" data-tab="favorites">❤️ Избранное</button>
</div>

		
<!-- ===== ВКЛАДКА 1: МОИ ЦЕЛИ ===== -->		
<div class="tab-content active" id="tab-goals">
    <div class="todo-container">
        <h3 style="font-family:'Playfair Display',serif; font-size:24px; margin-bottom:15px;">🎯 Мои цели</h3>
        <p style="color:#888; margin-bottom:20px;">Добавляй задачи и дедлайны, чтобы не забывать о важном!</p>

        <!-- Форма добавления -->
        <div class="todo-form">
            <input type="text" id="todoInput" placeholder="Что нужно сделать?" class="todo-input">
            <input type="date" id="todoDate" class="todo-date">
            <select id="todoPriority" class="todo-priority">
                <option value="high">🔴 Высокий</option>
                <option value="medium" selected>🟡 Средний</option>
                <option value="low">🟢 Низкий</option>
            </select>
            <button class="todo-add-btn" onclick="addTodo()">➕ Добавить</button>
        </div>

        <!-- Фильтры -->
        <div class="todo-filters">
            <button class="todo-filter active" data-filter="all">Все</button>
            <button class="todo-filter" data-filter="high">🔴 Высокий</button>
            <button class="todo-filter" data-filter="medium">🟡 Средний</button>
            <button class="todo-filter" data-filter="low">🟢 Низкий</button>
            <button class="todo-filter" data-filter="done">✅ Выполненные</button>
        </div>

        <!-- Список задач -->
        <div class="todo-list" id="todoList"></div>

        <!-- Кнопка удаления выделенных -->
        <button class="todo-delete-selected" onclick="deleteSelectedTodos()">🗑️ Удалить выделенные</button>
    </div>
</div>

<!-- ===== ВКЛАДКА 2: ПРОГРЕСС ===== -->
<div class="tab-content" id="tab-progress">
<!-- ===== КОЛЕСО ФОРТУНЫ ===== -->
        <section class="profile-section">
            <div class="container">
                <h2>🎡 Колесо фортуны</h2>
                <p style="color:#888; margin-bottom:25px;">Крути и получи задание на сегодня!</p>

                <div class="wheel-container">
                    <div class="wheel-wrapper">
                        <div class="wheel-pointer">▼</div>
                        <div class="wheel" id="wheel" style="transform: rotate(0deg);"></div>
                        <div class="wheel-center">КРУТИ</div>
                    </div>
                    <button class="wheel-spin-btn" id="spinBtn">🎡 Крутить!</button>
                    <div class="wheel-task" id="wheelTask">
                        Нажми «Крутить!», чтобы получить задание
                    </div>
                </div>
            </div>
        </section>
		
<!-- ===== ДЕРЕВО ПРОГРЕССА ===== -->
        <section class="profile-section" style="background:#FDF8F0;">
            <div class="container">
                <h2>🌳 Твой прогресс</h2>
                <p style="color:#888; margin-bottom:25px;">Сколько слов уже было добавлено в избранное</p>

                <div class="tree-container">
                    <div class="tree-card">
                        <h3>🇪🇸 Испанский</h3>
                        <div class="progress-circle es"><?php echo $es_words; ?></div>
                        <div class="progress-days">слов в избранном</div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill es" style="width: <?php echo min($es_words * 10, 100); ?>%;"></div>
                        </div>
                    </div>

                    <div class="tree-card">
                        <h3>🇧🇷 Португальский</h3>
                        <div class="progress-circle pt"><?php echo $pt_words; ?></div>
                        <div class="progress-days">слов в избранном</div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill pt" style="width: <?php echo min($pt_words * 10, 100); ?>%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

</div>
<!-- ===== ВКЛАДКА 3: ИЗБРАННОЕ ===== -->
<div class="tab-content" id="tab-favorites">

<!-- ===== ИЗБРАННЫЕ СЛОВА (С ПРАВИЛЬНЫМИ КНОПКАМИ) ===== -->
        <section class="profile-section">
            <div class="container">
                <h2>❤️ Избранные слова</h2>
                <p style="color:#888; margin-bottom:25px;">Слова, которые уже добавлены в избранное на страницах словаря</p>

                <div class="fav-list" id="favList">
                    <?php if (mysqli_num_rows($fav_result) > 0): ?>
                        <?php while ($fav = mysqli_fetch_assoc($fav_result)): ?>
                            <div class="fav-item <?php echo ($fav['language'] == 'pt') ? 'pt-fav' : ''; ?>" data-word-id="<?php echo $fav['id']; ?>">
                                <div class="fav-word-info">
                                    <span class="fav-word"><?php echo htmlspecialchars($fav['foreign_word']); ?></span>
                                    <span class="fav-translation"><?php echo htmlspecialchars($fav['translation']); ?></span>
                                </div>
                                <button class="fav-remove" data-word-id="<?php echo $fav['id']; ?>" title="Удалить из избранного">✕</button>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="fav-empty">
                            😢 У тебя пока нет избранных слов<br>
                            <a href="spanish.html">Добавь в испанском</a> или <a href="portugues.html">в португальском</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
</div>
    </main>

<!-- ========== КНОПКА "НАВЕРХ" ========== -->
<button id="backToTop" class="back-to-top">⬆ Наверх</button> 

    <footer class="footer">
        <div class="container">
            <p>LinguoMas, 2026. Сделано с ❤️ для изучения языков</p>
        </div>
    </footer>

    <script src="script.js"></script>
	<script>
// ============================================================
// ToDoList (с localStorage)
// ============================================================
let todos = [];

function loadTodos() {
    const stored = localStorage.getItem('linguomas_todos');
    todos = stored ? JSON.parse(stored) : [];
    renderTodos();
}

function saveTodos() {
    localStorage.setItem('linguomas_todos', JSON.stringify(todos));
}

function addTodo() {
    const input = document.getElementById('todoInput');
    const dateInput = document.getElementById('todoDate');
    const priorityInput = document.getElementById('todoPriority');
    const text = input.value.trim();
    const date = dateInput.value;
    const priority = priorityInput.value;

    if (!text) {
        alert('Напиши задачу!');
        return;
    }

    todos.push({
        id: Date.now(),
        text: text,
        date: date,
        priority: priority,
        done: false,
        selected: false // <- новый флаг для выделения
    });

    saveTodos();
    renderTodos();

    input.value = '';
    dateInput.value = '';
}

function toggleTodo(id) {
    const todo = todos.find(t => t.id === id);
    if (todo) {
        todo.done = !todo.done;
        saveTodos();
        renderTodos();
    }
}

function toggleSelectTodo(id) {
    const todo = todos.find(t => t.id === id);
    if (todo) {
        todo.selected = !todo.selected;
        renderTodos();
    }
}

function deleteSelectedTodos() {
    const selected = todos.filter(t => t.selected);
    if (selected.length === 0) {
        alert('Выбери задачи для удаления (отметь их чекбоксами)!');
        return;
    }
    if (!confirm(`Удалить ${selected.length} выделенных задач?`)) return;

    const ids = selected.map(t => t.id);
    todos = todos.filter(t => !ids.includes(t.id));
    saveTodos();
    renderTodos();
}

function filterTodos(filter) {
    document.querySelectorAll('.todo-filter').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`.todo-filter[data-filter="${filter}"]`)?.classList.add('active');
    renderTodos(filter);
}

function renderTodos(filter = 'all') {
    const container = document.getElementById('todoList');
    let filtered = [...todos];

    // Сортировка по дате
    filtered.sort((a, b) => (a.date || '9999-12-31') > (b.date || '9999-12-31') ? 1 : -1);

    // Фильтрация
    if (filter === 'done') {
        filtered = filtered.filter(t => t.done);
    } else if (filter !== 'all') {
        filtered = filtered.filter(t => t.priority === filter);
    }

    if (filtered.length === 0) {
        container.innerHTML = `<div class="todo-empty"><span>🎯</span>Пока нет задач. Добавь свою первую цель!</div>`;
        return;
    }

    container.innerHTML = filtered.map(todo => `
        <div class="todo-item priority-${todo.priority}">
            <!-- ГАЛОЧКА ДЛЯ ВЫПОЛНЕНИЯ (слева) -->
            <input type="checkbox" class="todo-done-checkbox" data-id="${todo.id}" ${todo.done ? 'checked' : ''} onchange="toggleTodo(${todo.id})">
            
            <!-- ТЕКСТ ЗАДАЧИ -->
            <span class="todo-text ${todo.done ? 'done' : ''}">${todo.text}</span>
            
            <!-- ДЕДЛАЙН -->
            ${todo.date ? `<span class="todo-date-label">📅 ${todo.date}</span>` : ''}
            
            <!-- ПРИОРИТЕТ -->
            <span class="todo-priority-label">${todo.priority === 'high' ? '🔴' : todo.priority === 'medium' ? '🟡' : '🟢'}</span>
            
            <!-- ЧЕКБОКС ДЛЯ ВЫДЕЛЕНИЯ (рядом с крестиком) -->
            <input type="checkbox" class="todo-select-checkbox" data-id="${todo.id}">
            
            <!-- КНОПКА УДАЛЕНИЯ ОДНОЙ ЗАДАЧИ -->
            <button class="todo-delete-btn" onclick="deleteTodo(${todo.id})">✕</button>
        </div>
    `).join('');
}

function deleteTodo(id) {
    if (!confirm('Удалить эту задачу?')) return;
    todos = todos.filter(t => t.id !== id);
    saveTodos();
    renderTodos();
}

// Навешиваем события на фильтры
document.querySelectorAll('.todo-filter').forEach(btn => {
    btn.addEventListener('click', function() {
        filterTodos(this.dataset.filter);
    });
});

// Загружаем задачи при загрузке страницы
loadTodos();
// ============================================================
// ПЕРЕКЛЮЧЕНИЕ ВКЛАДОК В ПРОФИЛЕ
// ============================================================
document.querySelectorAll('.profile-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        // Убираем активный класс у всех вкладок
        document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');

        // Скрываем все вкладки
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

        // Показываем нужную
        const target = document.getElementById('tab-' + this.dataset.tab);
        if (target) target.classList.add('active');
    });
});
	</script>
</body>
</html>