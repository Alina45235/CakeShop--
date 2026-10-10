<?php
if (!is_logged_in() || !is_admin()) {
    redirect('/login.php');
}