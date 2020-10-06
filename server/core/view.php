<?php

function minify_html (string $data) {
    return preg_replace(
        [ '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s' ],
        [ '>', '<', '\\1' ],
        $data
    );
}

function view (string $_path, array $_data = []) {
    extract($_data);
    unset($_data);
    ob_start();
    eval('unset($_path) ?>' . preg_replace(
        ['/^\s*@view\((.*)\)/m', '/^\s*@(.*)/m', '/{{(.*)}}/U', '/{!!(.*)!!}/U'],
        ['<?php echo view($1) ?>', '<?php $1 ?>', '<?php echo htmlspecialchars($1, ENT_QUOTES, \'UTF-8\') ?>', '<?php echo $1 ?>'],
        file_get_contents(ROOT . '/views/' . str_replace('.', '/', $_path) . '.html')
    ));
    $html = ob_get_contents();
    ob_end_clean();
    return minify_html($html);
}
