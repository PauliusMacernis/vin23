<?php
declare(strict_types = 1);

spl_autoload_register(static function (string $className) {
    $classFile = sprintf('src%s%s.php', DIRECTORY_SEPARATOR, strtr($className, ['\\' => DIRECTORY_SEPARATOR]));

    if (!is_file($classFile)) {
        throw new RuntimeException(sprintf('Class "%s" is not found in "%s" therefore cannot be loaded.', $className, $classFile));
    }

    include $classFile;
});

