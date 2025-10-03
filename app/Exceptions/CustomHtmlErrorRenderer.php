<?php

namespace App\Exceptions;

use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;

class CustomHtmlErrorRenderer extends HtmlErrorRenderer
{
    protected function getFileExcerpt(string $filePath, int $line): string
    {
        // highlight_file() fonksiyonunu çağırmak yerine sadece satır bilgisini döndürüyoruz
        return sprintf(
            '<pre style="background:#f8f8f8;padding:10px;">%s:%d</pre>',
            htmlspecialchars($filePath, ENT_QUOTES, 'UTF-8'),
            $line
        );
    }
}

