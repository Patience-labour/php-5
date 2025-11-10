<?php

require_once 'config/Database.php';
require_once 'interfaces/Database.php';
require_once 'models/BaseModel.php';
require_once 'models/ShopModel.php';
require_once 'models/ClientModel.php';
require_once 'models/ProductModel.php';
require_once 'models/OrderModel.php';
require_once 'models/OrderProductModel.php';

class Demo
{
  private PDO $pdo;

  public function __construct()
  {
    $this->pdo = Database::getConnection();
  }

  public function run(): void
  {
    echo "=== ДЕМОНСТРАЦИЯ РАБОТЫ С МОДЕЛЯМИ ===\n\n";

    $shopModel = new ShopModel($this->pdo);
    $clientModel = new ClientModel($this->pdo);
    $productModel = new ProductModel($this->pdo);
    $orderModel = new OrderModel($this->pdo);
    $orderProductModel = new OrderProductModel($this->pdo);

    echo "1. ДОБАВЛЕНИЕ НОВОГО МАГАЗИНА:\n";
    $newShop = $shopModel->insert(
      ['name', 'address'],
      ['Компьютерный мир', 'ул. Техническая, 15']
    );
    print_r($newShop);
    echo "\n";

    echo "2. ДОБАВЛЕНИЕ НОВОГО КЛИЕНТА:\n";
    $newClient = $clientModel->insert(
      ['name', 'phone', 'birthdate'],
      ['Васильев Игорь', '+7-900-999-88-77', '1992-09-25']
    );
    print_r($newClient);
    echo "\n";

    echo "3. ПОИСК КЛИЕНТА ПО ID:\n";
    $foundClient = $clientModel->find(1);
    print_r($foundClient);
    echo "\n";

    echo "4. ОБНОВЛЕНИЕ ТЕЛЕФОНА КЛИЕНТА:\n";
    $updatedClient = $clientModel->update(1, ['phone' => '+7-900-111-22-33']);
    print_r($updatedClient);
    echo "\n";

    echo "5. ДОБАВЛЕНИЕ НОВОГО ПРОДУКТА:\n";
    $newProduct = $productModel->insert(
      ['name', 'price', 'count', 'shop_id'],
      ['Мышь компьютерная', 2500.00, 50, 1]
    );
    print_r($newProduct);
    echo "\n";

    echo "6. ОБНОВЛЕНИЕ ЦЕНЫ ПРОДУКТА:\n";
    $updatedProduct = $productModel->update(1, ['price' => 52000.00]);
    print_r($updatedProduct);
    echo "\n";

    echo "7. УДАЛЕНИЕ ПРОДУКТА:\n";
    $deleteResult = $productModel->delete(3);
    echo "Результат удаления: " . ($deleteResult ? 'УСПЕХ' : 'ОШИБКА') . "\n";
    echo "\n";

    echo "8. ПОИСК УДАЛЕННОГО ПРОДУКТА:\n";
    $deletedProduct = $productModel->find(3);
    print_r($deletedProduct);
    echo "\n";

    echo "=== ДЕМОНСТРАЦИЯ ЗАВЕРШЕНА ===\n";
  }
}

try {
  $demo = new Demo();
  $demo->run();
} catch (Exception $e) {
  echo "Ошибка: " . $e->getMessage() . "\n";
}
