<?php
foreach ($_POST as $key => $value) {
    echo "Field:".htmlspecialchars($key)."<hr>".htmlspecialchars($value)."<hr>";
}
?>