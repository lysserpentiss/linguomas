<?php
// Для практики пока не используем БД, все данные из practice_data.js
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Продвинутая практика — LinguoMas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,700&family=Nunito:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        /* ===== ОБЩИЕ СТИЛИ ===== */
        .practice-section { padding: 40px 0 60px; background: #FDF8F0; }
        .practice-tabs { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 30px; justify-content: center; }
        .practice-tab { padding: 12px 24px; border: 2px solid #e0e0e0; border-radius: 50px; background: white; font-weight: 700; font-size: 15px; cursor: pointer; transition: all 0.3s; color: #555; }
        .practice-tab:hover { border-color: #B22222; color: #B22222; }
        .practice-tab.active { border-color: #B22222; background: #B22222; color: white; }
        .tab-content { display: none; animation: fadeIn 0.5s ease; }
        .tab-content.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

        /* ===== ГАДАЛКА ===== */
        .fortune-container { max-width: 700px; margin: 0 auto; background: white; padding: 30px; border-radius: 24px; box-shadow: 0 8px 30px rgba(0,0,0,0.04); text-align: center; }
        .fortune-header { display: flex; justify-content: space-between; font-size: 18px; font-weight: 700; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
        .fortune-title { color: #B22222; }
        .fortune-score { color: #888; }
        .fortune-cards { display: flex; gap: 20px; justify-content: center; margin: 20px 0; flex-wrap: wrap; }
        .fortune-card { width: 150px; height: 200px; background: linear-gradient(145deg, #1A1A2E, #16213E); border-radius: 20px; box-shadow: 0 8px 25px rgba(0,0,0,0.2); display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; transition: all 0.4s; color: white; font-size: 48px; position: relative; border: 2px solid rgba(255,215,0,0.2); }
        .fortune-card:hover:not(.revealed) { transform: translateY(-10px); box-shadow: 0 15px 40px rgba(0,0,0,0.3); }
        .fortune-card .card-back { font-size: 60px; opacity: 0.6; }
        .fortune-card .card-emoji { font-size: 50px; }
        .fortune-card .card-label { font-size: 14px; color: #aaa; margin-top: 8px; }
        .fortune-card.revealed { cursor: default; background: #FFF8F0; border: 2px solid #ddd; color: #333; transform: scale(1.02); }
        .fortune-card.revealed .card-emoji { font-size: 60px; }
        .fortune-card.revealed .card-label { color: #555; font-weight: 700; }
        .fortune-card.revealed .card-desc { font-size: 13px; color: #888; margin-top: 4px; padding: 0 10px; }
        .fortune-card .card-corner { position: absolute; top: 8px; left: 12px; font-size: 14px; color: #aaa; }
        .fortune-card .card-corner-bottom { position: absolute; bottom: 8px; right: 12px; font-size: 14px; color: #aaa; transform: rotate(180deg); }
        .fortune-result { margin: 20px 0; padding: 15px; min-height: 80px; background: #FDF8F0; border-radius: 16px; }
        .fortune-message { font-size: 18px; color: #555; line-height: 1.6; }
        .fortune-message .highlight-es { color: #B22222; font-weight: 700; }
        .fortune-message .highlight-pt { color: #006437; font-weight: 700; }
        .btn-fortune-next { padding: 12px 30px; background: #B22222; color: white; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; margin-right: 10px; }
        .btn-fortune-next:hover { background: #8B1A1A; transform: translateY(-3px); }
        .btn-fortune-reset { padding: 12px 30px; background: #ddd; color: #333; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; }
        .btn-fortune-reset:hover { background: #ccc; transform: translateY(-3px); }
        @media (max-width: 500px) { .fortune-card { width: 110px; height: 160px; font-size: 32px; } .fortune-card .card-emoji { font-size: 36px; } }

        /* ===== ПРЕДЛОГИ ===== */
        .prep-container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 24px; box-shadow: 0 8px 30px rgba(0,0,0,0.04); text-align: center; }
        .prep-header { display: flex; justify-content: space-between; font-size: 16px; color: #888; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
        .prep-counter { font-weight: 700; color: #333; }
        .prep-score { font-weight: 700; }
        .prep-score .correct { color: #006437; }
        .prep-score .wrong { color: #B22222; }
        .prep-question { padding: 15px 0 20px; }
        .prep-sentence { font-size: 28px; font-weight: 700; color: #2D2D2D; display: block; margin-bottom: 5px; }
        .prep-hint { font-size: 16px; color: #888; }
        .prep-options { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin: 20px 0; }
        @media (max-width: 500px) { .prep-options { grid-template-columns: 1fr; } }
        .prep-options button { padding: 14px 20px; border: 2px solid #e0e0e0; border-radius: 14px; background: white; font-size: 18px; font-weight: 600; cursor: pointer; transition: all 0.3s; color: #333; }
        .prep-options button:hover:not(.disabled) { border-color: #B22222; transform: translateY(-2px); }
        .prep-options button.correct { border-color: #006437; background: #E8F5E9; color: #006437; }
        .prep-options button.wrong { border-color: #B22222; background: #FFEBEE; color: #B22222; }
        .prep-options button.disabled { cursor: not-allowed; opacity: 0.7; }
        .prep-feedback { font-size: 18px; min-height: 30px; font-weight: 600; }
        .prep-feedback.correct { color: #006437; }
        .prep-feedback.wrong { color: #B22222; }
        .btn-prep-next { padding: 12px 30px; background: #B22222; color: white; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; margin-right: 10px; }
        .btn-prep-next:hover { background: #8B1A1A; transform: translateY(-3px); }
        .btn-prep-reset { padding: 12px 30px; background: #ddd; color: #333; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; }
        .btn-prep-reset:hover { background: #ccc; transform: translateY(-3px); }
        .prep-result { padding: 20px 0; }
        .prep-result .emoji { font-size: 60px; display: block; margin-bottom: 10px; }
        .prep-result .message { font-size: 22px; font-weight: 700; color: #333; }
        .prep-result .stats { font-size: 16px; color: #888; margin-top: 10px; }

        /* ===== R-КАРУСЕЛЬ ===== */
        .r-container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 24px; box-shadow: 0 8px 30px rgba(0,0,0,0.04); text-align: center; }
        .r-header { display: flex; justify-content: space-between; font-size: 16px; color: #888; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
        .r-counter { font-weight: 700; color: #333; }
        .r-score { font-weight: 700; }
        .r-score .correct { color: #006437; }
        .r-score .wrong { color: #B22222; }
        .r-question { padding: 15px 0 20px; }
        .r-word { font-size: 48px; font-weight: 800; color: #2D2D2D; display: block; margin-bottom: 5px; cursor: pointer; transition: all 0.3s; }
        .r-word:hover { color: #B22222; }
        .r-hint { font-size: 16px; color: #888; }
        .r-buttons { display: flex; gap: 20px; justify-content: center; margin: 20px 0; flex-wrap: wrap; }
        .btn-r-lang { padding: 16px 40px; border: 3px solid #e0e0e0; border-radius: 50px; background: white; font-size: 20px; font-weight: 700; cursor: pointer; transition: all 0.3s; min-width: 160px; }
        .btn-r-lang:hover:not(.disabled) { transform: translateY(-3px); }
        .btn-r-lang.es:hover:not(.disabled) { border-color: #B22222; background: #FFF5F0; }
        .btn-r-lang.pt:hover:not(.disabled) { border-color: #006437; background: #F0FFF5; }
        .btn-r-lang.correct { border-color: #006437; background: #E8F5E9; color: #006437; }
        .btn-r-lang.wrong { border-color: #B22222; background: #FFEBEE; color: #B22222; }
        .btn-r-lang.disabled { cursor: not-allowed; opacity: 0.6; }
        .btn-r-lang.reveal-correct { border-color: #006437; background: #E8F5E9; color: #006437; }
        .r-feedback { font-size: 18px; min-height: 30px; font-weight: 600; }
        .r-feedback.correct { color: #006437; }
        .r-feedback.wrong { color: #B22222; }
        .btn-r-next { padding: 12px 30px; background: #B22222; color: white; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; margin-right: 10px; }
        .btn-r-next:hover { background: #8B1A1A; transform: translateY(-3px); }
        .btn-r-reset { padding: 12px 30px; background: #ddd; color: #333; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; }
        .btn-r-reset:hover { background: #ccc; transform: translateY(-3px); }
        .r-result { padding: 20px 0; }
        .r-result .emoji { font-size: 60px; display: block; margin-bottom: 10px; }
        .r-result .message { font-size: 22px; font-weight: 700; color: #333; }
        .r-result .stats { font-size: 16px; color: #888; margin-top: 10px; }

        /* ===== НОСОВЫЕ ===== */
        .nasal-container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 24px; box-shadow: 0 8px 30px rgba(0,0,0,0.04); text-align: center; }
        .nasal-header { display: flex; justify-content: space-between; font-size: 16px; color: #888; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
        .nasal-counter { font-weight: 700; color: #333; }
        .nasal-score { font-weight: 700; }
        .nasal-score .correct { color: #006437; }
        .nasal-score .wrong { color: #B22222; }
        .nasal-question { padding: 15px 0 20px; }
        .nasal-hint { font-size: 16px; color: #888; }
        .nasal-controls { margin-bottom: 20px; }
        .btn-nasal-play { padding: 16px 40px; background: #B22222; color: white; border: none; border-radius: 50px; font-weight: 700; font-size: 20px; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(178, 34, 34, 0.3); }
        .btn-nasal-play:hover { background: #8B1A1A; transform: translateY(-3px); box-shadow: 0 8px 25px rgba(178, 34, 34, 0.4); }
        .nasal-options { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin: 20px 0; }
        @media (max-width: 500px) { .nasal-options { grid-template-columns: 1fr; } }
        .nasal-options button { padding: 14px 20px; border: 2px solid #e0e0e0; border-radius: 14px; background: white; font-size: 20px; font-weight: 700; cursor: pointer; transition: all 0.3s; color: #333; }
        .nasal-options button:hover:not(.disabled) { border-color: #B22222; transform: translateY(-2px); }
        .nasal-options button.correct { border-color: #006437; background: #E8F5E9; color: #006437; }
        .nasal-options button.wrong { border-color: #B22222; background: #FFEBEE; color: #B22222; }
        .nasal-options button.disabled { cursor: not-allowed; opacity: 0.7; }
        .nasal-feedback { font-size: 18px; min-height: 30px; font-weight: 600; }
        .nasal-feedback.correct { color: #006437; }
        .nasal-feedback.wrong { color: #B22222; }
        .btn-nasal-next { padding: 12px 30px; background: #B22222; color: white; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; margin-right: 10px; }
        .btn-nasal-next:hover { background: #8B1A1A; transform: translateY(-3px); }
        .btn-nasal-reset { padding: 12px 30px; background: #ddd; color: #333; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; }
        .btn-nasal-reset:hover { background: #ccc; transform: translateY(-3px); }
        .nasal-result { padding: 20px 0; }
        .nasal-result .emoji { font-size: 60px; display: block; margin-bottom: 10px; }
        .nasal-result .message { font-size: 22px; font-weight: 700; color: #333; }
        .nasal-result .stats { font-size: 16px; color: #888; margin-top: 10px; }

        /* ===== ПЕРЕВОДЧИК-ДЕТЕКТИВ ===== */
        .detective-container { max-width: 700px; margin: 0 auto; background: white; padding: 30px; border-radius: 24px; box-shadow: 0 8px 30px rgba(0,0,0,0.04); }
        .detective-header { display: flex; justify-content: space-between; font-size: 16px; color: #888; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
        .detective-counter { font-weight: 700; color: #333; }
        .detective-score { font-weight: 700; }
        .detective-score .correct { color: #006437; }
        .detective-score .wrong { color: #B22222; }
        .detective-badge { text-align: center; font-size: 14px; color: #888; margin-bottom: 10px; letter-spacing: 2px; text-transform: uppercase; }
        .detective-question { background: #FDF8F0; border-radius: 16px; padding: 25px; text-align: center; margin-bottom: 20px; }
        .detective-lang-label { font-size: 14px; color: #888; display: block; margin-bottom: 5px; }
        .detective-original { font-size: 24px; font-weight: 700; color: #B22222; margin-bottom: 5px; }
        .detective-arrow { font-size: 24px; color: #888; display: block; margin: 5px 0; }
        .detective-hint { font-size: 16px; color: #888; }
        .detective-options { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin: 15px 0; }
        @media (max-width: 500px) { .detective-options { grid-template-columns: 1fr; } }
        .detective-options button { padding: 14px 18px; border: 2px solid #e0e0e0; border-radius: 14px; background: white; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s; color: #333; text-align: left; line-height: 1.4; }
        .detective-options button:hover:not(.disabled) { border-color: #B22222; transform: translateY(-2px); }
        .detective-options button.correct { border-color: #006437; background: #E8F5E9; color: #006437; }
        .detective-options button.wrong { border-color: #B22222; background: #FFEBEE; color: #B22222; }
        .detective-options button.disabled { cursor: not-allowed; opacity: 0.7; }
        .detective-feedback { text-align: center; font-size: 18px; min-height: 30px; font-weight: 600; }
        .detective-feedback.correct { color: #006437; }
        .detective-feedback.wrong { color: #B22222; }
        .detective-tip { text-align: center; font-size: 15px; color: #555; background: #FFF8E0; padding: 12px 16px; border-radius: 12px; margin: 10px 0; border-left: 4px solid #F1C40F; }
        .btn-detective-next { display: block; margin: 10px auto 0; padding: 12px 30px; background: #B22222; color: white; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; }
        .btn-detective-next:hover { background: #8B1A1A; transform: translateY(-3px); }
        .btn-detective-reset { display: inline-block; margin: 10px auto 0; padding: 12px 30px; background: #ddd; color: #333; border: none; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; margin-left: 10px; }
        .btn-detective-reset:hover { background: #ccc; transform: translateY(-3px); }
        .detective-result { text-align: center; padding: 20px 0; }
        .detective-result .emoji { font-size: 60px; display: block; margin-bottom: 10px; }
        .detective-result .message { font-size: 22px; font-weight: 700; color: #333; }
        .detective-result .stats { font-size: 16px; color: #888; margin-top: 10px; }

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
                    <li><a href="practice.php">Практика</a></li>
                    <li><a href="practice_advanced.php" class="active">Продвинутая практика</a></li>
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
                <h2 class="section-title">🧘 Продвинутая практика</h2>
                <p class="section-subtitle">Игры для настоящих полиглотов</p>

                <div class="practice-tabs">
                    <button class="practice-tab active" data-tab="fortune">🔮 Гадалка</button>
                    <button class="practice-tab" data-tab="prepositions">📝 Предлоги</button>
                    <button class="practice-tab" data-tab="r-sound">🔊 R-карусель</button>
                    <button class="practice-tab" data-tab="nasal">👃 Носовые</button>
                    <button class="practice-tab" data-tab="detective">🕵️ Детектив</button>
                </div>

                <!-- ===== ГАДАЛКА ===== -->
                <div class="tab-content active" id="tab-fortune">
                    <div class="fortune-container" id="fortuneApp">
                        <div class="fortune-header">
                            <span class="fortune-title">🔮 Карты судьбы</span>
                            <span class="fortune-score">✨ Предсказаний: <span id="fortuneCount">0</span></span>
                        </div>
                        <div class="fortune-cards" id="fortuneCards"></div>
                        <div class="fortune-result" id="fortuneResult">
                            <p class="fortune-message" id="fortuneMessage">Выбери карту, чтобы узнать своё будущее</p>
                        </div>
                        <button class="btn-fortune-next" id="fortuneNextBtn" style="display:none;">🔮 Следующее</button>
                        <button class="btn-fortune-reset" onclick="initFortune()">🔄 Новый расклад</button>
                    </div>
                </div>

                <!-- ===== ПРЕДЛОГИ ===== -->
                <div class="tab-content" id="tab-prepositions">
                    <div class="prep-container" id="prepApp">
                        <div class="prep-header">
                            <span class="prep-counter" id="prepCounter">1 / 4</span>
                            <span class="prep-score" id="prepScore">✅ 0 | ❌ 0</span>
                        </div>
                        <div class="prep-question">
                            <span class="prep-sentence" id="prepSentence">Voy ___ cine</span>
                            <span class="prep-hint">— выбери правильный предлог</span>
                        </div>
                        <div class="prep-options" id="prepOptions"></div>
                        <div class="prep-feedback" id="prepFeedback"></div>
                        <button class="btn-prep-next" id="prepNextBtn" style="display:none;">Следующий →</button>
                        <button class="btn-prep-reset" onclick="initPrepositions()">🔄 Начать заново</button>
                    </div>
                </div>

                <!-- ===== R-КАРУСЕЛЬ ===== -->
                <div class="tab-content" id="tab-r-sound">
                    <div class="r-container" id="rApp">
                        <div class="r-header">
                            <span class="r-counter" id="rCounter">1 / 8</span>
                            <span class="r-score" id="rScore">✅ 0 | ❌ 0</span>
                        </div>
                        <div class="r-question">
                            <span class="r-word" id="rWord" onclick="playRSound()">perro</span>
                            <span class="r-hint">👆 Нажми на слово, чтобы услышать звук</span>
                        </div>
                        <div class="r-buttons">
                            <button class="btn-r-lang es" onclick="checkRLang('es')">🇪🇸 Испанский</button>
                            <button class="btn-r-lang pt" onclick="checkRLang('pt')">🇧🇷 Португальский</button>
                        </div>
                        <div class="r-feedback" id="rFeedback"></div>
                        <button class="btn-r-next" id="rNextBtn" style="display:none;">Следующее слово →</button>
                        <button class="btn-r-reset" onclick="initRCarousel()">🔄 Начать заново</button>
                    </div>
                </div>

                <!-- ===== НОСОВЫЕ ===== -->
                <div class="tab-content" id="tab-nasal">
                    <div class="nasal-container" id="nasalApp">
                        <div class="nasal-header">
                            <span class="nasal-counter" id="nasalCounter">1 / 6</span>
                            <span class="nasal-score" id="nasalScore">✅ 0 | ❌ 0</span>
                        </div>
                        <div class="nasal-question">
                            <span class="nasal-hint">🎧 Нажми на звук и выбери правильное написание</span>
                        </div>
                        <div class="nasal-controls">
                            <button class="btn-nasal-play" onclick="playNasalWord()">🔊 Прослушать</button>
                        </div>
                        <div class="nasal-options" id="nasalOptions"></div>
                        <div class="nasal-feedback" id="nasalFeedback"></div>
                        <button class="btn-nasal-next" id="nasalNextBtn" style="display:none;">Следующее слово →</button>
                        <button class="btn-nasal-reset" onclick="initNasal()">🔄 Начать заново</button>
                    </div>
                </div>

                <!-- ===== ДЕТЕКТИВ ===== -->
                <div class="tab-content" id="tab-detective">
                    <div class="detective-container" id="detectiveApp">
                        <div class="detective-header">
                            <span class="detective-counter" id="detectiveCounter">1 / 5</span>
                            <span class="detective-score" id="detectiveScore">✅ 0 | ❌ 0</span>
                        </div>
                        <div class="detective-badge">🕵️‍♀️ Найди правильный перевод</div>
                        <div class="detective-question">
                            <span class="detective-lang-label">🇪🇸 Испанский</span>
                            <div class="detective-original" id="detectiveOriginal">Voy a la playa con mis amigos.</div>
                            <span class="detective-arrow">⬇️</span>
                            <span class="detective-lang-label">🇧🇷 Португальский</span>
                            <div class="detective-hint" id="detectiveHint">Выбери правильный перевод</div>
                        </div>
                        <div class="detective-options" id="detectiveOptions"></div>
                        <div class="detective-feedback" id="detectiveFeedback"></div>
                        <div class="detective-tip" id="detectiveTip" style="display:none;"></div>
                        <button class="btn-detective-next" id="detectiveNextBtn" style="display:none;">🔍 Следующее расследование →</button>
                        <button class="btn-detective-reset" onclick="initDetective()">🔄 Начать заново</button>
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
        // ДАННЫЕ ДЛЯ ИГР (встроены прямо в файл)
        // ============================================================
        const fortuneData = [
            { emoji: '🌟', label: 'Удача', es: '¡Buena suerte!', pt: 'Boa sorte!', desc: 'Ты выбрала правильную форму — удача на твоей стороне!', isCorrect: true },
            { emoji: '📚', label: 'Учёба', es: 'Estudia más', pt: 'Estude mais', desc: 'Немного практики — и ты освоишь это правило!', isCorrect: false },
            { emoji: '💪', label: 'Сила', es: '¡Sigue así!', pt: 'Continue assim!', desc: 'Ты на правильном пути, продолжай в том же духе!', isCorrect: true },
            { emoji: '🌀', label: 'Путаница', es: 'No te confundas', pt: 'Não se confunda', desc: 'Осторожно с похожими словами! Они коварны.', isCorrect: false },
            { emoji: '🎯', label: 'Цель', es: '¡Lograrás tu meta!', pt: 'Você vai alcançar seu objetivo!', desc: 'Ты точно знаешь, чего хочешь — иди к своей цели!', isCorrect: true },
            { emoji: '🌈', label: 'Вдохновение', es: 'La inspiración te encuentra', pt: 'A inspiração te encontra', desc: 'Языки — это мост к новым мирам. Ты на верном пути!', isCorrect: true }
        ];

        const prepositions = [
            { sentence: 'Voy ___ cine', options: ['al', 'a la', 'en el', 'por'], correct: 'al' },
            { sentence: 'Estou ___ Brasil', options: ['no', 'em', 'na', 'ao'], correct: 'no' },
            { sentence: 'El libro está ___ mesa', options: ['en la', 'sobre la', 'de la', 'a la'], correct: 'sobre la' },
            { sentence: 'Vamos ___ praia', options: ['para a', 'na', 'em', 'à'], correct: 'para a' }
        ];

        const rSounds = [
            { word: 'perro', lang: 'es', hint: 'рычащая R (вибрация)' },
            { word: 'carro', lang: 'pt', hint: 'горловая R (как во французском)' },
            { word: 'rio', lang: 'pt', hint: 'горловая R' },
            { word: 'rojo', lang: 'es', hint: 'рычащая R' },
            { word: 'ferrocarril', lang: 'es', hint: 'сильная рычащая R' },
            { word: 'rua', lang: 'pt', hint: 'горловая R' },
            { word: 'rapaz', lang: 'pt', hint: 'горловая R' },
            { word: 'ratón', lang: 'es', hint: 'рычащая R' }
        ];

        const nasalData = [
            { word: 'pão', options: ['pao', 'pão', 'pam', 'pau'], correct: 'pão' },
            { word: 'mãe', options: ['mae', 'mãe', 'mai', 'mam'], correct: 'mãe' },
            { word: 'coração', options: ['coracao', 'coração', 'corason', 'corasão'], correct: 'coração' },
            { word: 'irmã', options: ['irma', 'irmã', 'irman', 'irmam'], correct: 'irmã' },
            { word: 'põe', options: ['poe', 'põe', 'poem', 'pom'], correct: 'põe' },
            { word: 'limão', options: ['limao', 'limão', 'limam', 'liman'], correct: 'limão' }
        ];

        const detectiveData = [
            {
                es: 'Voy a la playa con mis amigos.',
                pt: 'Vou para a praia com meus amigos.',
                options: [
                    'Vou para a praia com meus amigos.',
                    'Voy a la playa con mis amigos.',
                    'Vou à praia com meus amigos.',
                    'Vou para a praia com meus amigas.'
                ],
                hint: 'Обрати внимание на предлог (a / para) и род (amigos / amigas).',
                correctIndex: 0
            },
            {
                es: 'El gato negro está en el tejado.',
                pt: 'O gato preto está no telhado.',
                options: [
                    'O gato preto está no telhado.',
                    'El gato negro está en el tejado.',
                    'O gato preto está em o telhado.',
                    'O gato preto está no telhada.'
                ],
                hint: 'Проверь предлоги (no / em o) и род (telhado / telhada).',
                correctIndex: 0
            },
            {
                es: 'Mañana voy a Madrid en tren.',
                pt: 'Amanhã vou a Madrid de trem.',
                options: [
                    'Amanhã vou a Madrid de trem.',
                    'Mañana voy a Madrid en tren.',
                    'Amanhã vou para Madrid de trem.',
                    'Amanhã vou a Madrid de trem.'
                ],
                hint: 'В португальском для движения в город используется предлог a, а не para.',
                correctIndex: 0
            },
            {
                es: '¿Puedes ayudarme con mi tarea?',
                pt: 'Você pode me ajudar com minha tarefa?',
                options: [
                    'Você pode me ajudar com minha tarefa?',
                    '¿Puedes ayudarme con mi tarea?',
                    'Você pode ajudar-me com minha tarefa?',
                    'Você pode me ajudar com sua tarefa?'
                ],
                hint: 'Обрати внимание на позицию местоимения (me ajudar / ajudar-me) и притяжательное местоимение (minha / sua).',
                correctIndex: 0
            },
            {
                es: 'Estoy buscando un regalo para mi hermana.',
                pt: 'Estou procurando um presente para minha irmã.',
                options: [
                    'Estou procurando um presente para minha irmã.',
                    'Estoy buscando un regalo para mi hermana.',
                    'Estou procurando um presente para meu irmã.',
                    'Estou procurando um presente para minha irmã.'
                ],
                hint: 'Проверь род (irmã / irmão) и притяжательное местоимение (minha / meu).',
                correctIndex: 0
            }
        ];

        // ============================================================
        // ВСПОМОГАТЕЛЬНАЯ ФУНКЦИЯ
        // ============================================================
        function shuffleArray(arr) {
            for (let i = arr.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [arr[i], arr[j]] = [arr[j], arr[i]];
            }
            return arr;
        }

        // ============================================================
        // ТАБЫ
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
        // ГАДАЛКА
        // ============================================================
        let fortuneCards = [];
        let fortuneCount = 0;
        let isFortuneAnswered = false;

        function initFortune() {
            fortuneCount = 0;
            isFortuneAnswered = false;
            const shuffled = shuffleArray([...fortuneData]);
            fortuneCards = shuffled.slice(0, 3);
            document.getElementById('fortuneCount').textContent = fortuneCount;
            document.getElementById('fortuneMessage').textContent = 'Выбери карту, чтобы узнать своё будущее в изучении языков';
            document.getElementById('fortuneMessage').className = 'fortune-message';
            document.getElementById('fortuneNextBtn').style.display = 'none';
            renderFortuneCards();
        }

        function renderFortuneCards() {
            const container = document.getElementById('fortuneCards');
            container.innerHTML = '';
            fortuneCards.forEach((card, index) => {
                const div = document.createElement('div');
                div.className = 'fortune-card';
                div.dataset.index = index;
                div.innerHTML = `<div class="card-corner">✦</div><div class="card-back">🃏</div><div class="card-corner-bottom">✦</div>`;
                div.addEventListener('click', () => revealFortuneCard(index));
                container.appendChild(div);
            });
        }

        function revealFortuneCard(index) {
            if (isFortuneAnswered) return;
            isFortuneAnswered = true;
            const card = fortuneCards[index];
            const cardElement = document.querySelectorAll('.fortune-card')[index];
            cardElement.classList.add('revealed');
            cardElement.innerHTML = `<div class="card-corner">${card.emoji}</div><div><div class="card-emoji">${card.emoji}</div><div class="card-label">${card.label}</div><div class="card-desc">${card.desc}</div></div><div class="card-corner-bottom">${card.emoji}</div>`;
            const message = document.getElementById('fortuneMessage');
            if (card.isCorrect) {
                fortuneCount++;
                document.getElementById('fortuneCount').textContent = fortuneCount;
                message.innerHTML = `✨ <span class="highlight-es">${card.es}</span> / <span class="highlight-pt">${card.pt}</span><br><span style="font-size:16px; color:#888;">${card.desc}</span>`;
                message.className = 'fortune-message correct';
            } else {
                message.innerHTML = `😅 <span class="highlight-es">${card.es}</span> / <span class="highlight-pt">${card.pt}</span><br><span style="font-size:16px; color:#888;">${card.desc}</span><br><span style="font-size:14px; color:#B22222;">💡 Попробуй ещё раз!</span>`;
                message.className = 'fortune-message wrong';
            }
            document.getElementById('fortuneNextBtn').style.display = 'inline-block';
        }

        function nextFortune() {
            isFortuneAnswered = false;
            document.getElementById('fortuneNextBtn').style.display = 'none';
            const shuffled = shuffleArray([...fortuneData]);
            fortuneCards = shuffled.slice(0, 3);
            renderFortuneCards();
            document.getElementById('fortuneMessage').textContent = 'Выбери карту, чтобы узнать своё будущее';
            document.getElementById('fortuneMessage').className = 'fortune-message';
        }

        document.getElementById('fortuneNextBtn')?.addEventListener('click', nextFortune);

        // ============================================================
        // ПРЕДЛОГИ
        // ============================================================
        let prepData = [];
        let prepIndex = 0;
        let prepCorrect = 0;
        let prepWrong = 0;
        let isPrepAnswered = false;

        function initPrepositions() {
            prepData = shuffleArray([...prepositions]);
            prepIndex = 0;
            prepCorrect = 0;
            prepWrong = 0;
            isPrepAnswered = false;
            renderPrepQuestion();
        }

        function renderPrepQuestion() {
            if (prepIndex >= prepData.length) { showPrepResult(); return; }
            const item = prepData[prepIndex];
            document.getElementById('prepCounter').textContent = `${prepIndex + 1} / ${prepData.length}`;
            document.getElementById('prepScore').innerHTML = `✅ <span class="correct">${prepCorrect}</span> | ❌ <span class="wrong">${prepWrong}</span>`;
            document.getElementById('prepSentence').textContent = item.sentence;
            document.getElementById('prepFeedback').textContent = '';
            document.getElementById('prepFeedback').className = 'prep-feedback';
            document.getElementById('prepNextBtn').style.display = 'none';
            isPrepAnswered = false;
            const container = document.getElementById('prepOptions');
            container.innerHTML = '';
            const shuffledOptions = shuffleArray([...item.options]);
            shuffledOptions.forEach(opt => {
                const btn = document.createElement('button');
                btn.textContent = opt;
                btn.dataset.correct = (opt === item.correct) ? 'true' : 'false';
                btn.addEventListener('click', () => handlePrepAnswer(btn, item.correct));
                container.appendChild(btn);
            });
        }

        function handlePrepAnswer(btn, correct) {
            if (isPrepAnswered) return;
            isPrepAnswered = true;
            document.querySelectorAll('.prep-options button').forEach(b => b.classList.add('disabled'));
            const isCorrect = btn.dataset.correct === 'true';
            if (isCorrect) {
                btn.classList.add('correct');
                prepCorrect++;
                document.getElementById('prepFeedback').textContent = '✅ Правильно!';
                document.getElementById('prepFeedback').className = 'prep-feedback correct';
            } else {
                btn.classList.add('wrong');
                prepWrong++;
                document.getElementById('prepFeedback').textContent = `❌ Правильно: "${correct}"`;
                document.getElementById('prepFeedback').className = 'prep-feedback wrong';
                document.querySelectorAll('.prep-options button').forEach(b => { if (b.dataset.correct === 'true') b.classList.add('correct'); });
            }
            document.getElementById('prepScore').innerHTML = `✅ <span class="correct">${prepCorrect}</span> | ❌ <span class="wrong">${prepWrong}</span>`;
            document.getElementById('prepNextBtn').style.display = 'inline-block';
        }

        function showPrepResult() {
            const container = document.getElementById('prepApp');
            const total = prepCorrect + prepWrong;
            const percent = total > 0 ? Math.round((prepCorrect / total) * 100) : 0;
            let emoji, message;
            if (percent === 100) { emoji = '🏆'; message = 'Идеально! Ты знаешь предлоги!'; }
            else if (percent >= 80) { emoji = '🌟'; message = 'Отлично! Так держать!'; }
            else if (percent >= 60) { emoji = '💪'; message = 'Неплохо! Есть куда расти!'; }
            else { emoji = '📚'; message = 'Повтори предлоги и попробуй ещё раз!'; }
            container.innerHTML = `<div class="prep-result"><span class="emoji">${emoji}</span><div class="message">${message}</div><div class="stats">✅ ${prepCorrect} правильных | ❌ ${prepWrong} неправильных | 🎯 ${percent}%</div><button class="btn-prep-reset" onclick="initPrepositions()">🔄 Пройти ещё раз</button></div>`;
        }

        document.getElementById('prepNextBtn')?.addEventListener('click', () => { prepIndex++; renderPrepQuestion(); });

        // ============================================================
        // R-КАРУСЕЛЬ (С АУДИО)
        // ============================================================
        let rData = [];
        let rIndex = 0;
        let rCorrect = 0;
        let rWrong = 0;
        let isRAnswered = false;

        function initRCarousel() {
            rData = shuffleArray([...rSounds]);
            rIndex = 0;
            rCorrect = 0;
            rWrong = 0;
            isRAnswered = false;
            renderRQuestion();
        }

        function renderRQuestion() {
            if (rIndex >= rData.length) { showRResult(); return; }
            const item = rData[rIndex];
            document.getElementById('rCounter').textContent = `${rIndex + 1} / ${rData.length}`;
            document.getElementById('rScore').innerHTML = `✅ <span class="correct">${rCorrect}</span> | ❌ <span class="wrong">${rWrong}</span>`;
            document.getElementById('rWord').textContent = item.word;
            document.getElementById('rFeedback').textContent = '';
            document.getElementById('rFeedback').className = 'r-feedback';
            document.getElementById('rNextBtn').style.display = 'none';
            isRAnswered = false;
            document.querySelectorAll('.btn-r-lang').forEach(b => b.classList.remove('disabled', 'correct', 'wrong', 'reveal-correct'));
        }

        function playRSound() {
            const word = document.getElementById('rWord').textContent;
            const item = rData[rIndex];
            if (!item) return;
            const lang = item.lang === 'es' ? 'es-ES' : 'pt-BR';
            if (window.speechSynthesis) {
                const utterance = new SpeechSynthesisUtterance(word);
                utterance.lang = lang;
                utterance.rate = 0.7;
                utterance.pitch = 1;
                window.speechSynthesis.speak(utterance);
            }
        }

        function checkRLang(selectedLang) {
            if (isRAnswered) return;
            isRAnswered = true;
            const item = rData[rIndex];
            const isCorrect = selectedLang === item.lang;
            document.querySelectorAll('.btn-r-lang').forEach(b => b.classList.add('disabled'));
            const selectedBtn = document.querySelector(`.btn-r-lang.${selectedLang}`);
            if (isCorrect) {
                selectedBtn.classList.add('correct');
                rCorrect++;
                document.getElementById('rFeedback').textContent = `✅ Правильно! Это ${item.lang === 'es' ? 'испанская' : 'португальская'} R (${item.hint})`;
                document.getElementById('rFeedback').className = 'r-feedback correct';
            } else {
                selectedBtn.classList.add('wrong');
                rWrong++;
                document.getElementById('rFeedback').textContent = `❌ Неправильно. Это ${item.lang === 'es' ? 'испанская' : 'португальская'} R (${item.hint})`;
                document.getElementById('rFeedback').className = 'r-feedback wrong';
                const correctBtn = document.querySelector(`.btn-r-lang.${item.lang}`);
                if (correctBtn) correctBtn.classList.add('reveal-correct');
            }
            document.getElementById('rScore').innerHTML = `✅ <span class="correct">${rCorrect}</span> | ❌ <span class="wrong">${rWrong}</span>`;
            document.getElementById('rNextBtn').style.display = 'inline-block';
        }

        function showRResult() {
            const container = document.getElementById('rApp');
            const total = rCorrect + rWrong;
            const percent = total > 0 ? Math.round((rCorrect / total) * 100) : 0;
            let emoji, message;
            if (percent === 100) { emoji = '🏆'; message = 'Ты слышишь R как профи!'; }
            else if (percent >= 80) { emoji = '🌟'; message = 'Отличный слух! Так держать!'; }
            else if (percent >= 60) { emoji = '💪'; message = 'Неплохо! Тренируйся дальше!'; }
            else { emoji = '🎧'; message = 'Попробуй ещё раз, слух натренируется!'; }
            container.innerHTML = `<div class="r-result"><span class="emoji">${emoji}</span><div class="message">${message}</div><div class="stats">✅ ${rCorrect} правильных | ❌ ${rWrong} неправильных | 🎯 ${percent}%</div><button class="btn-r-reset" onclick="initRCarousel()">🔄 Пройти ещё раз</button></div>`;
        }

        document.getElementById('rNextBtn')?.addEventListener('click', () => { rIndex++; renderRQuestion(); });

        // ============================================================
        // НОСОВЫЕ
        // ============================================================
        let nasalWords = [];
        let nasalIndex = 0;
        let nasalCorrect = 0;
        let nasalWrong = 0;
        let isNasalAnswered = false;
        let currentNasalWord = null;

        function initNasal() {
            nasalWords = shuffleArray([...nasalData]);
            nasalIndex = 0;
            nasalCorrect = 0;
            nasalWrong = 0;
            isNasalAnswered = false;
            renderNasalQuestion();
        }

        function renderNasalQuestion() {
            if (nasalIndex >= nasalWords.length) { showNasalResult(); return; }
            currentNasalWord = nasalWords[nasalIndex];
            document.getElementById('nasalCounter').textContent = `${nasalIndex + 1} / ${nasalWords.length}`;
            document.getElementById('nasalScore').innerHTML = `✅ <span class="correct">${nasalCorrect}</span> | ❌ <span class="wrong">${nasalWrong}</span>`;
            document.getElementById('nasalFeedback').textContent = '';
            document.getElementById('nasalFeedback').className = 'nasal-feedback';
            document.getElementById('nasalNextBtn').style.display = 'none';
            isNasalAnswered = false;
            const container = document.getElementById('nasalOptions');
            container.innerHTML = '';
            const shuffledOptions = shuffleArray([...currentNasalWord.options]);
            shuffledOptions.forEach(opt => {
                const btn = document.createElement('button');
                btn.textContent = opt;
                btn.dataset.correct = (opt === currentNasalWord.correct) ? 'true' : 'false';
                btn.addEventListener('click', () => handleNasalAnswer(btn));
                container.appendChild(btn);
            });
        }

        function playNasalWord() {
            if (!currentNasalWord) return;
            const text = currentNasalWord.correct;
            if (window.speechSynthesis) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'pt-BR';
                utterance.rate = 0.7;
                utterance.pitch = 1;
                window.speechSynthesis.speak(utterance);
            } else {
                alert('Ваш браузер не поддерживает синтез речи.');
            }
        }

        function handleNasalAnswer(btn) {
            if (isNasalAnswered) return;
            isNasalAnswered = true;
            document.querySelectorAll('.nasal-options button').forEach(b => b.classList.add('disabled'));
            const isCorrect = btn.dataset.correct === 'true';
            if (isCorrect) {
                btn.classList.add('correct');
                nasalCorrect++;
                document.getElementById('nasalFeedback').textContent = '✅ Правильно! Отличный слух!';
                document.getElementById('nasalFeedback').className = 'nasal-feedback correct';
            } else {
                btn.classList.add('wrong');
                nasalWrong++;
                document.getElementById('nasalFeedback').textContent = `❌ Неправильно. Правильное написание: "${currentNasalWord.correct}"`;
                document.getElementById('nasalFeedback').className = 'nasal-feedback wrong';
                document.querySelectorAll('.nasal-options button').forEach(b => { if (b.dataset.correct === 'true') b.classList.add('correct'); });
            }
            document.getElementById('nasalScore').innerHTML = `✅ <span class="correct">${nasalCorrect}</span> | ❌ <span class="wrong">${nasalWrong}</span>`;
            document.getElementById('nasalNextBtn').style.display = 'inline-block';
        }

        function showNasalResult() {
            const container = document.getElementById('nasalApp');
            const total = nasalCorrect + nasalWrong;
            const percent = total > 0 ? Math.round((nasalCorrect / total) * 100) : 0;
            let emoji, message;
            if (percent === 100) { emoji = '🏆'; message = 'Идеально! Ты слышишь все носовые!'; }
            else if (percent >= 80) { emoji = '🌟'; message = 'Отличный слух! Так держать!'; }
            else if (percent >= 60) { emoji = '💪'; message = 'Неплохо! Тренируйся дальше!'; }
            else { emoji = '👃'; message = 'Попробуй ещё раз, носовые звуки — это сложно!'; }
            container.innerHTML = `<div class="nasal-result"><span class="emoji">${emoji}</span><div class="message">${message}</div><div class="stats">✅ ${nasalCorrect} правильных | ❌ ${nasalWrong} неправильных | 🎯 ${percent}%</div><button class="btn-nasal-reset" onclick="initNasal()">🔄 Пройти ещё раз</button></div>`;
        }

        document.getElementById('nasalNextBtn')?.addEventListener('click', () => { nasalIndex++; renderNasalQuestion(); });

        // ============================================================
        // ДЕТЕКТИВ
        // ============================================================
        let detectiveDataArr = [];
        let detectiveIndex = 0;
        let detectiveCorrect = 0;
        let detectiveWrong = 0;
        let isDetectiveAnswered = false;

        function initDetective() {
            detectiveDataArr = shuffleArray([...detectiveData]);
            detectiveIndex = 0;
            detectiveCorrect = 0;
            detectiveWrong = 0;
            isDetectiveAnswered = false;
            renderDetectiveQuestion();
        }

        function renderDetectiveQuestion() {
            if (detectiveIndex >= detectiveDataArr.length) {
                showDetectiveResult();
                return;
            }

            const item = detectiveDataArr[detectiveIndex];
            document.getElementById('detectiveCounter').textContent = `${detectiveIndex + 1} / ${detectiveDataArr.length}`;
            document.getElementById('detectiveScore').innerHTML = `✅ <span class="correct">${detectiveCorrect}</span> | ❌ <span class="wrong">${detectiveWrong}</span>`;
            document.getElementById('detectiveOriginal').textContent = item.es;
            document.getElementById('detectiveHint').textContent = 'Выбери правильный перевод на португальский';
            document.getElementById('detectiveFeedback').textContent = '';
            document.getElementById('detectiveFeedback').className = 'detective-feedback';
            document.getElementById('detectiveTip').style.display = 'none';
            document.getElementById('detectiveNextBtn').style.display = 'none';
            isDetectiveAnswered = false;

            const container = document.getElementById('detectiveOptions');
            container.innerHTML = '';
            const shuffledOptions = shuffleArray([...item.options]);
            shuffledOptions.forEach((opt) => {
                const btn = document.createElement('button');
                btn.textContent = opt;
                btn.dataset.correct = (opt === item.options[item.correctIndex]) ? 'true' : 'false';
                btn.addEventListener('click', () => handleDetectiveAnswer(btn, item));
                container.appendChild(btn);
            });
        }

        function handleDetectiveAnswer(btn, item) {
            if (isDetectiveAnswered) return;
            isDetectiveAnswered = true;

            document.querySelectorAll('.detective-options button').forEach(b => b.classList.add('disabled'));

            const isCorrect = btn.dataset.correct === 'true';
            if (isCorrect) {
                btn.classList.add('correct');
                detectiveCorrect++;
                document.getElementById('detectiveFeedback').textContent = '✅ Правильно! Ты настоящий детектив! 🕵️‍♀️';
                document.getElementById('detectiveFeedback').className = 'detective-feedback correct';
            } else {
                btn.classList.add('wrong');
                detectiveWrong++;
                document.getElementById('detectiveFeedback').textContent = '❌ Неправильно. Правильный перевод:';
                document.getElementById('detectiveFeedback').className = 'detective-feedback wrong';
                document.querySelectorAll('.detective-options button').forEach(b => {
                    if (b.dataset.correct === 'true') b.classList.add('correct');
                });
                const tip = document.getElementById('detectiveTip');
                tip.textContent = `💡 Подсказка: ${item.hint}`;
                tip.style.display = 'block';
            }

            document.getElementById('detectiveScore').innerHTML = `✅ <span class="correct">${detectiveCorrect}</span> | ❌ <span class="wrong">${detectiveWrong}</span>`;
            document.getElementById('detectiveNextBtn').style.display = 'inline-block';
        }

        function showDetectiveResult() {
            const container = document.getElementById('detectiveApp');
            const total = detectiveCorrect + detectiveWrong;
            const percent = total > 0 ? Math.round((detectiveCorrect / total) * 100) : 0;

            let emoji, message;
            if (percent === 100) { emoji = '🏆'; message = 'Ты — супер-детектив! Все переводы найдены!'; }
            else if (percent >= 80) { emoji = '🌟'; message = 'Отличная работа! Ты хорошо разбираешься в переводах!'; }
            else if (percent >= 60) { emoji = '💪'; message = 'Неплохо! Есть куда расти!'; }
            else { emoji = '📚'; message = 'Попробуй ещё раз, внимательнее к деталям!'; }

            container.innerHTML = `
                <div class="detective-result">
                    <span class="emoji">${emoji}</span>
                    <div class="message">${message}</div>
                    <div class="stats">✅ ${detectiveCorrect} правильных | ❌ ${detectiveWrong} неправильных | 🎯 ${percent}%</div>
                    <button class="btn-detective-reset" onclick="initDetective()" style="margin:0 auto;">🔄 Начать новое расследование</button>
                </div>
            `;
        }

        document.getElementById('detectiveNextBtn')?.addEventListener('click', () => {
            detectiveIndex++;
            renderDetectiveQuestion();
        });

        // ============================================================
        // ЗАПУСК ВСЕХ ИГР
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            initFortune();
            initPrepositions();
            initRCarousel();
            initNasal();
            initDetective();
        });
    </script>
</body>
</html>