<?php view('layout/header', [ 'title' => 'Admin' ]) ?>

<?php view('admin/navbar') ?>

<h2>Units (<a href="/admin/units/create">Create</a>)</h2>
<table>
    <tr>
        <th>#</th>
        <th>Group</th>
        <th>Name</th>
        <th>Price</th>
        <th>Attack</th>
        <th>Defence</th>
        <th></th>
    </tr>

    <?php foreach ($units as $unit): ?>
        <tr>
            <td><?= $unit->id ?></td>
            <td><?= findById($unit_groups, $unit->unit_group_id)->name ?></td>
            <td><?= $unit->name ?></td>
            <td><span class="money">$ <?= number_format($unit->price) ?></span></td>
            <td><span class="attack"><?= number_format($unit->attack) ?></span></td>
            <td><span class="defence"><?= number_format($unit->defence) ?></span></td>
            <td>
                <a href="/admin/units/<?= $unit->id ?>/edit">Edit</a> &nbsp;
                <a href="/admin/units/<?= $unit->id ?>/delete">Delete</a>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<?php view('layout/footer') ?>
