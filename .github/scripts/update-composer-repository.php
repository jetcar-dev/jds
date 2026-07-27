<?php

declare(strict_types=1);

$tag = $argv[1] ?? '';
if (! preg_match('/^v(\d+\.\d+\.\d+)$/', $tag, $matches)) {
    fwrite(STDERR, "Usage: php update-composer-repository.php v1.2.3\n");
    exit(1);
}

$version = $matches[1];
$root = dirname(__DIR__, 2);
$composer = json_decode(
    file_get_contents($root.'/composer.json'),
    true,
    flags: JSON_THROW_ON_ERROR,
);
$repositoryPath = $root.'/composer-repository/packages.json';
$repository = file_exists($repositoryPath)
    ? json_decode(file_get_contents($repositoryPath), true, flags: JSON_THROW_ON_ERROR)
    : ['packages' => []];

$package = array_filter([
    'name' => $composer['name'],
    'version' => $version,
    'type' => $composer['type'] ?? 'library',
    'license' => $composer['license'] ?? null,
    'require' => $composer['require'] ?? [],
    'autoload' => $composer['autoload'] ?? [],
    'extra' => $composer['extra'] ?? [],
], static fn (mixed $value): bool => $value !== null);

$package['dist'] = [
    'type' => 'zip',
    'url' => "https://github.com/jetcar-dev/jds/releases/download/{$tag}/jetcar-jds-{$tag}.zip",
];

$repository['packages'][$composer['name']][$version] = $package;
uksort(
    $repository['packages'][$composer['name']],
    static fn (string $left, string $right): int => version_compare($right, $left),
);

file_put_contents(
    $repositoryPath,
    json_encode($repository, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR).PHP_EOL,
);
