<?php view('layout/header', [ 'title' => 'Confirm with your password' ]) ?>

<form method="post">
    <h1>Confirm with your password</h1>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" autofocus required>
    </div>
    <div>
        <button type="submit">Confirm</button>
    </div>
</form>

<?php view('layout/footer') ?>
