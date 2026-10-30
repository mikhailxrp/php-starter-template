<?php

declare(strict_types=1);

/**
 * Установка БД — создание таблиц.
 * Запускать один раз через браузер: /install-temp.php
 * После выполнения — удалить install-temp.php из public/
 *
 * Добавляй свои таблицы ниже по образцу.
 */

require_once dirname(__DIR__) . '/config/config.php';

$pdo = getPdo();

// ─── Таблицы ────────────────────────────────────────────────────────────────

$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        name          VARCHAR(100) NOT NULL,
        email         VARCHAR(150) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
");

// Добавляй свои таблицы здесь:
// $pdo->exec("CREATE TABLE IF NOT EXISTS ...");

echo "✅ Таблицы созданы успешно.\n";
