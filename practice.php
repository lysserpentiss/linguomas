<?php
// Для практики пока не используем БД, все данные из practice_data.js
?>

<html>

<head>
<title>linguomas - practice</title>
<link rel="stylesheet" type="text/css" href="style.css"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,700&family=Nunito:wght@400;600;800&display=swap" rel="stylesheet">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

<style>
/* ===== ОБЩИЕ СТИЛИ ДЛЯ ПРАКТИКИ ===== */
.practice-section { padding: 40px 0 60px; background: #FDF8F0; }
.practice-tabs { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 30px; justify-content: center; }
.practice-tab { padding: 12px 24px; border: 2px solid #e0e0e0; border-radius: 50px; background: white; font-weight: 700; font-size: 15px; cursor: pointer; transition: all 0.3s; color: #555; }
.practice-tab:hover { border-color: #B22222; color: #B22222; }
.practice-tab.active { border-color: #B22222; background: #B22222; color: white; }
.tab-content { display: none; animation: fadeIn 0.5s ease; }
.tab-content.active { display: block; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

/* ===== ФЛЕШ-КАРТЫ ===== */
.flashcard-container { display: flex; flex-direction: column; align-items: center; gap: 25px; max-width: 600px; margin: 0 auto; }
.flashcard { width: 100%; height: 260px; perspective: 1000px; cursor: pointer; }
.flashcard-inner { position: relative; width: 100%; height: 100%; transition: transform 0.6s; transform-style: preserve-3d; }
.flashcard.flipped .flashcard-inner { transform: rotateY(180deg); }
.flashcard-front, .flashcard-back { position: absolute; width: 100%; height: 100%; backface-visibility: hidden; border-radius: 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px; box-shadow: 0 8px 30px rgba(0,0,0,0.08); text-align: center; background: white; }
.flashcard-front { background: linear-gradient(145deg, #FFF5F0, #FFE8E0); border: 2px solid rgba(178, 34, 34, 0.1); }
.flashcard-back { transform: rotateY(180deg); background: linear-gradient(145deg, #F0FFF5, #E0F5E8); border: 2px solid rgba(0, 100, 55, 0.1); }
.flashcard-front .word { font-size: 42px; font-weight: 800; color: #B22222; margin-bottom: 10px; }
.flashcard-front .lang-label { font-size: 14px; color: #888; }
.flashcard-back .word { font-size: 36px; font-weight: 700; color: #006437; margin-bottom: 10px; }
.flashcard-back .translation { font-size: 20px; color: #555; }
.flashcard-back .example { font-size: 14px; color: #888; margin-top: 10px; font-style: italic; }
.flashcard-counter { font-size: 16px; color: #888; }
.flashcard-buttons { display: flex; gap: 20px; flex-wrap: wrap; justify-content: center; }
.flashcard-buttons button { padding: 12px 30px; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; }
.btn-know { background: #006437; color: white; }
.btn-know:hover { background: #004D29; transform: translateY(-3px); }
.btn-dont-know { background: #B22222; color: white; }
.btn-dont-know:hover { background: #8B1A1A; transform: translateY(-3px); }
.btn-reset { background: #ddd; color: #333; }
.btn-reset:hover { background: #ccc; transform: translateY(-3px); }
.flashcard-stats { display: flex; gap: 30px; font-size: 16px; color: #555; }
.flashcard-stats span { font-weight: 700; }
.flashcard-stats .known { color: #006437; }
.flashcard-stats .unknown { color: #B22222; }

/* ===== ТЕСТ ===== */
.test-container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 24px; box-shadow: 0 8px 30px rgba(0,0,0,0.04); }
.test-header { display: flex; justify-content: space-between; font-size: 16px; color: #888; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
.test-counter { font-weight: 700; color: #333; }
.test-score { font-weight: 700; }
.test-score .correct { color: #006437; }
.test-score .wrong { color: #B22222; }
.test-question { text-align: center; padding: 20px 0; }
.test-lang { font-size: 20px; display: block; margin-bottom: 5px; }
.test-word { font-size: 42px; font-weight: 800; color: #B22222; display: block; margin-bottom: 5px; }
.test-hint { font-size: 16px; color: #888; }
.test-options { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin: 20px 0; }
@media (max-width: 500px) { .test-options { grid-template-columns: 1fr; } }
.test-options button { padding: 14px 20px; border: 2px solid #e0e0e0; border-radius: 14px; background: white; font-size: 18px; font-weight: 600; cursor: pointer; transition: all 0.3s; color: #333; }
.test-options button:hover:not(.disabled) { border-color: #B22222; transform: translateY(-2px); }
.test-options button.correct { border-color: #006437; background: #E8F5E9; color: #006437; }
.test-options button.wrong { border-color: #B22222; background: #FFEBEE; color: #B22222; }
.test-options button.disabled { cursor: not-allowed; opacity: 0.7; }
.test-options button.reveal-correct { border-color: #006437; background: #E8F5E9; color: #006437; }
.test-feedback { text-align: center; font-size: 18px; min-height: 40px; font-weight: 700; }
.test-feedback.correct { color: #006437; }
.test-feedback.wrong { color: #B22222; }
.btn-test-next { display: block; margin: 10px auto 0; padding: 12px 40px; background: #B22222; color: white; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; }
.btn-test-next:hover { background: #8B1A1A; transform: translateY(-3px); }
.test-result { text-align: center; padding: 20px 0; }
.test-result .emoji { font-size: 60px; display: block; margin-bottom: 10px; }
.test-result .message { font-size: 22px; font-weight: 700; color: #333; }
.test-result .stats { font-size: 16px; color: #888; margin-top: 10px; }
.btn-test-restart { margin-top: 15px; padding: 12px 30px; background: #006437; color: white; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; }
.btn-test-restart:hover { background: #004D29; transform: translateY(-3px); }

/* ===== СОБЕРИ ПРЕДЛОЖЕНИЕ ===== */
.sentence-container { max-width: 700px; margin: 0 auto; background: white; padding: 30px; border-radius: 24px; box-shadow: 0 8px 30px rgba(0,0,0,0.04); }
.sentence-header { display: flex; justify-content: space-between; font-size: 16px; color: #888; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
.sentence-counter { font-weight: 700; color: #333; }
.sentence-score { font-weight: 700; }
.sentence-score .correct { color: #006437; }
.sentence-score .wrong { color: #B22222; }
.sentence-prompt { text-align: center; padding: 10px 0 20px; }
.sentence-lang { font-size: 24px; display: block; margin-bottom: 5px; }
.sentence-hint { font-size: 16px; color: #888; }
.sentence-build { min-height: 60px; background: #FDF8F0; border-radius: 16px; padding: 16px 20px; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 8px; align-items: center; border: 2px dashed #ddd; transition: all 0.3s; }
.sentence-build .word-token { background: #B22222; color: white; padding: 6px 16px; border-radius: 20px; font-weight: 600; font-size: 16px; cursor: pointer; transition: all 0.3s; animation: popIn 0.3s ease; }
.sentence-build .word-token:hover { transform: scale(1.05); background: #8B1A1A; }
.sentence-build .word-token.pt-token { background: #006437; }
.sentence-build .word-token.pt-token:hover { background: #004D29; }
@keyframes popIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.sentence-words { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; padding: 10px 0; min-height: 50px; }
.sentence-words .word-chip { background: #f5f5f5; padding: 10px 22px; border-radius: 30px; font-weight: 600; font-size: 18px; cursor: pointer; transition: all 0.3s; border: 2px solid transparent; color: #333; }
.sentence-words .word-chip:hover { border-color: #B22222; transform: translateY(-3px); }
.sentence-words .word-chip.pt-chip:hover { border-color: #006437; }
.sentence-words .word-chip:active { transform: scale(0.95); }
.sentence-words .word-chip.hidden { display: none; }
.sentence-feedback { text-align: center; font-size: 18px; min-height: 40px; font-weight: 700; padding: 10px 0; }
.sentence-feedback.correct { color: #006437; }
.sentence-feedback.wrong { color: #B22222; }
.btn-sentence-next { display: block; margin: 10px auto 0; padding: 12px 40px; background: #B22222; color: white; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; }
.btn-sentence-next:hover { background: #8B1A1A; transform: translateY(-3px); }
.sentence-result { text-align: center; padding: 20px 0; }
.sentence-result .emoji { font-size: 60px; display: block; margin-bottom: 10px; }
.sentence-result .message { font-size: 22px; font-weight: 700; color: #333; }
.sentence-result .stats { font-size: 16px; color: #888; margin-top: 10px; }
.btn-sentence-restart { margin-top: 15px; padding: 12px 30px; background: #006437; color: white; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; }
.btn-sentence-restart:hover { background: #004D29; transform: translateY(-3px); }

/* ===== АУДИО-ДИКТАНТ ===== */
.audio-container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 24px; box-shadow: 0 8px 30px rgba(0,0,0,0.04); }
.audio-header { display: flex; justify-content: space-between; font-size: 16px; color: #888; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
.audio-counter { font-weight: 700; color: #333; }
.audio-score { font-weight: 700; }
.audio-score .correct { color: #006437; }
.audio-score .wrong { color: #B22222; }
.audio-question { text-align: center; padding: 15px 0 20px; }
.audio-lang { font-size: 20px; display: block; margin-bottom: 5px; }
.audio-hint { font-size: 16px; color: #888; }
.audio-controls { text-align: center; margin-bottom: 20px; }
.btn-audio-play { padding: 16px 50px; background: #B22222; color: white; border: none; border-radius: 50px; font-weight: 700; font-size: 20px; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(178, 34, 34, 0.3); }
.btn-audio-play:hover { background: #8B1A1A; transform: translateY(-3px); box-shadow: 0 8px 25px rgba(178, 34, 34, 0.4); }
.audio-input-area { display: flex; gap: 12px; margin-bottom: 15px; flex-wrap: wrap; }
.audio-input { flex: 1; padding: 14px 20px; border: 2px solid #e0e0e0; border-radius: 14px; font-size: 20px; font-weight: 600; min-width: 150px; transition: border 0.3s; }
.audio-input:focus { border-color: #B22222; outline: none; }
.audio-input.correct { border-color: #006437; background: #E8F5E9; }
.audio-input.wrong { border-color: #B22222; background: #FFEBEE; }
.btn-audio-check { padding: 14px 30px; background: #006437; color: white; border: none; border-radius: 14px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; }
.btn-audio-check:hover { background: #004D29; transform: translateY(-2px); }
.btn-audio-check:disabled { opacity: 0.5; cursor: not-allowed; }
.audio-feedback { text-align: center; font-size: 18px; min-height: 30px; font-weight: 600; }
.audio-feedback.correct { color: #006437; }
.audio-feedback.wrong { color: #B22222; }
.audio-result { text-align: center; font-size: 20px; min-height: 40px; padding: 10px 0; letter-spacing: 4px; font-weight: 700; }
.audio-result .char-correct { color: #006437; }
.audio-result .char-wrong { color: #B22222; }
.btn-audio-next { display: block; margin: 10px auto 0; padding: 12px 40px; background: #B22222; color: white; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; }
.btn-audio-next:hover { background: #8B1A1A; transform: translateY(-3px); }
.audio-final { text-align: center; padding: 20px 0; }
.audio-final .emoji { font-size: 60px; display: block; margin-bottom: 10px; }
.audio-final .message { font-size: 22px; font-weight: 700; color: #333; }
.audio-final .stats { font-size: 16px; color: #888; margin-top: 10px; }
.btn-audio-restart { margin-top: 15px; padding: 12px 30px; background: #006437; color: white; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; }
.btn-audio-restart:hover { background: #004D29; transform: translateY(-3px); }

/* ===== ГОНКА ===== */
.race-container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 24px; box-shadow: 0 8px 30px rgba(0,0,0,0.04); text-align: center; }
.race-header { display: flex; justify-content: space-between; font-size: 18px; font-weight: 700; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
.race-timer { color: #B22222; font-size: 24px; background: #FFF0E0; padding: 4px 18px; border-radius: 30px; }
.race-timer.warning { color: #B22222; animation: pulse 0.8s infinite; }
@keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); background: #FFEBEE; } }
.race-score { color: #333; }
.race-best { color: #006437; }
.race-question { padding: 15px 0; }
.race-word { font-size: 42px; font-weight: 800; color: #B22222; display: block; margin-bottom: 5px; }
.race-hint { font-size: 16px; color: #888; }
.race-options { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin: 20px 0; }
@media (max-width: 500px) { .race-options { grid-template-columns: 1fr; } }
.race-options button { padding: 14px 20px; border: 2px solid #e0e0e0; border-radius: 14px; background: white; font-size: 18px; font-weight: 600; cursor: pointer; transition: all 0.3s; color: #333; }
.race-options button:hover:not(.disabled) { border-color: #B22222; transform: translateY(-2px); }
.race-options button.correct { border-color: #006437; background: #E8F5E9; color: #006437; }
.race-options button.wrong { border-color: #B22222; background: #FFEBEE; color: #B22222; }
.race-options button.disabled { cursor: not-allowed; opacity: 0.7; }
.race-feedback { font-size: 18px; min-height: 30px; font-weight: 600; }
.race-feedback.correct { color: #006437; }
.race-feedback.wrong { color: #B22222; }
.race-progress { width: 100%; height: 8px; background: #f0f0f0; border-radius: 10px; margin-top: 15px; overflow: hidden; }
.race-progress-bar { height: 100%; background: #B22222; width: 0%; transition: width 0.3s ease; }
.race-controls { display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-top: 15px; }
.btn-race-start, .btn-race-restart { padding: 12px 30px; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; }
.btn-race-start { background: #006437; color: white; }
.btn-race-start:hover { background: #004D29; transform: translateY(-3px); }
.btn-race-restart { background: #B22222; color: white; }
.btn-race-restart:hover { background: #8B1A1A; transform: translateY(-3px); }

/* ===== КНОПКА "НАВЕРХ" ===== */
.back-to-top { position: fixed; bottom: 30px; right: 30px; padding: 15px 20px; background: #B22222; color: white; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; box-shadow: 0 4px 20px rgba(178, 34, 34, 0.3); transition: all 0.3s ease; opacity: 0; visibility: hidden; z-index: 1000; }
.back-to-top.show { opacity: 1; visibility: visible; }
.back-to-top:hover { background: #8B1A1A; transform: translateY(-3px); box-shadow: 0 8px 30px rgba(178, 34, 34, 0.5); }
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
<li><a href="practice.php" class="active">Практика</a></li>
<li><a href="practice_advanced.php">Продвинутая практика</a></li>
<li><a href="downloads.html">Шпаргалки</a></li>
<li><a href="profile.php">Профиль</a></li>
<li><a href="feedback.php">Обратная связь</a></li>
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

<section class="practice-section">
<div class="container">
<h2 class="section-title">🧘 Тренируйся с удовольствием</h2>
<p class="section-subtitle">Игры и упражнения, которые помогут тебе заговорить на испанском и португальском</p>

<div class="practice-tabs">
<button class="practice-tab active" data-tab="flashcards">🃏 Карточки</button>
<button class="practice-tab" data-tab="test">🧠 Тест</button>
<button class="practice-tab" data-tab="sentences">📝 Собери</button>
<button class="practice-tab" data-tab="audio">🎧 Аудио</button>
<button class="practice-tab" data-tab="race">⏱️ Гонка</button>
</div>

<!-- ========================================================== -->
<!-- ВКЛАДКА 1: ФЛЕШ-КАРТЫ -->
<!-- ========================================================== -->
<div class="tab-content active" id="tab-flashcards">
<div class="flashcard-container" id="flashcardApp">
<div class="flashcard" id="flashcard" onclick="flipCard()">
<div class="flashcard-inner">
<div class="flashcard-front">
<div class="lang-label">🇪🇸 Испанский</div>
<div class="word" id="cardWord">pan</div>
</div>
<div class="flashcard-back">
<div class="translation">Перевод</div>
<div class="word" id="cardTranslation">хлеб</div>
<div class="example" id="cardExample">🇧🇷 port. pão</div>
</div>
</div>
</div>
<div class="flashcard-counter" id="cardCounter">1 / 20</div>
<div class="flashcard-buttons">
<button class="btn-know" onclick="markWord(true)">✅ Знаю</button>
<button class="btn-dont-know" onclick="markWord(false)">❌ Не знаю</button>
<button class="btn-reset" onclick="resetCards()">🔄 Сбросить</button>
</div>
<div class="flashcard-stats">
<span>✅ <span class="known" id="knownCount">0</span></span>
<span>❌ <span class="unknown" id="unknownCount">0</span></span>
<span>📦 <span id="remainingCount">20</span></span>
</div>
</div>
</div>

<!-- ========================================================== -->
<!-- ВКЛАДКА 2: ТЕСТ -->
<!-- ========================================================== -->
<div class="tab-content" id="tab-test">
<div class="test-container" id="testApp">
<div class="test-header">
<span class="test-counter" id="testCounter">1 / 10</span>
<span class="test-score" id="testScore">✅ 0 | ❌ 0</span>
</div>
<div class="test-question">
<span class="test-lang">🇪🇸</span>
<span class="test-word" id="testWord">pan</span>
<span class="test-hint">— выбери правильный перевод</span>
</div>
<div class="test-options" id="testOptions"></div>
<div class="test-feedback" id="testFeedback"></div>
<button class="btn-test-next" id="testNextBtn" style="display:none;">Следующее слово →</button>
<button class="btn-test-restart" onclick="initTest()" style="margin-top:15px; display:block; margin-left:auto; margin-right:auto;">🔄 Начать заново</button>
</div>
</div>

<!-- ========================================================== -->
<!-- ВКЛАДКА 3: СОБЕРИ ПРЕДЛОЖЕНИЕ -->
<!-- ========================================================== -->
<div class="tab-content" id="tab-sentences">
<div class="sentence-container" id="sentenceApp">
<div class="sentence-header">
<span class="sentence-counter" id="sentenceCounter">1 / 5</span>
<span class="sentence-score" id="sentenceScore">✅ 0 | ❌ 0</span>
</div>
<div class="sentence-prompt">
<span class="sentence-lang" id="sentenceLang">🇪🇸</span>
<span class="sentence-hint">Собери предложение, кликая на слова в правильном порядке</span>
</div>
<div class="sentence-build" id="sentenceBuild"></div>
<div class="sentence-words" id="sentenceWords"></div>
<div class="sentence-feedback" id="sentenceFeedback"></div>
<button class="btn-sentence-next" id="sentenceNextBtn" style="display:none;">Следующее предложение →</button>
<button class="btn-sentence-restart" onclick="initSentences()" style="margin-top:15px; display:block; margin-left:auto; margin-right:auto;">🔄 Начать заново</button>
</div>
</div>

<!-- ========================================================== -->
<!-- ВКЛАДКА 4: АУДИО-ДИКТАНТ -->
<!-- ========================================================== -->
<div class="tab-content" id="tab-audio">
<div class="audio-container" id="audioApp">
<div class="audio-header">
<span class="audio-counter" id="audioCounter">1 / 10</span>
<span class="audio-score" id="audioScore">✅ 0 | ❌ 0</span>
</div>
<div class="audio-question">
<span class="audio-lang" id="audioLang">🇪🇸 Испанский</span>
<span class="audio-hint">🎧 Нажми на 🔊, прослушай слово и напиши его</span>
</div>
<div class="audio-controls">
<button class="btn-audio-play" onclick="playAudioWord()">🔊 Прослушать</button>
</div>
<div class="audio-input-area">
<input type="text" class="audio-input" id="audioInput" placeholder="Напиши слово..." autocomplete="off">
<button class="btn-audio-check" id="audioCheckBtn">✅ Проверить</button>
</div>
<div class="audio-feedback" id="audioFeedback"></div>
<div class="audio-result" id="audioResult"></div>
<button class="btn-audio-next" id="audioNextBtn" style="display:none;">Следующее слово →</button>
<button class="btn-audio-restart" onclick="initAudio()" style="margin-top:15px; display:block; margin-left:auto; margin-right:auto;">🔄 Начать заново</button>
</div>
</div>

<!-- ========================================================== -->
<!-- ВКЛАДКА 5: ГОНКА -->
<!-- ========================================================== -->
<div class="tab-content" id="tab-race">
<div class="race-container" id="raceApp">
<div class="race-header">
<span class="race-timer" id="raceTimer">⏱️ 30</span>
<span class="race-score" id="raceScore">✅ 0 / 10</span>
<span class="race-best" id="raceBest">🏆 Рекорд: 0</span>
</div>
<div class="race-question">
<span class="race-word" id="raceWord">pan</span>
<span class="race-hint">— выбери перевод за 30 секунд</span>
</div>
<div class="race-options" id="raceOptions"></div>
<div class="race-feedback" id="raceFeedback"></div>
<div class="race-progress"><div class="race-progress-bar" id="raceProgressBar"></div></div>
<div class="race-controls">
<button class="btn-race-start" id="raceStartBtn">🏁 Старт</button>
<button class="btn-race-restart" id="raceRestartBtn" style="display:none;">🔄 Играть снова</button>
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

<script src="practice_data.js"></script>
<script>
// ============================================================
// 1. ТАБЫ
// ============================================================
document.querySelectorAll('.practice-tab').forEach(tab => {
tab.addEventListener('click', function() {
document.querySelectorAll('.practice-tab').forEach(t => t.classList.remove('active'));
this.classList.add('active');
document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
const target = document.getElementById('tab-' + this.dataset.tab);
if (target) target.classList.add('active');
});
});

// ============================================================
// 2. ВСПОМОГАТЕЛЬНАЯ ФУНКЦИЯ
// ============================================================
function shuffleArray(arr) {
for (let i = arr.length - 1; i > 0; i--) {
const j = Math.floor(Math.random() * (i + 1));
[arr[i], arr[j]] = [arr[j], arr[i]];
}
return arr;
}

// ============================================================
// 3. ФЛЕШ-КАРТЫ
// ============================================================
let currentWords = [];
let cardIndex = 0;
let knownWords = [];
let unknownWords = [];

function initFlashcards() {
currentWords = shuffleArray([...words]);
cardIndex = 0;
knownWords = [];
unknownWords = [];
renderCard();
updateCardStats();
}

function renderCard() {
if (currentWords.length === 0) {
document.getElementById('cardWord').textContent = '🎉';
document.getElementById('cardTranslation').textContent = 'Ты молодец!';
document.getElementById('cardExample').textContent = 'Все слова пройдены!';
document.getElementById('cardCounter').textContent = '✅ Завершено!';
return;
}
const word = currentWords[cardIndex];
document.getElementById('cardWord').textContent = word.es;
document.getElementById('cardTranslation').textContent = word.ru;
document.getElementById('cardExample').textContent = '🇧🇷 port. ' + word.pt;
document.getElementById('cardCounter').textContent = (cardIndex + 1) + ' / ' + currentWords.length;
document.getElementById('flashcard').classList.remove('flipped');
}

function flipCard() {
document.getElementById('flashcard').classList.toggle('flipped');
}

function markWord(known) {
if (currentWords.length === 0) return;
const word = currentWords[cardIndex];
if (known) knownWords.push(word);
else unknownWords.push(word);
currentWords.splice(cardIndex, 1);
if (cardIndex >= currentWords.length) {
if (unknownWords.length > 0) {
currentWords = [...unknownWords];
unknownWords = [];
cardIndex = 0;
} else {
cardIndex = 0;
renderCard();
updateCardStats();
return;
}
}
renderCard();
updateCardStats();
}

function resetCards() {
if (confirm('Сбросить все карточки?')) initFlashcards();
}

function updateCardStats() {
document.getElementById('knownCount').textContent = knownWords.length;
document.getElementById('unknownCount').textContent = unknownWords.length;
document.getElementById('remainingCount').textContent = currentWords.length;
}

// ============================================================
// 4. ТЕСТ
// ============================================================
let testWords = [];
let testIndex = 0;
let testCorrect = 0;
let testWrong = 0;
let isTestAnswered = false;

function initTest() {
const shuffled = shuffleArray([...words]);
testWords = shuffled.slice(0, Math.min(10, shuffled.length));
testIndex = 0;
testCorrect = 0;
testWrong = 0;
isTestAnswered = false;
renderTestQuestion();
}

function renderTestQuestion() {
if (testIndex >= testWords.length) {
showTestResult();
return;
}
const word = testWords[testIndex];
document.getElementById('testCounter').textContent = (testIndex + 1) + ' / ' + testWords.length;
document.getElementById('testScore').innerHTML = '✅ <span class="correct">' + testCorrect + '</span> | ❌ <span class="wrong">' + testWrong + '</span>';
document.getElementById('testWord').textContent = word.es;
document.getElementById('testFeedback').textContent = '';
document.getElementById('testFeedback').className = 'test-feedback';
document.getElementById('testNextBtn').style.display = 'none';
isTestAnswered = false;

const options = generateOptions(word.ru);
const container = document.getElementById('testOptions');
container.innerHTML = '';
options.forEach(opt => {
const btn = document.createElement('button');
btn.textContent = opt;
btn.dataset.correct = (opt === word.ru) ? 'true' : 'false';
btn.addEventListener('click', () => handleTestAnswer(btn, word.ru));
container.appendChild(btn);
});
}

function generateOptions(correctRu) {
const otherTranslations = words.filter(w => w.ru !== correctRu).map(w => w.ru);
shuffleArray(otherTranslations);
const wrongOptions = otherTranslations.slice(0, 3);
const allOptions = [correctRu, ...wrongOptions];
shuffleArray(allOptions);
return allOptions;
}

function handleTestAnswer(btn, correctRu) {
if (isTestAnswered) return;
isTestAnswered = true;
document.querySelectorAll('.test-options button').forEach(b => b.classList.add('disabled'));
const isCorrect = btn.dataset.correct === 'true';
if (isCorrect) {
btn.classList.add('correct');
testCorrect++;
document.getElementById('testFeedback').textContent = '✅ Правильно! Отлично!';
document.getElementById('testFeedback').className = 'test-feedback correct';
} else {
btn.classList.add('wrong');
testWrong++;
document.getElementById('testFeedback').textContent = '❌ Неправильно. Правильный ответ: ' + correctRu;
document.getElementById('testFeedback').className = 'test-feedback wrong';
document.querySelectorAll('.test-options button').forEach(b => {
if (b.dataset.correct === 'true') b.classList.add('reveal-correct');
});
}
document.getElementById('testScore').innerHTML = '✅ <span class="correct">' + testCorrect + '</span> | ❌ <span class="wrong">' + testWrong + '</span>';
document.getElementById('testNextBtn').style.display = 'block';
}

function showTestResult() {
const container = document.getElementById('testApp');
const total = testCorrect + testWrong;
const percent = total > 0 ? Math.round((testCorrect / total) * 100) : 0;
let emoji, message;
if (percent === 100) { emoji = '🏆'; message = 'Идеально! Ты гений!'; }
else if (percent >= 80) { emoji = '🌟'; message = 'Очень хорошо! Так держать!'; }
else if (percent >= 60) { emoji = '💪'; message = 'Неплохо! Есть куда расти!'; }
else { emoji = '📚'; message = 'Повтори слова и попробуй ещё раз!'; }
container.innerHTML = '<div class="test-result"><span class="emoji">' + emoji + '</span><div class="message">' + message + '</div><div class="stats">✅ ' + testCorrect + ' правильных | ❌ ' + testWrong + ' неправильных | 🎯 ' + percent + '%</div><button class="btn-test-restart" onclick="initTest()">🔄 Начать заново</button></div>';
}

document.getElementById('testNextBtn')?.addEventListener('click', function() {
testIndex++;
renderTestQuestion();
});

// ============================================================
// 5. СОБЕРИ ПРЕДЛОЖЕНИЕ
// ============================================================
let sentenceData = [];
let sentenceIndex = 0;
let sentenceCorrect = 0;
let sentenceWrong = 0;
let isSentenceAnswered = false;

function initSentences() {
sentenceData = shuffleArray([...sentences]);
sentenceIndex = 0;
sentenceCorrect = 0;
sentenceWrong = 0;
isSentenceAnswered = false;
renderSentence();
}

function renderSentence() {
if (sentenceIndex >= sentenceData.length) {
showSentenceResult();
return;
}
const sent = sentenceData[sentenceIndex];
const lang = sent.es ? 'es' : 'pt';
const wordsArr = lang === 'es' ? sent.es.split(' ') : sent.pt.split(' ');
document.getElementById('sentenceLang').textContent = lang === 'es' ? '🇪🇸 Испанский' : '🇧🇷 Португальский';
document.getElementById('sentenceCounter').textContent = (sentenceIndex + 1) + ' / ' + sentenceData.length;
document.getElementById('sentenceScore').innerHTML = '✅ <span class="correct">' + sentenceCorrect + '</span> | ❌ <span class="wrong">' + sentenceWrong + '</span>';
document.getElementById('sentenceBuild').innerHTML = '';
document.getElementById('sentenceFeedback').textContent = '';
document.getElementById('sentenceFeedback').className = 'sentence-feedback';
document.getElementById('sentenceNextBtn').style.display = 'none';
isSentenceAnswered = false;

const shuffledWords = shuffleArray([...wordsArr]);
const container = document.getElementById('sentenceWords');
container.innerHTML = '';
shuffledWords.forEach((word) => {
const chip = document.createElement('button');
chip.className = 'word-chip' + (lang === 'pt' ? ' pt-chip' : '');
chip.textContent = word;
chip.addEventListener('click', function() { handleSentenceWordClick(this, word); });
container.appendChild(chip);
});
updateBuildPlaceholder();
}

function updateBuildPlaceholder() {
const build = document.getElementById('sentenceBuild');
if (build.children.length === 0 && !isSentenceAnswered) {
build.innerHTML = '<span style="color:#ccc; font-size:14px;">👆 Кликай на слова, чтобы собрать предложение</span>';
}
}

function handleSentenceWordClick(chip, word) {
if (isSentenceAnswered) return;
if (chip.classList.contains('hidden')) return;
const build = document.getElementById('sentenceBuild');
if (build.querySelector('span[style]')) build.innerHTML = '';
const token = document.createElement('span');
const lang = document.getElementById('sentenceLang').textContent.includes('Португальский') ? 'pt-token' : '';
token.className = 'word-token' + (lang ? ' ' + lang : '');
token.textContent = word;
token.addEventListener('click', function() {
if (isSentenceAnswered) return;
this.remove();
document.querySelectorAll('.word-chip').forEach(c => {
if (c.textContent === word && c.classList.contains('hidden')) {
c.classList.remove('hidden');
}
});
updateBuildPlaceholder();
});
build.appendChild(token);
chip.classList.add('hidden');
if (document.querySelectorAll('.word-chip:not(.hidden)').length === 0) {
checkSentence();
}
}

function checkSentence() {
isSentenceAnswered = true;
const sent = sentenceData[sentenceIndex];
const lang = sent.es ? 'es' : 'pt';
const correctWords = lang === 'es' ? sent.es.split(' ') : sent.pt.split(' ');
const buildTokens = document.querySelectorAll('.word-token');
const userSentence = Array.from(buildTokens).map(t => t.textContent);
const userSentenceStr = userSentence.join(' ');
const correctSentenceStr = correctWords.join(' ');
const isCorrect = userSentenceStr === correctSentenceStr;
if (isCorrect) {
sentenceCorrect++;
document.getElementById('sentenceFeedback').textContent = '✅ Правильно! Отлично!';
document.getElementById('sentenceFeedback').className = 'sentence-feedback correct';
} else {
sentenceWrong++;
document.getElementById('sentenceFeedback').textContent = '❌ Неправильно. Правильно: "' + correctSentenceStr + '"';
document.getElementById('sentenceFeedback').className = 'sentence-feedback wrong';
}
document.getElementById('sentenceScore').innerHTML = '✅ <span class="correct">' + sentenceCorrect + '</span> | ❌ <span class="wrong">' + sentenceWrong + '</span>';
document.getElementById('sentenceNextBtn').style.display = 'block';
}

function showSentenceResult() {
const container = document.getElementById('sentenceApp');
const total = sentenceCorrect + sentenceWrong;
const percent = total > 0 ? Math.round((sentenceCorrect / total) * 100) : 0;
let emoji, message;
if (percent === 100) { emoji = '🏆'; message = 'Идеально! Ты мастер предложений!'; }
else if (percent >= 80) { emoji = '🌟'; message = 'Очень хорошо! Так держать!'; }
else if (percent >= 60) { emoji = '💪'; message = 'Неплохо! Есть куда расти!'; }
else { emoji = '📚'; message = 'Попробуй ещё раз!'; }
container.innerHTML = '<div class="sentence-result"><span class="emoji">' + emoji + '</span><div class="message">' + message + '</div><div class="stats">✅ ' + sentenceCorrect + ' правильных | ❌ ' + sentenceWrong + ' неправильных | 🎯 ' + percent + '%</div><button class="btn-sentence-restart" onclick="initSentences()">🔄 Начать заново</button></div>';
}

document.getElementById('sentenceNextBtn')?.addEventListener('click', function() {
sentenceIndex++;
renderSentence();
});

// ============================================================
// 6. АУДИО-ДИКТАНТ
// ============================================================
const audioWordList = [
{ word: 'pan', lang: 'es', text: 'pan' }, { word: 'pão', lang: 'pt', text: 'pão' },
{ word: 'vino', lang: 'es', text: 'vino' }, { word: 'vinho', lang: 'pt', text: 'vinho' },
{ word: 'playa', lang: 'es', text: 'playa' }, { word: 'praia', lang: 'pt', text: 'praia' },
{ word: 'madre', lang: 'es', text: 'madre' }, { word: 'mãe', lang: 'pt', text: 'mãe' },
{ word: 'sol', lang: 'es', text: 'sol' }, { word: 'sol', lang: 'pt', text: 'sol' },
{ word: 'rojo', lang: 'es', text: 'rojo' }, { word: 'vermelho', lang: 'pt', text: 'vermelho' },
{ word: 'casa', lang: 'es', text: 'casa' }, { word: 'casa', lang: 'pt', text: 'casa' }
];

let audioWordsList = [];
let audioIndex = 0;
let audioCorrect = 0;
let audioWrong = 0;
let isAudioAnswered = false;
let currentAudioWord = null;

function initAudio() {
audioWordsList = shuffleArray([...audioWordList]);
audioIndex = 0;
audioCorrect = 0;
audioWrong = 0;
isAudioAnswered = false;
renderAudioQuestion();
}

function renderAudioQuestion() {
if (audioIndex >= audioWordsList.length) {
showAudioFinal();
return;
}
currentAudioWord = audioWordsList[audioIndex];
const langLabel = currentAudioWord.lang === 'es' ? '🇪🇸 Испанский' : '🇧🇷 Португальский';
document.getElementById('audioCounter').textContent = (audioIndex + 1) + ' / ' + audioWordsList.length;
document.getElementById('audioScore').innerHTML = '✅ <span class="correct">' + audioCorrect + '</span> | ❌ <span class="wrong">' + audioWrong + '</span>';
document.getElementById('audioLang').textContent = langLabel;
document.getElementById('audioFeedback').textContent = '';
document.getElementById('audioFeedback').className = 'audio-feedback';
document.getElementById('audioResult').innerHTML = '';
document.getElementById('audioNextBtn').style.display = 'none';
document.getElementById('audioInput').value = '';
document.getElementById('audioInput').className = 'audio-input';
document.getElementById('audioInput').disabled = false;
document.getElementById('audioCheckBtn').disabled = false;
isAudioAnswered = false;
}

function playAudioWord() {
if (!currentAudioWord) return;
const text = currentAudioWord.word;
const lang = currentAudioWord.lang === 'es' ? 'es-ES' : 'pt-BR';
if (window.speechSynthesis) {
const utterance = new SpeechSynthesisUtterance(text);
utterance.lang = lang;
utterance.rate = 0.75;
utterance.pitch = 1;
window.speechSynthesis.speak(utterance);
} else {
alert('Ваш браузер не поддерживает синтез речи.');
}
}

function checkAudioAnswer() {
if (isAudioAnswered) return;
if (!currentAudioWord) return;
const userInput = document.getElementById('audioInput').value.trim().toLowerCase();
const correctWord = currentAudioWord.text.toLowerCase();
if (!userInput) {
document.getElementById('audioFeedback').textContent = '✍️ Напиши слово!';
document.getElementById('audioFeedback').className = 'audio-feedback';
return;
}
isAudioAnswered = true;
document.getElementById('audioInput').disabled = true;
document.getElementById('audioCheckBtn').disabled = true;
const maxLen = Math.max(userInput.length, correctWord.length);
let resultHtml = '';
let isCorrect = true;
for (let i = 0; i < maxLen; i++) {
const userChar = userInput[i] || '';
const correctChar = correctWord[i] || '';
if (userChar === correctChar) {
resultHtml += '<span class="char-correct">' + (correctChar || ' ') + '</span>';
} else {
resultHtml += '<span class="char-wrong">' + (userChar || '?') + '</span>';
isCorrect = false;
}
}
document.getElementById('audioResult').innerHTML = resultHtml;
if (isCorrect && userInput.length === correctWord.length) {
audioCorrect++;
document.getElementById('audioFeedback').textContent = '✅ Отлично! Ты услышала правильно!';
document.getElementById('audioFeedback').className = 'audio-feedback correct';
document.getElementById('audioInput').className = 'audio-input correct';
} else {
audioWrong++;
document.getElementById('audioFeedback').textContent = '❌ Правильно: "' + correctWord + '"';
document.getElementById('audioFeedback').className = 'audio-feedback wrong';
document.getElementById('audioInput').className = 'audio-input wrong';
}
document.getElementById('audioScore').innerHTML = '✅ <span class="correct">' + audioCorrect + '</span> | ❌ <span class="wrong">' + audioWrong + '</span>';
document.getElementById('audioNextBtn').style.display = 'block';
}

function showAudioFinal() {
const container = document.getElementById('audioApp');
const total = audioCorrect + audioWrong;
const percent = total > 0 ? Math.round((audioCorrect / total) * 100) : 0;
let emoji, message;
if (percent === 100) { emoji = '🏆'; message = 'Идеальный слух! Ты слышишь всё!'; }
else if (percent >= 80) { emoji = '🌟'; message = 'Отличный слух! Так держать!'; }
else if (percent >= 60) { emoji = '💪'; message = 'Неплохо! Тренируйся дальше!'; }
else { emoji = '🎧'; message = 'Попробуй ещё раз, слух натренируется!'; }
container.innerHTML = '<div class="audio-final"><span class="emoji">' + emoji + '</span><div class="message">' + message + '</div><div class="stats">✅ ' + audioCorrect + ' правильных | ❌ ' + audioWrong + ' неправильных | 🎯 ' + percent + '%</div><button class="btn-audio-restart" onclick="initAudio()">🔄 Начать заново</button></div>';
}

document.getElementById('audioCheckBtn')?.addEventListener('click', checkAudioAnswer);
document.getElementById('audioInput')?.addEventListener('keydown', function(e) {
if (e.key === 'Enter') { e.preventDefault(); checkAudioAnswer(); }
});
document.getElementById('audioNextBtn')?.addEventListener('click', function() {
audioIndex++;
renderAudioQuestion();
});

// ============================================================
// 7. ГОНКА
// ============================================================
let raceWordsArr = [];
let raceIndex = 0;
let raceCorrect = 0;
let raceTimer = 30;
let raceInterval = null;
let isRaceFinished = false;
let isRaceStarted = false;
let raceTotal = 10;
let bestScore = parseInt(localStorage.getItem('linguomas_race_best')) || 0;

function initRace() {
if (raceInterval) clearInterval(raceInterval);
const shuffled = shuffleArray([...words]);
raceWordsArr = shuffled.slice(0, raceTotal);
raceIndex = 0;
raceCorrect = 0;
raceTimer = 30;
isRaceFinished = false;
isRaceStarted = false;

document.getElementById('raceBest').textContent = '🏆 Рекорд: ' + bestScore;
document.getElementById('raceStartBtn').style.display = 'inline-block';
document.getElementById('raceRestartBtn').style.display = 'none';
document.getElementById('raceFeedback').textContent = '';
document.getElementById('raceFeedback').className = 'race-feedback';
document.getElementById('raceTimer').textContent = '⏱️ 30';
document.getElementById('raceTimer').classList.remove('warning');
document.getElementById('raceScore').textContent = '✅ 0 / 10';
document.getElementById('raceProgressBar').style.width = '0%';

renderRaceQuestion(true);
}

function renderRaceQuestion(waiting) {
if (raceIndex >= raceWordsArr.length || isRaceFinished) {
if (!isRaceFinished) finishRace();
return;
}
const word = raceWordsArr[raceIndex];
document.getElementById('raceWord').textContent = word.es;
document.getElementById('raceScore').textContent = '✅ ' + raceCorrect + ' / ' + raceTotal;
document.getElementById('raceProgressBar').style.width = ((raceIndex / raceTotal) * 100) + '%';

const options = generateOptions(word.ru);
const container = document.getElementById('raceOptions');
container.innerHTML = '';
options.forEach(opt => {
const btn = document.createElement('button');
btn.textContent = opt;
btn.dataset.correct = (opt === word.ru) ? 'true' : 'false';
btn.addEventListener('click', function() { handleRaceAnswer(this, word.ru); });
if (waiting || isRaceFinished) btn.classList.add('disabled');
container.appendChild(btn);
});
}

function startRace() {
if (isRaceStarted) return;
isRaceStarted = true;
document.getElementById('raceStartBtn').style.display = 'none';
document.getElementById('raceRestartBtn').style.display = 'none';
document.querySelectorAll('.race-options button').forEach(b => b.classList.remove('disabled'));

raceTimer = 30;
document.getElementById('raceTimer').textContent = '⏱️ ' + raceTimer;
document.getElementById('raceTimer').classList.remove('warning');
raceInterval = setInterval(function() {
raceTimer--;
document.getElementById('raceTimer').textContent = '⏱️ ' + raceTimer;
if (raceTimer <= 5) document.getElementById('raceTimer').classList.add('warning');
if (raceTimer <= 0) {
clearInterval(raceInterval);
if (!isRaceFinished) finishRace();
}
}, 1000);
}

function handleRaceAnswer(btn, correctRu) {
if (isRaceFinished) return;
if (!isRaceStarted) return;
if (btn.classList.contains('disabled')) return;

document.querySelectorAll('.race-options button').forEach(b => b.classList.add('disabled'));
const isCorrect = btn.dataset.correct === 'true';
if (isCorrect) {
btn.classList.add('correct');
raceCorrect++;
document.getElementById('raceFeedback').textContent = '✅ Правильно!';
document.getElementById('raceFeedback').className = 'race-feedback correct';
} else {
btn.classList.add('wrong');
document.getElementById('raceFeedback').textContent = '❌ Правильно: ' + correctRu;
document.getElementById('raceFeedback').className = 'race-feedback wrong';
document.querySelectorAll('.race-options button').forEach(b => {
if (b.dataset.correct === 'true') b.classList.add('correct');
});
}
document.getElementById('raceScore').textContent = '✅ ' + raceCorrect + ' / ' + raceTotal;

setTimeout(function() {
raceIndex++;
if (raceIndex >= raceWordsArr.length) finishRace();
else renderRaceQuestion(false);
}, 700);
}

function finishRace() {
if (isRaceFinished) return;
isRaceFinished = true;
clearInterval(raceInterval);
document.getElementById('raceTimer').textContent = '⏱️ 0';
document.getElementById('raceTimer').classList.remove('warning');
document.querySelectorAll('.race-options button').forEach(b => b.classList.add('disabled'));
document.getElementById('raceProgressBar').style.width = '100%';

const total = raceWordsArr.length;
const percent = Math.round((raceCorrect / total) * 100);
let isNewRecord = false;
if (raceCorrect > bestScore) {
bestScore = raceCorrect;
localStorage.setItem('linguomas_race_best', bestScore);
isNewRecord = true;
document.getElementById('raceBest').textContent = '🏆 Рекорд: ' + bestScore;
}
let emoji, message;
if (percent === 100) { emoji = '🏆'; message = 'Идеально! Ты чемпион!'; }
else if (percent >= 80) { emoji = '🌟'; message = 'Отлично! Ты быстрая!'; }
else if (percent >= 60) { emoji = '💪'; message = 'Неплохо! Тренируйся дальше!'; }
else { emoji = '📚'; message = 'Попробуй ещё раз!'; }
document.getElementById('raceFeedback').textContent = emoji + ' ' + message + ' | ✅ ' + raceCorrect + ' из ' + total + ' (' + percent + '%)' + (isNewRecord ? ' 🎉 НОВЫЙ РЕКОРД!' : '');
document.getElementById('raceFeedback').className = 'race-feedback ' + (percent >= 60 ? 'correct' : 'wrong');
document.getElementById('raceStartBtn').style.display = 'none';
document.getElementById('raceRestartBtn').style.display = 'inline-block';
}

document.getElementById('raceStartBtn')?.addEventListener('click', startRace);
document.getElementById('raceRestartBtn')?.addEventListener('click', initRace);

// ============================================================
// 8. ЗАПУСК
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
initFlashcards();
initTest();
initSentences();
initAudio();
initRace();
});
</script>
</body>
</html>