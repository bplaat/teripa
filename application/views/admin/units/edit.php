<?php view('layout/header', [ 'title' => 'Admin' ]) ?>

<?php view('admin/navbar') ?>

<form method="post" enctype="multipart/form-data">
    <h2>Edit a unit</h2>
    <div>
        <label for="image">Image:</label>
        <img src="/images/units/<?= slug(findById($unit_groups, $unit->unit_group_id)->name) ?>/<?= $unit->position ?>.jpg">
        <input type="file" id="image" name="image">
    </div>
    <div>
        <label for="unit_group_id">Unit group:</label>
        <select id="unit_group_id" name="unit_group_id" autofocus required>
            <?php foreach ($unit_groups as $unit_group): ?>
                <option value="<?= $unit_group->id ?>" <?= $unit_group->id == $unit->unit_group_id ? ' selected' : '' ?>><?= $unit_group->name ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <div>
        <label for="position">Position:</label>
        <input type="number" id="position" name="position" value="<?= $unit->position ?>" required>
    </div>
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= $unit->name ?>" required>
    </div>
    <div>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" value="<?= $unit->price ?>" required>
    </div>
    <div>
        <label for="attack">Attack:</label>
        <input type="number" id="attack" name="attack" value="<?= $unit->attack ?>" required>
    </div>
    <div>
        <label for="defence">Defence:</label>
        <input type="number" id="defence" name="defence" value="<?= $unit->defence ?>" required>
    </div>
    <div>
        <button type="submit">Save</button>
    </div>
</form>

<?php view('layout/footer') ?>
