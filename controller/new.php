<?php
touch('../db/' . $_POST['name'] . '.db');
header('Location: ../index.php');
