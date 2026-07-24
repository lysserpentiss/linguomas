<?php
session_start();
require_once 'db_connect.php';

// Считаем количество отзывов
$count_sql = "SELECT COUNT(*) as total FROM feedback";
$count_result = mysqli_query($link, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$total_reviews = $count_row['total'] ?? 0;
?>
<html>

<head>
<title>linguomas - feedback</title>
<link rel="stylesheet" type="text/css" href="style.css"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,700&family=Nunito:wght@400;600;800&display=swap" rel="stylesheet">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<style>
/* все твои стили остаются без изменений */
.feedback-section {
padding: 40px 0 60px;
background: #FDF8F0;
}
.feedback-grid {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 40px;
margin-top: 30px;
}
@media (max-width: 768px) {
.feedback-grid {
grid-template-columns: 1fr;
}
}
.feedback-form {
background: white;
padding: 35px 30px;
border-radius: 24px;
box-shadow: 0 8px 30px rgba(0,0,0,0.04);
}
.feedback-form h3 {
font-family: 'Playfair Display', serif;
font-size: 24px;
margin-bottom: 5px;
}
.feedback-form .sub {
color: #888;
margin-bottom: 20px;
}
.form-group {
margin-bottom: 18px;
}
.form-group label {
display: block;
font-weight: 600;
margin-bottom: 5px;
color: #333;
}
.form-group input,
.form-group textarea,
.form-group select {
width: 100%;
padding: 12px 16px;
border: 2px solid #e0e0e0;
border-radius: 14px;
font-size: 16px;
font-family: inherit;
transition: border 0.3s;
box-sizing: border-box;
}
.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
border-color: #B22222;
outline: none;
}
.form-group textarea {
min-height: 120px;
resize: vertical;
}
.form-group .rating-stars {
display: flex;
gap: 8px;
font-size: 28px;
}
.form-group .rating-stars span {
cursor: pointer;
color: #ddd;
transition: color 0.3s;
user-select: none;
}
.form-group .rating-stars span.active {
color: #F1C40F;
}
.form-group .rating-stars span:hover {
color: #F1C40F;
}
.submit-btn {
width: 100%;
padding: 16px;
background: #B22222;
color: white;
border: none;
border-radius: 50px;
font-weight: 700;
font-size: 18px;
cursor: pointer;
transition: all 0.3s;
}
.submit-btn:hover {
background: #8B1A1A;
transform: translateY(-2px);
box-shadow: 0 8px 25px rgba(178, 34, 34, 0.3);
}
.reviews-list {
display: flex;
flex-direction: column;
gap: 20px;
max-height: 600px;
overflow-y: auto;
padding-right: 10px;
}
.reviews-list::-webkit-scrollbar {
width: 6px;
}
.reviews-list::-webkit-scrollbar-track {
background: #f0f0f0;
border-radius: 10px;
}
.reviews-list::-webkit-scrollbar-thumb {
background: #B22222;
border-radius: 10px;
}
.review-card {
background: white;
padding: 20px 25px;
border-radius: 16px;
box-shadow: 0 4px 15px rgba(0,0,0,0.04);
border-left: 4px solid #B22222;
transition: all 0.3s;
}
.review-card:hover {
transform: translateX(4px);
box-shadow: 0 6px 20px rgba(0,0,0,0.06);
}
.review-card .review-header {
display: flex;
justify-content: space-between;
align-items: center;
flex-wrap: wrap;
gap: 10px;
margin-bottom: 8px;
}
.review-card .review-name {
font-weight: 700;
font-size: 18px;
color: #2D2D2D;
}
.review-card .review-date {
font-size: 13px;
color: #aaa;
}
.review-card .review-stars {
color: #F1C40F;
font-size: 16px;
letter-spacing: 2px;
}
.review-card .review-message {
color: #555;
line-height: 1.6;
margin-top: 6px;
}
.review-card .review-email {
font-size: 13px;
color: #888;
margin-top: 4px;
}
.review-empty {
text-align: center;
color: #aaa;
padding: 40px 0;
font-size: 16px;
}
.review-empty span {
font-size: 40px;
display: block;
margin-bottom: 10px;
}
.alert {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-weight: 600;
    display: none;
    position: relative;
    align-items: center;
    justify-content: space-between;
}
.alert.success {
    display: flex;
    background: #E8F5E9;
    color: #2E7D32;
    border: 1px solid #A5D6A7;
}
.alert.error {
    display: flex;
    background: #FFEBEE;
    color: #C62828;
    border: 1px solid #EF9A9A;
}
.alert-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: inherit;
    opacity: 0.6;
    padding: 0 5px;
    transition: opacity 0.3s;
}
.alert-close:hover {
    opacity: 1;
}
.feedback-title {
display: flex;
justify-content: space-between;
align-items: center;
flex-wrap: wrap;
gap: 15px;
}
.feedback-title .review-count {
font-size: 16px;
color: #888;
}
.review-delete-btn {
    background: none;
    border: none;
    color: #B22222;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s;
    padding: 0 5px;
}
.review-delete-btn:hover {
    color: #8B1A1A;
    transform: scale(1.2);
}
</style>
</head>

