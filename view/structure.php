<?php
$table = new Table($route->getTable());
$columns = $table->getColumns();
?>
<main class="m-6 __mx-md-ex">
    <!-- テーブル一覧 -->
    <?php include('inc/list.php'); ?>
    <!-- /テーブル一覧 -->
    <nav class="UnderlineNav mt-6">
        <div class="UnderlineNav-body">
            <a class="UnderlineNav-item __hover-pointer" aria-current="page">Structure</a>
            <a class="UnderlineNav-item" href="content/">Content</a>
        </div>
        <div class="UnderlineNav-actions">
            <form id="Drop" action="/controller/_drop.php" method="post">
                <input type="hidden" name="drop-table-name" value="<?= $table->getName() ?>">
                <button class="btn btn-danger" type="submit">Drop Table</button>
            </form>
        </div>
    </nav>
    <!-- Name -->
    <div class="form-group">
        <div class="form-group-header">
            <label>Name</label>
        </div>
        <div class="form-group-body">
            <input class="form-control" type="text" name="table-name" placeholder="name" value="<?= $table->getName() ?>" />
        </div>
    </div>
    <!-- /Name -->
    <!-- Columns -->
    <div id="Columns" class="form-group">
        <div class="form-group-header">
            <label for="table-name">Columns</label>
        </div>
        <div class="form-group-body">
            <div class="overflow-x-scroll no-wrap __scroll">
                <table class="width-full text-center">
                    <thead>
                        <tr>
                            <th scope="col" class="color-border-secondary border">Name</th>
                            <th scope="col" class="color-border-secondary border">Type</th>
                            <th scope="col" class="color-border-secondary border">Primey Key</th>
                            <th scope="col" class="color-border-secondary border">Unique</th>
                            <th scope="col" class="color-border-secondary border">Not Null</th>
                            <th scope="col" class="color-border-secondary border">Default</th>
                            <th scope="col" class="color-border-secondary border">Check</th>
                            <th scope="col" colspan="2" class="color-border-secondary border">Foreign Key</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($columns as $index => $tmp) : ?>
                            <?php
                            $constraints = $tmp->getConstraints();
                            $foreign_key = false;
                            if ($constraints["foreign_key"]) {
                                $foreign_key = $constraints["foreign_key"]["table"] . ":" . $constraints["foreign_key"]["column"];
                            }
                            ?>
                            <tr class="__hover-pointer __hover-bg" data-index="<?= $index ?>">
                                <td class="color-border-secondary border"><?= $tmp->getName() ?></td>
                                <td class="color-border-secondary border"><?= $tmp->getType() ?></td>
                                <td class="color-border-secondary border"><?php if ($constraints["primary_key"]) echo ($constraints["autoincrement"]) ? 'autoincrement' : '○'; ?></td>
                                <td class="color-border-secondary border"><?php if ($constraints["unique"]) echo '○'; ?></td>
                                <td class="color-border-secondary border"><?php if ($constraints["not_null"]) echo '○'; ?></td>
                                <td class="color-border-secondary border"><?= $constraints["default"] ?></td>
                                <td class="color-border-secondary border"><?= $constraints["check"] ?></td>
                                <?php if ($constraints["foreign_key"]) : ?>
                                    <td class="color-border-secondary border"><?= $constraints["foreign_key"]["table"] ?></td>
                                    <td class="color-border-secondary border"><?= $constraints["foreign_key"]["column"] ?></td>
                                <?php else : ?>
                                    <td class="color-border-secondary border"></td>
                                    <td class="color-border-secondary border"></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <small class="__hover-pointer mt-4 j__add d-inline-block">
            <svg class="octicon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15">
                <circle cx="12" cy="12" r="10" opacity=".35" />
                <path d="M17,13H7c-0.552,0-1-0.448-1-1v0c0-0.552,0.448-1,1-1h10c0.552,0,1,0.448,1,1v0C18,12.552,17.552,13,17,13z" />
                <path d="M11,17V7c0-0.552,0.448-1,1-1h0c0.552,0,1,0.448,1,1v10c0,0.552-0.448,1-1,1h0C11.448,18,11,17.552,11,17z" />
            </svg>
            Add Column
        </small>
        <!-- __modal -->
        <div class="__modal">
            <div class="__modal-bg j__modal-close"></div>
            <div class="__modal-content">
                <div class="Box">
                    <div class="Box-header">
                        <button class="close-button float-right j__modal-close" type="button">
                            <svg class="octicon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                <path fill-rule="evenodd" d="M3.72 3.72a.75.75 0 011.06 0L8 6.94l3.22-3.22a.75.75 0 111.06 1.06L9.06 8l3.22 3.22a.75.75 0 11-1.06 1.06L8 9.06l-3.22 3.22a.75.75 0 01-1.06-1.06L6.94 8 3.72 4.78a.75.75 0 010-1.06z"></path>
                            </svg>
                        </button>
                        <h3 class="Box-title">New Column</h3>
                    </div>
                    <ul>
                        <li class="Box-row">
                            <div class="form-group">
                                <div class="form-group-header">
                                    <label>Column</label>
                                </div>
                                <div class="form-group-body">
                                    <input class="form-control input-sm mr-2 mb-1" type="text" name="column-name" placeholder="name" />
                                    <select class="form-select select-sm mr-2 mb-1" name="column-type">
                                        <option value="none" selected disabled>Data Type</option>
                                        <option value="text">TEXT</option>
                                        <option value="integer">INTEGER</option>
                                        <option value="float">FLOAT</option>
                                        <option value="real">REAL</option>
                                        <option value="blob">BLOB</option>
                                        <option value="numeric">NUMERIC</option>
                                        <option value="date">DATE</option>
                                        <option value="time">TIME</option>
                                        <option valu="datetime">DATETIME</option>
                                        <option value="boolean">BOOLEAN</option>
                                    </select>
                                </div>
                            </div>
                        </li>
                        <li class="Box-row">
                            <div class="form-group">
                                <div class="form-group-header">
                                    <label>Constraint</label>
                                </div>
                                <div class="form-group-body">
                                    <div class="d-flex flex-wrap">
                                        <div class="form-checkbox flex-1">
                                            <label class="no-wrap"><input type="checkbox" name="column-primary_key" />PRIMARY KEY</label>
                                        </div>
                                        <div class="form-checkbox flex-1">
                                            <label class="no-wrap"><input type="checkbox" name="column-unique" />UNIQUE</label>
                                        </div>
                                        <div class="form-checkbox flex-1">
                                            <label class="no-wrap"><input type="checkbox" name="column-not_null" />NOT NULL</label>
                                        </div>
                                        <div class="form-checkbox flex-1">
                                            <label class="no-wrap"><input type="checkbox" name="column-autoincrement" disabled />AUTOINCREMENT</label>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap">
                                        <div class="form-checkbox flex-1">
                                            <label class="no-wrap">
                                                <input type="checkbox" class="form-checkbox-details-trigger" name="column-default" />
                                                DEFAULT
                                                <span class="form-checkbox-details text-normal">
                                                    <input type="text" name="default-value" class="form-control input-sm width-auto" autocomplete="on" list="function" />
                                                </span>
                                            </label>
                                        </div>
                                        <datalist id="function">
                                            <option value="date('now', 'localtime')">
                                            <option value="time('now', 'localtime')">
                                            <option value="datetime('now', 'localtime')">
                                            <option value="true">
                                            <option value="false">
                                        </datalist>
                                        <div class="form-checkbox flex-1">
                                            <label class="no-wrap">
                                                <input type="checkbox" class="form-checkbox-details-trigger" name="column-check" />
                                                CHECK
                                                <span class="form-checkbox-details text-normal">
                                                    <input type="text" name="check-value" class="form-control input-sm width-auto" />
                                                </span>
                                            </label>
                                        </div>
                                        <div class="form-checkbox flex-1">
                                            <label class="no-wrap">
                                                <input type="checkbox" class="form-checkbox-details-trigger" name="column-foreign_key" />
                                                FOREIGN KEY
                                                <div class="d-flex">
                                                    <span class="form-checkbox-details text-normal">
                                                        <select class="form-select select-sm" name="foreign_key-table">
                                                            <option value="table" selected disabled>Table</option>
                                                            <?php foreach ($tables as $tmp) : ?>
                                                                <option value="<?= $tmp ?>"><?= $tmp ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </span>
                                                    <span class="form-checkbox-details text-normal">
                                                        <select class="form-select select-sm" name="foreign_key-column">
                                                            <option selected disabled>Column</option>
                                                        </select>
                                                    </span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="Box-footer">
                        <button class="btn btn-sm btn-block j__create" type="button" disabled>Create Column</button>
                        <button class="btn btn-sm btn-danger btn-block j__delete mt-2 hide" type="button">Delete Column</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /__modal -->
    </div>
    <!-- Constraints -->
    <div id="Constraints" class="form-group">
        <div class="form-group-header">
            <label>Constraints</label>
        </div>
        <div class="form-group-body">
            <ol id="Constraints" class="ml-4 d-inline-block width-fit">
                <li class="__hover-pointer py-1">sa</li>
            </ol>
        </div>
        <small class="__hover-pointer mt-4 j__add d-inline-block">
            <svg class="octicon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15">
                <circle cx="12" cy="12" r="10" opacity=".35" />
                <path d="M17,13H7c-0.552,0-1-0.448-1-1v0c0-0.552,0.448-1,1-1h10c0.552,0,1,0.448,1,1v0C18,12.552,17.552,13,17,13z" />
                <path d="M11,17V7c0-0.552,0.448-1,1-1h0c0.552,0,1,0.448,1,1v10c0,0.552-0.448,1-1,1h0C11.448,18,11,17.552,11,17z" />
            </svg>
            Add Constraint
        </small>
        <!-- modal -->
        <div class="__modal">
            <div class="__modal-bg j__modal-close"></div>
            <div class="__modal-content">
                <div class="Box">
                    <div class="Box-header">
                        <button class="close-button float-right j__modal-close" type="button">
                            <svg class="octicon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                <path fill-rule="evenodd" d="M3.72 3.72a.75.75 0 011.06 0L8 6.94l3.22-3.22a.75.75 0 111.06 1.06L9.06 8l3.22 3.22a.75.75 0 11-1.06 1.06L8 9.06l-3.22 3.22a.75.75 0 01-1.06-1.06L6.94 8 3.72 4.78a.75.75 0 010-1.06z"></path>
                            </svg>
                        </button>
                        <h3 class="Box-title">New Constraint</h3>
                    </div>
                    <ul>
                        <li class="Box-row">
                            <div class="form-group">
                                <div class="form-group-header">
                                    <label>Constraint</label>
                                </div>
                                <div class="form-group-body">
                                    <select class="form-select select-sm mr-2 mb-1" name="constraint-name">
                                        <option value="none" selected disabled>Constraint</option>
                                        <option value="primary_key">PRIMARY KEY</option>
                                        <option value="unique">UNIQUE</option>
                                        <option value="check">CHECK</option>
                                        <option value="foreign_key">FOREIGN KEY</option>
                                    </select>
                                </div>
                            </div>
                        </li>
                        <li class="Box-row">
                            <div class="form-group">
                                <div class="form-group-header">
                                    <label>Constraint</label>
                                </div>
                                <div class="form-group-body">
                                    <div class="d-flex">
                                        <div class="form-checkbox flex-1">
                                            <label><input type="checkbox" name="column-primary_key" value="primary_key" />PRIMARY KEY</label>
                                        </div>
                                        <div class="form-checkbox flex-1">
                                            <label><input type="checkbox" name="column-unique" value="unique" />UNIQUE</label>
                                        </div>
                                        <div class="form-checkbox flex-1">
                                            <label><input type="checkbox" name="column-not_null" value="not_null" />NOT NULL</label>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap">
                                        <div class="form-checkbox flex-1">
                                            <label aria-live="polite">
                                                <input type="checkbox" class="form-checkbox-details-trigger" name="column-default" />
                                                DEFAULT
                                                <span class="form-checkbox-details text-normal">
                                                    <input type="text" name="default-value" class="form-control input-sm width-auto" autocomplete="on" list="function" />
                                                </span>
                                            </label>
                                        </div>
                                        <datalist id="function">
                                            <option value="date('now', 'localtime')">
                                            <option value="time('now', 'localtime')">
                                            <option value="datetime('now', 'localtime')">
                                            <option value="true">
                                            <option value="false">
                                        </datalist>
                                        <div class="form-checkbox flex-1">
                                            <label aria-live="polite">
                                                <input type="checkbox" class="form-checkbox-details-trigger" name="column-check" />
                                                CHECK
                                                <span class="form-checkbox-details text-normal">
                                                    <input type="text" name="check-value" class="form-control input-sm width-auto" />
                                                </span>
                                            </label>
                                        </div>
                                        <div class="form-checkbox flex-1">
                                            <label aria-live="polite">
                                                <input type="checkbox" class="form-checkbox-details-trigger" name="column-foreign_key" />
                                                FOREIGN KEY
                                                <div class="d-flex">
                                                    <span class="form-checkbox-details text-normal">
                                                        <select class="form-select select-sm" name="foreign_key-table">
                                                            <option value="table" selected disabled>Table</option>
                                                            <?php foreach ($tables as $tmp) : ?>
                                                                <option value="<?= $tmp ?>"><?= $tmp ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </span>
                                                    <span class="form-checkbox-details text-normal">
                                                        <select class="form-select select-sm" name="foreign_key-column">
                                                            <option selected disabled>Column</option>
                                                        </select>
                                                    </span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="Box-footer">
                        <button class="btn btn-sm btn-block j__create mb-2" type="button">Create Column</button>
                        <button class="btn btn-sm btn-danger btn-block j__delete" type="button">Delete Column</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal -->
    </div>
    <!-- /Constraints -->
    <div class="form-group">
        <div class="form-group-header">
            <label for="table-name">Schema</label>
        </div>
        <div class="form-group-body">
            <code>
                <textarea class="form-control" name="table-schema" readonly placeholder="schema"><?= $table->getSchema() ?></textarea>
            </code>
            <!--<pre><code></code></pre>-->
        </div>
    </div>
    <form id="Create" action="controller/_new.php" method="post">
        <button class="btn btn-block" type="submit">Update Table</button>
    </form>
</main>