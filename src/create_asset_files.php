<?php
$publicDir = __DIR__ . '/../public';
$jsDir  = __DIR__ . '/../public/js';
$jsVDir = __DIR__ . '/../public/js/vendor';
$cssDir = __DIR__ . '/../public/css';
//$cssVDir = __DIR__ . '/../public/css/vendor';
//$imgDir = __DIR__ . '/../public/img';

$staticFileHashes = [];
$staticFileHashes = array_merge($staticFileHashes, listFileHashes($publicDir));
$staticFileHashes = array_merge($staticFileHashes, listFileHashes($jsDir));
$staticFileHashes = array_merge($staticFileHashes, listFileHashes($jsVDir));
$staticFileHashes = array_merge($staticFileHashes, listFileHashes($cssDir));
//$staticFileHashes = array_merge($staticFileHashes, listFileHashes($cssVDir));
//$staticFileHashes = array_merge($staticFileHashes, listFileHashes($imgDir));

$publicDirPath = realpath(__DIR__ . '/../public');
$contents = "<?php\nreturn [\n";
foreach ($staticFileHashes as $realPath => $hash) {
    $publicPath = str_replace($publicDirPath, '', $realPath);
    $shortHash = substr($hash, 0, 8);
    $contents .= "    '{$publicPath}' => '{$publicPath}?{$shortHash}',\n";
}
$contents .= "];\n";
file_put_contents(__DIR__ . '/../src/asset_files.php', $contents);

function listFileHashes($dir)
{
    $list = [];
    $it = new DirectoryIterator($dir);
    foreach ($it as $fileInfo) {
        if ($fileInfo->isFile()) {
            if ($fileInfo->getExtension() === 'swp'
                || substr($fileInfo->getFilename(), 0, 1) === '.') {
                continue;
            }
            $list[$fileInfo->getRealPath()] = md5_file($fileInfo->getRealPath());
        }
    }
    return $list;
}
