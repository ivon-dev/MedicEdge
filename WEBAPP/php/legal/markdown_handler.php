<?php

function markdownToHtml(string $markdown): string {
    $html = $markdown;

    // Regex replacements based on your dictionary
    $html = preg_replace('/^# (.*)$/m', '<h1 class="display-1">$1</h1>', $html);
    $html = preg_replace('/^## (.*)$/m', '<h2 class="display-2">$1</h2>', $html);
    $html = preg_replace('/^### (.*)$/m', '<h3 class="display-3">$1</h3>', $html);
    $html = preg_replace('/^#### (.*)$/m', '<h4 class="display-4">$1</h4>', $html);
    $html = preg_replace('/^##### (.*)$/m', '<h5 class="display-5">$1</h5>', $html);
    $html = preg_replace('/^###### (.*)$/m', '<h6 class="display-6">$1</h6>', $html);
    $html = preg_replace('/\*\*(.*?)\*\*|\_\_(.*?)\_\_/', '<strong class="fw-bold">$1$2</strong>', $html);
    $html = preg_replace('/\*(.*?)\*|\_(.*?)\_/', '<em class="fst-italic">$1$2</em>', $html);
    $html = preg_replace('/\~\~(.*?)\~\~/', '<del class="text-decoration-line-through">$1</del>', $html);
    $html = preg_replace('/\> (.*)/', '<blockquote class="blockquote">$1</blockquote>', $html);

    // Ordered and Unordered Lists
    $html = preg_replace_callback('/^- \[(x| )\] (.*)$/m', function ($matches) {
        $checked = ($matches[1] === 'x') ? 'checked' : '';
        return '<ul class="list-unstyled"><li class="form-check"><input class="form-check-input" type="checkbox" ' . $checked . '> ' . $matches[2] . '</li></ul>';
    }, $html);
    $html = preg_replace('/^- (.*)$/m', '<ul class="list-group"><li class="list-group-item">$1</li></ul>', $html);
    $html = preg_replace('/^1\. (.*)$/m', '<ol class="list-group"><li class="list-group-item">$1</li></ol>', $html);

    $html = preg_replace('/\`(.*)\`/', '<code class="bg-light text-dark p-1">$1</code>', $html);
    $html = preg_replace('/\n```(.*)\n```/s', '<pre class="bg-dark text-light p-3"><code>$1</code></pre>', $html);
    $html = preg_replace('/---/', '<hr class="my-4">', $html);
    $html = preg_replace('/\[([^\]]*)\]\(([^)]*)\)/', '<a href="$2" class="text-primary">$1</a>', $html);
    $html = preg_replace('/\!\[([^\]]*)\]\(([^)]*)\)/', '<img src="$2" alt="$1" class="img-fluid rounded">', $html);

    // Table conversion (simplified)
    $html = preg_replace('/\|(.*)\|(.*)\|/m', '<table class="table table-bordered"><tr><th>$1</th><th>$2</th></tr></table>', $html);

    $html = preg_replace('/\[\^(\d+)\]/', '<sup><a href="#footnote-$1" class="text-muted">$1</a></sup>', $html);
    $html = preg_replace('/(Term)/', '<dl class="row"><dt class="col-sm-3">$1</dt><dd class="col-sm-9">Definition</dd></dl>', $html);
    $html = str_replace(['\*', '\_'], ['*', '_'], $html);
    $html = preg_replace('/\<div\>(.*)\<\/div\>/', '<div class="p-3 bg-light border">$1</div>', $html);

    return $html;
}

?>