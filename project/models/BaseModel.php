<?php
require_once __DIR__ . '/../interfaces/Database.php';

abstract class BaseModel implements DatabaseWrapper
{
  protected string $tableName;
  protected string $idColumn = 'id';
  protected PDO $pdo;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function insert(array $tableColumns, array $values): array
  {
    $columns = implode(', ', $tableColumns);
    $placeholders = ':' . implode(', :', $tableColumns);

    $sql = "INSERT INTO {$this->tableName} ($columns) VALUES ($placeholders)";
    $stmt = $this->pdo->prepare($sql);

    $params = array_combine($tableColumns, $values);
    $stmt->execute($params);

    $lastId = (int)$this->pdo->lastInsertId();
    return $this->find($lastId);
  }

  public function update(int $id, array $values): array
  {
    $setParts = [];
    foreach ($values as $column => $value) {
      $setParts[] = "$column = :$column";
    }
    $setClause = implode(', ', $setParts);

    $sql = "UPDATE {$this->tableName} SET $setClause WHERE {$this->idColumn} = :id";
    $stmt = $this->pdo->prepare($sql);

    $values['id'] = $id;
    $stmt->execute($values);

    return $this->find($id);
  }

  public function find(int $id): array
  {
    $sql = "SELECT * FROM {$this->tableName} WHERE {$this->idColumn} = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: [];
  }

  public function delete(int $id): bool
  {
    $sql = "DELETE FROM {$this->tableName} WHERE {$this->idColumn} = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute(['id' => $id]);
  }
}
