<?php $tables = Table::list(); ?>
<div class="overflow-x-scroll no-wrap __scroll">
    <a class="color-text-secondary no-underline" href="/">
        <span class="label py-2 px-3 mx-1 text-bold">+</span>
    </a>
    <?php foreach ($tables as $tmp) : ?>
        <a class="color-text-secondary no-underline" href="/<?= $tmp ?>/">
            <span class="label p-2 mx-1 text-bold __hover-pointer"><?= $tmp ?></span>
        </a>
    <?php endforeach; ?>
</div>