<?php
require_once __DIR__ . '/BaseModel.php';

class OrderProductModel extends BaseModel
{
  protected string $tableName = 'order_product';
  protected string $idColumn = 'order_id';

  public function find(int $id): array
  {
    $sql = "SELECT * FROM {$this->tableName} WHERE order_id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function insert(array $tableColumns, array $values): array
  {
    $columns = implode(', ', $tableColumns);
    $placeholders = ':' . implode(', :', $tableColumns);

    $sql = "INSERT INTO {$this->tableName} ($columns) VALUES ($placeholders)";
    $stmt = $this->pdo->prepare($sql);

    $params = array_combine($tableColumns, $values);
    $stmt->execute($params);

    return $this->find($values[array_search('order_id', $tableColumns)]);
  }
}
