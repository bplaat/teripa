<?php view('layout/header', [ 'title' => 'Admin' ]) ?>

<?php view('admin/navbar') ?>

<form method="post">
    <h2>Edit a unit group</h2>
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= $unit_group->name ?>" autofocus required>
    </div>
    <div>
        <button type="submit">Save</button>
    </div>
</form>

<?php view('layout/footer') ?>
