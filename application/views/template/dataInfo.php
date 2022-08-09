<?php
if (isset($dataInfo)) {
?>
    <table class="table">
        <thead class="bg-gray">
            <tr>
                <th>REGIST</th>
                <th>CUSTOMER</th>
                <th>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($dataInfo as $data) {
            ?>
                <tr>
                    <td><?= $data['REGIST']; ?></td>
                    <td><?= $data['CUSTOMER']; ?></td>
                    <td><?= $data['info']; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
<?php
}
?>