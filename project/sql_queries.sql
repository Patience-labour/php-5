CREATE TABLE shop (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  address VARCHAR(500) NOT NULL
);

CREATE TABLE client (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  phone VARCHAR(50) NOT NULL,
  birthdate DATE NOT NULL
);

CREATE TABLE product (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  count INT NOT NULL,
  shop_id INT NOT NULL,
  FOREIGN KEY (shop_id) REFERENCES shop(id)
);

CREATE TABLE `order` (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  client_id INT NOT NULL,
  shop_id INT NOT NULL,
  FOREIGN KEY (client_id) REFERENCES client(id),
  FOREIGN KEY (shop_id) REFERENCES shop(id)
);

CREATE TABLE order_product (
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  price_at_order DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (order_id, product_id),
  FOREIGN KEY (order_id) REFERENCES `order`(id),
  FOREIGN KEY (product_id) REFERENCES product(id)
);

SELECT
  c.id,
  c.name,
  c.birthdate,
  SUM(op.quantity * op.price_at_order) AS total_spent
FROM client c
JOIN `order` o ON c.id = o.client_id
JOIN order_product op ON o.id = op.order_id
WHERE DATE_FORMAT(o.created_at, '%m-%d') 
  BETWEEN DATE_FORMAT(DATE_SUB(c.birthdate, INTERVAL 3 DAY), '%m-%d')
  AND DATE_FORMAT(DATE_ADD(c.birthdate, INTERVAL 3 DAY), '%m-%d')
GROUP BY c.id, c.name, c.birthdate;

SELECT
  o.id AS order_id,
  p.name AS product_name,
  op.price_at_order AS order_price,
  p.price AS current_price,
  (op.price_at_order - p.price) AS price_difference,
  op.quantity,
  (op.price_at_order - p.price) * op.quantity AS total_difference
FROM `order` o
JOIN order_product op ON o.id = op.order_id
JOIN product p ON op.product_id = p.id
WHERE op.price_at_order != p.price;

SELECT s.id, s.name, SUM(op.quantity * op.price_at_order) AS total_revenue
FROM shop s
JOIN `order` o ON s.id = o.shop_id
JOIN order_product op ON o.id = op.order_id
GROUP BY s.id, s.name
ORDER BY total_revenue DESC
LIMIT 1;

SELECT s.id, s.name, COUNT(o.id) AS total_orders
FROM shop s
JOIN `order` o ON s.id = o.shop_id
GROUP BY s.id, s.name
ORDER BY total_orders DESC
LIMIT 1;