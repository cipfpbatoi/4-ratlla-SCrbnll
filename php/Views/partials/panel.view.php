<div class="panel">
    <table>
        <tr>
            <td style="background-color: <?= $players[1]->getColor() ?>"><?= $players[1]->getName() ?></td>
            <td><?= $scores[1] ?></td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="background-color: <?= $players[2]->getColor() ?>; color: #000"><?= $players[2]->getName() ?></td>
            <td><?= $scores[2] ?></td>
        </tr>
    </table>
</div>