<body>
<header>
<div class="container">
<div class="logo">🌎 LINGUOMAS</div>
<nav>
<ul>
<li><a href="index.html">Главная</a></li>
<li><a href="spanish.html">Испанский</a></li>
<li><a href="portugues.html">Португальский</a></li>
<li><a href="compare.html">Сравнить</a></li>
<li><a href="practice.php">Практика</a></li>
<li><a href="practice_advanced.php">Продвинутая практика</a></li>
<li><a href="downloads.html">Шпаргалки</a></li>
<li><a href="profile.php">Профиль</a></li>
<li><a href="feedback.php"class="active">Обратная связь</a></li>
</ul>
</nav>
</div>
</header>

<main>
<button id="backToTop" class="back-to-top">⬆ Наверх</button>

<div class="back-nav">
<div class="container">
<a href="index.html" class="back-link">← На главную</a>
</div>
</div>

<section class="feedback-section">
<div class="container">
<div class="feedback-title">
<h2 class="section-title" style="margin-bottom:0;">💬 Отзывы и предложения</h2>
<span class="review-count" id="reviewCount"><?php echo $total_reviews; ?> отзывов</span>
</div>
<p class="section-subtitle">Напиши нам, что думаешь о сайте или как мы можем его улучшить</p>

<!-- АЛЕРТ -->
<div id="alert" class="alert" style="display:none;">
    <span id="alertText"></span>
    <button class="alert-close" onclick="closeAlert()">✕</button>
</div>

<div class="feedback-grid">
<!-- ФОРМА -->
<div class="feedback-form">
<h3>📝 Оставить отзыв</h3>
<p class="sub">Твоё мнение важно для нас ❤️<br>
<small style="color:#aaa;">* — обязательное поле, остальное по желанию</small></p>
<form id="feedbackForm" action="feedback_process.php" method="POST">

<div class="form-group">
<label for="name">Имя *</label>
<input type="text" id="name" name="name" placeholder="Например: Анна" required>
</div>

<div class="form-group">
<label for="email">Email</label>
<input type="email" id="email" name="email" placeholder="example@mail.ru (необязательно)">
</div>

<div class="form-group">
<label>Оценка</label>
<div class="rating-stars" id="ratingStars">
<span data-value="1">★</span>
<span data-value="2">★</span>
<span data-value="3">★</span>
<span data-value="4">★</span>
<span data-value="5">★</span>
</div>
<input type="hidden" name="rating" id="ratingInput" value="0">
<span id="ratingLabel" style="font-size:14px; color:#888; display:block; margin-top:4px;">Оцените нас</span>
</div>

<div class="form-group">
<label for="message">Сообщение</label>
<textarea id="message" name="message" placeholder="Поделись своим мнением... (необязательно)"></textarea>
</div>

<button type="submit" class="submit-btn">✉️ Отправить</button>
</form>
</div> 

<!-- ОТЗЫВЫ -->
<div>
    <h3 style="font-family:'Playfair Display',serif; font-size:22px; margin-bottom:15px;">📋 Последние отзывы</h3>
    <div class="reviews-list" id="reviewsList">
        <?php include 'get_feedback.php'; ?>
    </div>
</div>

</div>
</div>
</section>					
</main>

