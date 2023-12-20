<?php
spl_autoload_register(static function (string $className): void {
    $homeFolder = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
    if (str_starts_with($className, 'Aoc\\Y2023\\Day'))
    {
        $classParts = explode('\\', $className);
        if (count($classParts) < 4)
            return;
        $classParts[2] = strtolower($classParts[2]);
        if (isset($classParts[4]))
            $classParts[3] = strtolower($classParts[3]);

        $filePath = $homeFolder . join(DIRECTORY_SEPARATOR, array_slice($classParts, 2)) . '.php';
        if (file_exists($filePath)) {
            include_once $filePath;
            return;
        }
    }
});
