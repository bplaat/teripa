<?php view('layout/header', [ 'title' => 'Admin' ]) ?>

<?php view('admin/navbar') ?>

<h2>Units groups (<a href="/admin/unit_groups/create">Create</a>)</h2>
<table>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th></th>
    </tr>

    <?php foreach ($unit_groups as $unit_group): ?>
        <tr>
            <td><?= $unit_group->id ?></td>
            <td style="width: 800px;"><a href="/units/<?= slug($unit_group->name) ?>"><?= $unit_group->name ?></a></td>
            <td>
                <a href="/admin/unit_groups/<?= $unit_group->id ?>/edit">Edit</a> &nbsp;
                <a href="/admin/unit_groups/<?= $unit_group->id ?>/delete">Delete</a>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<?php view('layout/footer') ?>
