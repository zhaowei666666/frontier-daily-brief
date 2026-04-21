<?php
declare(strict_types=1);

$storageFile = '/opt/每日前沿云报/运行配置/wxpusher_spt.txt';
$spt = trim($_POST['spt'] ?? '');
$isValid = preg_match('/^[A-Za-z0-9_-]{8,}$/', $spt) === 1;

header('Content-Type: text/html; charset=utf-8');

function page(string $title, string $message, bool $ok): void
{
    $color = $ok ? '#0b8b7f' : '#a23a3a';
    echo '<!doctype html><html lang="zh-CN"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</title>';
    echo '<style>body{font-family:"Noto Sans SC","Microsoft YaHei",sans-serif;background:#f6f8f7;color:#1f2a2f;margin:0}.card{width:min(640px,calc(100vw - 24px));margin:28px auto;background:#fff;border-radius:18px;padding:24px 20px;box-shadow:0 14px 42px rgba(31,42,47,.08)}h1{margin-top:0;color:' . $color . '}a{color:#0b8b7f}</style></head><body><div class="card">';
    echo '<h1>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h1>';
    echo '<p>' . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . '</p>';
    echo '<p><a href="./index.html">返回日报首页</a></p>';
    echo '</div></body></html>';
}

if (!$isValid) {
    page('保存失败', '没有识别到有效的 SPT。请返回上一页重新粘贴。', false);
    exit;
}

$dir = dirname($storageFile);
if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
    page('保存失败', '服务器无法创建运行配置目录。', false);
    exit;
}

if (file_put_contents($storageFile, $spt . PHP_EOL) === false) {
    page('保存失败', '服务器无法写入 SPT。', false);
    exit;
}

@chmod($storageFile, 0664);
page('保存成功', '你的手机推送标识已经保存。\n从下一次定时任务开始，日报就会自动推送到你的手机。', true);

