<?php

function render(string $template, array $params = array()): string
{
    extract($params);
    ob_start();
    require ROOT_TEMPLATE . $template;
    return ob_get_clean();
}