<?php

function markdownToHtml(string $markdown): string {
    $html = $markdown;

    // Headers
    $html = preg_replace('/^# (.*)$/m', '<h1>$1</h1>', $html);
    $html = preg_replace('/^## (.*)$/m', '<h2>$1</h2>', $html);
    $html = preg_replace('/^### (.*)$/m', '<h3>$1</h3>', $html);
    $html = preg_replace('/^#### (.*)$/m', '<h4>$1</h4>', $html);
    $html = preg_replace('/^##### (.*)$/m', '<h5>$1</h5>', $html);
    $html = preg_replace('/^###### (.*)$/m', '<h6>$1</h6>', $html);

    // Bold and Italic
    $html = preg_replace('/\*\*(.*?)\*\*|\_\_(.*?)\_\_/', '<strong class="fw-bold">$1$2</strong>', $html);
    $html = preg_replace('/\*(.*?)\*|\_(.*?)\_/', '<em class="fst-italic">$1$2</em>', $html);

    // Blockquote
    $html = preg_replace('/\> (.*)/', '<blockquote class="blockquote">$1</blockquote>', $html);

    // Lists (Unordered and Ordered)
    $html = preg_replace('/^- (.*)$/m', '<ul class="list-group"><li class="list-group-item">$1</li></ul>', $html);
    $html = preg_replace('/^1\. (.*)$/m', '<ol class="list-group"><li class="list-group-item">$1</li></ol>', $html);

    // Code
    $html = preg_replace('/\`(.*)\`/', '<code class="bg-light text-dark p-1">$1</code>', $html);

    // Horizontal Line
    $html = preg_replace('/---/', '<hr class="my-4">', $html);

    // Links
    $html = preg_replace('/\[([^\]]*)\]\(([^)]*)\)/', '<a href="$2" class="text-primary">$1</a>', $html);

    // Line Breaks (only if not preceded by a header)
    $html = preg_replace('/(?<!<\/h[1-6]>)\n/', '<br>', $html);

    return $html;
}

?>