<?php view('layout/header', [ 'title' => 'Home' ]) ?>

<?php if (!is_null(Auth::player())): ?>
    <h1>Welcome, <?= Auth::player()->first_name ?>!</h1>
<?php else: ?>
    <h1>Home</h1>
<?php endif ?>

<?php view('layout/footer') ?>