<footer class="footer">
<div class="container">
<p>LinguoMas, 2026. Сделано с ❤️ для изучения языков</p>
</div>
</footer>

<script>
// ============================================================
// ЗВЁЗДЫ ДЛЯ ОЦЕНКИ
// ============================================================
const stars = document.querySelectorAll('#ratingStars span');
const ratingInput = document.getElementById('ratingInput');
const ratingLabel = document.getElementById('ratingLabel');
let selectedRating = 0;

function updateStars(value) {
    stars.forEach(s => {
        const v = parseInt(s.dataset.value);
        s.style.color = v <= value && value > 0 ? '#F1C40F' : '#ddd';
        s.classList.toggle('active', v <= value && value > 0);
    });
}

stars.forEach(star => {
    star.addEventListener('mouseenter', function() {
        const value = parseInt(this.dataset.value);
        updateStars(value);
        const labels = ['Оцените нас', '⭐ Ужасно', '⭐ Плохо', '⭐⭐⭐ Нормально', '⭐⭐⭐⭐ Хорошо', '⭐⭐⭐⭐⭐ Отлично!'];
        ratingLabel.textContent = labels[value] || 'Оцените нас';
    });
});

const starsContainer = document.getElementById('ratingStars');
starsContainer.addEventListener('mouseleave', function() {
    updateStars(selectedRating);
    const labels = ['Оцените нас', '⭐ Ужасно', '⭐ Плохо', '⭐⭐⭐ Нормально', '⭐⭐⭐⭐ Хорошо', '⭐⭐⭐⭐⭐ Отлично!'];
    ratingLabel.textContent = labels[selectedRating] || 'Оцените нас';
});

stars.forEach(star => {
    star.addEventListener('click', function() {
        const value = parseInt(this.dataset.value);
        selectedRating = value;
        ratingInput.value = value;
        updateStars(value);
        const labels = ['Оцените нас', '⭐ Ужасно', '⭐ Плохо', '⭐⭐⭐ Нормально', '⭐⭐⭐⭐ Хорошо', '⭐⭐⭐⭐⭐ Отлично!'];
        ratingLabel.textContent = labels[value];
    });
});

document.addEventListener('DOMContentLoaded', function() {
    updateStars(0);
    ratingLabel.textContent = 'Оцените нас';
});

// ============================================================
// АЛЕРТ: ПОКАЗ И АВТО-ИСЧЕЗНОВЕНИЕ
// ============================================================
function showAlert(message, type = 'success') {
    const alert = document.getElementById('alert');
    const alertText = document.getElementById('alertText');
    
    alert.className = 'alert ' + type;
    alertText.textContent = message;
    alert.style.display = 'flex';
    
    setTimeout(() => {
        alert.style.display = 'none';
    }, 4000);
}

const urlParams = new URLSearchParams(window.location.search);

if (urlParams.has('success')) {
    showAlert('✅ ' + urlParams.get('success'), 'success');
}

if (urlParams.has('error')) {
    showAlert('❌ ' + urlParams.get('error'), 'error');
}

function closeAlert() {
    const alert = document.getElementById('alert');
    alert.style.display = 'none';
}

// ============================================================
// УДАЛЕНИЕ ОТЗЫВА (AJAX)
// ============================================================
function deleteReview(id) {
    if (!confirm('Удалить этот отзыв?')) return;

    const formData = new FormData();
    formData.append('id', id);

    fetch('delete_feedback.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const card = document.querySelector(`.review-card[data-id="${id}"]`);
            if (card) card.remove();
            const remaining = document.querySelectorAll('.review-card');
            if (remaining.length === 0) {
                const container = document.getElementById('reviewsList');
                container.innerHTML = '<div class="review-empty"><span>💬</span>Пока нет отзывов. Будь первым!</div>';
            }
            // Обновляем счётчик
            const countSpan = document.getElementById('reviewCount');
            const newCount = document.querySelectorAll('.review-card').length;
            countSpan.textContent = newCount + ' отзывов';
        } else {
            alert('Ошибка при удалении: ' + data.error);
        }
    })
    .catch(error => {
        alert('Ошибка соединения: ' + error);
    });
}
</script>
</body>
</html>