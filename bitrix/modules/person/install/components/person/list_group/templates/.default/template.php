<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<table>
    <tr>
        <th>ID</th><th>Название группы</th><th>Количество персон в группе</th>
    </tr>
    <? foreach( $arResult['persons'] as $k=>$v): ?>
        <tr>
            <td><?=$k?></td><td><?=$v['title']?></td><td><?=$v['cnt']?></td>
        </tr>
    <?endforeach;?>
</table>

