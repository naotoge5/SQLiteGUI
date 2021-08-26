<?php
require_once("controller/home.php");
?>
<main class="m-6">
    <div id="tables" class="overflow-x-scroll no-wrap">
        <a class="color-text-secondary no-underline" href="index.php?route=home">
            <span class="label py-2 px-3 mx-1 text-bold">+</span>
        </a>
        <?php foreach ($tables as $table) : ?>
            <a class="color-text-secondary no-underline" href="index.php?route=home&table=<?= $table ?>">
                <span class="label p-2 mx-1 text-bold __hover"><?= $table ?></span>
            </a>
        <?php endforeach; ?>
    </div>
    <h1 class="h1 my-4">Create New Table</h1>
    <div class="form-group">
        <div class="form-group-header">
            <label for="table-name">Table Name</label>
        </div>
        <div class="form-group-body">
            <input id="table-name" class="form-control" type="text" name="name" placeholder="name" />
        </div>
    </div>

    <div class="modal">
        <div class="modal-bg modal-close"></div>
        <div class="modal-content">
            <div class="Box">
                <div class="Box-header">
                    <button class="close-button float-right modal-close" type="button">
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
                                <input class="form-control input-sm mr-2 mb-1" type="text" name="name" placeholder="name" />
                                <select class="form-select select-sm mr-2 mb-1">
                                    <option selected disabled>Data Type</option>
                                    <option>TEXT</option>
                                    <option>INTEGER</option>
                                    <option>FLOAT</option>
                                    <option>REAL</option>
                                    <option>BLOB</option>
                                    <option>NUMERIC</option>
                                    <option>DATE</option>
                                    <option>TIME</option>
                                    <option>DATETIME</option>
                                    <option>BOOLEAN</option>
                                </select>
                                <select class="form-select select-sm mr-2 mb-1">
                                    <option>------------</option>
                                    <option>PRIMARY KEY</option>
                                    <option>UNIQUE</option>
                                </select>
                                <label class="m-1"> <input type="checkbox" /> NOT NULL </label>
                            </div>
                        </div>
                    </li>
                    <li class="Box-row">
                        <div class="form-group">
                            <div class="form-group-header">
                                <label>Constraint</label>
                            </div>
                            <div class="form-group-body">
                                <input class="form-control input-sm width-auto mr-2 mb-1" type="text" name="default" autocomplete="on" list="function" placeholder="default" />
                                <datalist id="function">
                                    <option value="date('now', 'localtime')">
                                    <option value="time('now', 'localtime')">
                                    <option value="datetime('now', 'localtime')">
                                    <option value="true">
                                    <option value="false">
                                </datalist>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="Box-footer">
                    <button id="Create" class="btn btn-sm btn-block" type="button">Create Column</button>
                </div>
            </div>
        </div>
    </div>
    <small id="Add" class="__hover">
        <svg class="octicon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15">
            <circle cx="12" cy="12" r="10" opacity=".35" />
            <path d="M17,13H7c-0.552,0-1-0.448-1-1v0c0-0.552,0.448-1,1-1h10c0.552,0,1,0.448,1,1v0C18,12.552,17.552,13,17,13z" />
            <path d="M11,17V7c0-0.552,0.448-1,1-1h0c0.552,0,1,0.448,1,1v10c0,0.552-0.448,1-1,1h0C11.448,18,11,17.552,11,17z" />
        </svg>
        Add Column
    </small>
</main>
<footer>

</footer>