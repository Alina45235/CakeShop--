<?php
if (!is_logged_in() || !is_admin()) {
    redirect('/SHOP/login.php');
}