<?php view('layout/header', [ 'title' => 'Admin' ]) ?>

<?php view('admin/navbar') ?>

<form method="post">
    <h2>Create a unit group</h2>
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" autofocus required>
    </div>
    <div>
        <button type="submit">Create</button>
    </div>
</form>

<?php view('layout/footer') ?>
