# smh-api

Не стал дбавлять лолгику в migration.php, если добавлять новые таблицы изменять пока вручную.
Чтобы работал api интерфейс нужно добавить в папку config файл .env и прописать туда параметры БД,
запустить скрипт migration.php
Кроме создания api-интерфеса (для проверки) от ТЗ отходить не стал
Загрузка IPhone в БД GET https://example.domain/?service=products&product=IPhone
Добавление нового товара POST https://example.domain/ BODY(json)(пример):
{
  "service":"products",
  "data": {
    "title": "iPhone 15 Pro",
    "price" : 999,
    "description": "New flagship smartphone",
    "brand": "Apple"
  }
}