<?php
interface IFileIO {
  function save($data);
  function load();
}
abstract class FileIO implements IFileIO {
  protected $filepath;
  public function __construct($filename) {
    if (!is_readable($filename) || !is_writable($filename)) {
      throw new Exception("Data source $filename is invalid.");
    }
    $this->filepath = realpath($filename);
  }
}
class JsonIO extends FileIO {
  public function load() {
    $file_content = file_get_contents($this->filepath);
    $data = json_decode($file_content, true);
    return is_array($data) ? $data : [];
  }
  public function save($data) {
    file_put_contents($this->filepath, json_encode($data, JSON_PRETTY_PRINT));
  }
}

interface IStorage {
  function add($record): string;
  function findById(string $id);
  function findAll(array $params = []);
  function findOne(array $params = []);
  function update(string $id, $record);
  function delete(string $id);

  function findMany(callable $condition);
  function updateMany(callable $condition, callable $updater);
  function deleteMany(callable $condition);
}
class Storage implements IStorage {
  protected $contents;
  protected $io;
  public function __construct(IFileIO $io) {
    $this->io = $io;
    $this->contents = $this->io->load();
    if(!is_array($this->contents)) {
      $this->contents = [];
    }
  }
  public function __destruct() {
    $this->io->save($this->contents);
  }
  public function add($record): string {
    $this->contents[] = $record;
    return (string)(count($this->contents) - 1);
  }
  public function findById(string $id) {
    return isset($this->contents[$id]) ? $this->contents[$id] : null;
  }
  public function findAll(array $params = []) {
    return array_filter($this->contents, function ($item) use ($params) {
      foreach ($params as $key => $value) {
        if (!isset($item[$key]) || $item[$key] != $value) {
          return false;
        }
      }
      return true;
    });
  }
  public function findOne(array $params = []) {
    foreach ($this->contents as $record) {
      $match = true;
      foreach ($params as $key => $value) {
        if (!isset($record[$key]) || $record[$key] != $value) {
          $match = false;
          break;
        }
      }
      if ($match) return $record;
    }
    return null;
  }
  public function update(string $id, $record) {
    if (isset($this->contents[$id])) {
      $this->contents[$id] = $record;
    }
  }
  public function delete(string $id) {
    if (isset($this->contents[$id])) {
      unset($this->contents[$id]);
      $this->contents = array_values($this->contents);
    }
  }
  public function findMany(callable $condition) {
    return array_filter($this->contents, $condition);
  }
  public function updateMany(callable $condition, callable $updater) {
    foreach($this->contents as $i => $record) {
      if($condition($record)) {
        $updater($record);
        $this->contents[$i] = $record;
      }
    }
  }
  public function deleteMany(callable $condition) {
    $this->contents = array_filter($this->contents, function($record) use($condition) {
      return !$condition($record);
    });
    $this->contents = array_values($this->contents);
  }
}
