<?php view('layout/header', [ 'title' => '404 Not Found!' ]) ?>

<h1>404 Not Found!</h1>
<p>The requested URL <code><?= $path ?></code> is not found on this server.</p>
<p>If you entered the URL manually please check your spelling and try again.</p>

<?php view('layout/footer') ?>