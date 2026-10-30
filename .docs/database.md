# Схема БД

> Этот файл — детальное описание всех таблиц.
> Агент читает его при планировании тасков связанных с БД.
> Синхронизируй с database/install.php.

---

## Таблицы

### `users`

| Колонка | Тип | Назначение |
|---------|-----|------------|
| id | INT PK AUTO_INCREMENT | |
| name | VARCHAR(100) NOT NULL | |
| email | VARCHAR(150) NOT NULL UNIQUE | |
| password_hash | VARCHAR(255) NOT NULL | |
| created_at | TIMESTAMP DEFAULT NOW | |

---

## Карта связей

```
users (1)
  └──< < таблица > (1:N)
```

---

## Правила

- Все `user_id` FK → `ON DELETE CASCADE`
- Все таблицы → `ENGINE=InnoDB`, `utf8mb4_unicode_ci`
- Пароли никогда в открытом виде
