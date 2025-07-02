<?php
namespace App\Services;

class ChangeTracker
{
    protected static array $changes = [];

    public static function add(string $type, array $data)
    {
        self::$changes[$type][] = $data;
    }

    public static function getAll(): array
    {
        return self::$changes;
    }

    public static function clear()
    {
        self::$changes = [];
    }
}
?>
