<?php
if (!function_exists('join_paths')) {
    function join_paths(string $baseDir, ...$paths): string
    {
        return implode(DIRECTORY_SEPARATOR,
            array_map(
                static fn (string $path): string => trim(trim(trim($path), DIRECTORY_SEPARATOR)),
                array_merge([$baseDir], $paths)
            )
        );
    }
}