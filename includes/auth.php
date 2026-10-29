<?php
if (!is_logged_in()) {
    redirect('/SHOP/login.php');
}