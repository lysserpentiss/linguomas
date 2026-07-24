<?php
require_once 'db_connect.php';

$sql = "SELECT id, name, email, message, rating, created_at FROM feedback ORDER BY created_at DESC LIMIT 20";
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) == 0) {
    echo '<div class="review-empty"><span>💬</span>Пока нет отзывов. Будь первым!</div>';
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        $stars = str_repeat('★', $row['rating']) . str_repeat('☆', 5 - $row['rating']);
        $date = date('d.m.Y', strtotime($row['created_at']));
        echo '
        <div class="review-card" data-id="' . $row['id'] . '">
            <div class="review-header">
                <span class="review-name">' . htmlspecialchars($row['name']) . '</span>
                <span class="review-date">' . $date . '</span>
                <button class="review-delete-btn" onclick="deleteReview(' . $row['id'] . ')" title="Удалить отзыв">✕</button>
            </div>
            <div class="review-stars">' . $stars . '</div>
            <div class="review-message">' . nl2br(htmlspecialchars($row['message'])) . '</div>
            <div class="review-email">✉️ ' . htmlspecialchars($row['email']) . '</div>
        </div>';
    }
}

mysqli_close($link);
?>