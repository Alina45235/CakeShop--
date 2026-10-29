CREATE DATABASE IF NOT EXISTS cakeshop
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cakeshop;

DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(30),
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    weight INT,
    image VARCHAR(500),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(100),
    phone VARCHAR(30),
    address TEXT,
    comment TEXT,
    delivery_date DATE,
    total DECIMAL(10,2),
    status ENUM('new','confirmed','preparing','delivering','done','cancelled')
           DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    qty INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

INSERT INTO categories (name, slug) VALUES
('Классические', 'classic'),
('Шоколадные', 'chocolate'),
('Фруктовые', 'fruit'),
('Детские', 'kids'),
('Свадебные', 'wedding'),
('Веганские', 'vegan'),
('Бенто', 'bento'),
('Чизкейки', 'cheesecake');

INSERT INTO products (category_id, name, description, price, weight, image) VALUES
-- Классические
(1, 'Медовик', 'Классический медовик со сметанным кремом', 1800, 1200, '1.jpeg'),
(1, 'Наполеон', 'Хрустящие коржи и заварной крем', 2000, 1100, '2.jpeg'),
(1, 'Птичье молоко', 'Нежный суфлейный торт в шоколадной глазури', 2200, 1300, '3.jpeg'),
(1, 'Сметанник', 'Тонкие коржи и сметанный крем', 1700, 1200, '4.jpeg'),
(1, 'Прага', 'Шоколадные коржи с кремом пломбир', 2100, 1150, '5.jpeg'),
(1, 'Киевский', 'Ореховый торт с кремом и безе', 2300, 1300, '6.jpeg'),

-- Шоколадные
(2, 'Три шоколада', 'Белый, молочный и горький шоколад', 2500, 1400, '7.jpeg'),
(2, 'Брауни', 'Плотный шоколадный торт с орехами', 2300, 1200, '8.jpeg'),
(2, 'Шоколадный фондан', 'С жидким шоколадным центром', 2600, 1300, '9.jpeg'),
(2, 'Орео', 'Шоколадные коржи и крем с печеньем', 2400, 1300, '10.jpeg'),
(2, 'Нутелла', 'С кремом из Nutella и фундуком', 2700, 1400, '11.jpeg'),
(2, 'Чёрный лес', 'Шоколад, вишня, взбитые сливки', 2600, 1400, '12.jpeg'),

-- Фруктовые
(3, 'Клубничный', 'Свежая клубника и сливочный крем', 2400, 1300, '13.jpeg'),
(3, 'Малиновый', 'Малина и крем-чиз', 2500, 1300, '14.jpeg'),
(3, 'Лимонный', 'Лимонный курд и меренга', 2300, 1200, '15.jpeg'),
(3, 'Манговый', 'Манго и кокосовый крем', 2600, 1300, '16.jpeg'),
(3, 'Черничный', 'Черника и ванильный крем', 2500, 1300, '17.jpeg'),
(3, 'Персиковый', 'Персики и сливки', 2300, 1200, '18.jpeg'),
(3, 'Апельсиновый', 'Цитрус и шоколад', 2400, 1250, '19.jpeg'),
(3, 'Банановый', 'Бананы и карамель', 2200, 1200, '20.jpeg'),

-- Детские
(4, 'Радужный', 'Разноцветные коржи, крем-чиз', 2500, 1400, '1.jpeg'),
(4, 'Смешарики', 'Детский торт с фигурками', 2800, 1500, '2.jpeg'),
(4, 'Машинки', 'Торт с машинками и декором', 2700, 1400, '3.jpeg'),
(4, 'Принцесса', 'Розовый торт с короной', 2900, 1500, '4.jpeg'),
(4, 'Динозавры', 'Зелёный торт с фигурками динозавров', 2800, 1500, '5.jpeg'),
(4, 'Единорог', 'Торт с рогом и гривой', 3000, 1500, '6.jpeg'),
(4, 'Человек-паук', 'Красный торт с паутиной', 2800, 1400, '7.jpeg'),

-- Свадебные
(5, 'Свадебный двухъярусный', 'Белый крем, живые цветы', 5500, 3000, '8.jpeg'),
(5, 'Свадебный трёхъярусный', 'Классика с декором', 7500, 4000, '9.jpeg'),
(5, 'Свадебный с золотом', 'Белый крем и золотые акценты', 6500, 3500, '10.jpeg'),
(5, 'Свадебный минимализм', 'Однотонный с одной веткой', 5800, 3000, '11.jpeg'),

-- Веганские
(6, 'Веган-шоколад', 'Без яиц и молока, кокосовый крем', 2200, 1200, '12.jpeg'),
(6, 'Веган-клубника', 'Кокосовый крем и свежая клубника', 2400, 1300, '13.jpeg'),
(6, 'Веган-морковный', 'Морковный торт с орехами', 2300, 1200, '14.jpeg'),
(6, 'Веган-лимонный', 'Лимонный крем на кокосовом молоке', 2200, 1200, '15.jpeg'),
(6, 'Веган-банан', 'Банановый хлеб-торт', 2100, 1100, '16.jpeg'),

-- Бенто
(7, 'Бенто классический', 'Мини-торт на 1-2 человек', 900, 400, '17.jpeg'),
(7, 'Бенто шоколадный', 'Маленький, но вкусный', 950, 400, '18.jpeg'),
(7, 'Бенто клубничный', 'С клубникой и кремом', 1000, 400, '19.jpeg'),
(7, 'Бенто с надписью', 'Мини-торт с пожеланием', 1100, 400, '20.jpeg'),
(7, 'Бенто с сердечком', 'Для признания в любви', 1050, 400, '1.jpeg'),
(7, 'Бенто к дню рождения', 'Маленький праздник', 1100, 400, '2.jpeg'),

-- Чизкейки
(8, 'Чизкейк классический', 'Нежный сливочный сыр', 2200, 1200, '3.jpeg'),
(8, 'Чизкейк шоколадный', 'С шоколадной крошкой', 2400, 1200, '4.jpeg'),
(8, 'Чизкейк клубничный', 'Со свежей клубникой', 2500, 1250, '5.jpeg'),
(8, 'Чизкейк малиновый', 'Малиновый топпинг', 2500, 1250, '6.jpeg'),
(8, 'Чизкейк карамельный', 'Солёная карамель', 2600, 1250, '7.jpeg'),
(8, 'Чизкейк фисташковый', 'С фисташковой пастой', 2700, 1250, '8.jpeg');