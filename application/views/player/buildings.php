<?php view('layout/header', [ 'title' => findById($building_groups, $building_group_id)->name . ' buildings' ]) ?>

<h1><?= findById($building_groups, $building_group_id)->name ?> buildings</h1>
<p>
    <?php foreach ($building_groups as $building_group): ?>
        <a <?= $building_group->id == $building_group_id ? 'class="active"' : '' ?> href="/buildings/<?= slug($building_group->name) ?>"><?= $building_group->name ?></a> &nbsp;
    <?php endforeach ?>
</p>
<table>
    <?php foreach ($buildings as $building): ?>
        <tr>
            <td style="width: 0;">
                <img width="150" src="/images/buildings/<?= $building->id ?>.jpg">
            </td>
            <td>
                <h3><?= $building->name ?></h3>
                <p>
                    <?php if ($building->income > 0): ?>
                        Income: <span class="money">$ <?= number_format($building->income) ?> / s</span>
                    <?php endif ?>
                    <?php if ($building->defence > 0): ?>
                        Defence: <span class="defence"><?= number_format($building->defence) ?></span>
                    <?php endif ?>
                </p>
            </td>
            <td>Price: <span class="money">$ <?= number_format($building->price) ?></span></td>
            <td><?= $building->amount ?></td>
            <td>
                <a href="/buy_buildings?id=<?= $building->id ?>&amount=1">Buy</a> &nbsp;
                <a href="/sell_buildings?id=<?= $building->id ?>&amount=1">Sell</a>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<?php view('layout/footer') ?>
