<?php
if (!is_logged_in()) {
    redirect('/login.php');
}