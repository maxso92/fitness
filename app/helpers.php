<?php

if (!function_exists('description_excerpt')) {
    function description_excerpt($text, $max_length = 200)
    {
        // Удаляем все HTML теги, кроме пробелов
        $html = preg_replace('/<[^>]*>/', ' ', $text);
        // Заменяем &nbsp; и &zwj; на обычные пробелы
        $html = str_replace(['&nbsp;', '&zwj;'], ' ', $html);
        // Удаляем лишние пробелы и переводы строк
        $html = preg_replace('/\s+/', ' ', $html);
        $html = trim($html);

        if (mb_strlen($html) <= $max_length) {
            return $html;
        }

        // Обрезаем текст до указанной длины и добавляем многоточие
        return mb_substr($html, 0, $max_length) . '...';
    }
}
