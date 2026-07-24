// ============================================================
// 1. ЦИТАТА ДНЯ (только на главной странице)
// ============================================================
const dailyQuoteText = document.getElementById('dailyQuoteText');
const dailyQuoteAuthor = document.getElementById('dailyQuoteAuthor');
const refreshBtn = document.getElementById('refreshQuote');

const dailyQuotes = [
    { text: '"Quien no habla, no vive." — "Quem não fala, não vive."', author: '— Испанская и португальская поговорка' },
    { text: '"El que siembra, recoge." — "Quem planta, colhe."', author: '— Пословица' },
    { text: '"La práctica hace al maestro." — "A prática leva à perfeição."', author: '— Пословица' },
    { text: '"No hay mal que por bien no venga." — "Não há mal que venha por bem."', author: '— Пословица' },
    { text: '"Hasta la vista, baby." — "Até logo, baby."', author: '— Терминатор (шутка)' }
];

function setDailyQuote() {
    if (!dailyQuoteText || !dailyQuoteAuthor) return;
    const random = dailyQuotes[Math.floor(Math.random() * dailyQuotes.length)];
    dailyQuoteText.textContent = random.text;
    dailyQuoteAuthor.textContent = random.author;
}

if (refreshBtn) {
    refreshBtn.addEventListener('click', setDailyQuote);
}

// ============================================================
// 2. КАРУСЕЛЬ ЦИТАТ (только на главной странице)
// ============================================================
const carousel = document.getElementById('quotesCarousel');
const prevBtn = document.getElementById('prevQuote');
const nextBtn = document.getElementById('nextQuote');

const quotesData = [
    { text: '«Изучить другой язык — значит обрести вторую душу.» / «Aprender otra lengua es tener una segunda alma.»', author: '— Карл Великий' },
    { text: '«Тот, кто говорит на двух языках, стоит двоих.» / «Quem fala duas línguas vale por dois.»', author: '— Поговорка' },
    { text: '«Nevazhno, skolko raz ti upal. Vazhno, skolko raz ti podnyalsya.»', author: 'Майя Смирнова, 2026 до нашей эры' },
    { text: '«Границы моего языка — это границы моего мира.» / «Los límites de mi lengua son los límites de mi mundo.»', author: '— Людвиг Витгенштейн' },
    { text: '«С каждым новым языком ты проживаешь новую жизнь.» / «Con cada nuevo idioma, vives una nueva vida.»', author: '— Чешская поговорка' }
];

let currentIndex = 0;

function renderQuotes() {
    if (!carousel) return;
    carousel.innerHTML = '';
    const visibleQuotes = quotesData.slice(currentIndex, currentIndex + 3);
    visibleQuotes.forEach(q => {
        const card = document.createElement('div');
        card.className = 'quote-card';
        card.innerHTML = `
            <blockquote>${q.text}</blockquote>
            <cite>${q.author}</cite>
        `;
        carousel.appendChild(card);
    });
}

if (nextBtn && prevBtn) {
    nextBtn.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % Math.max(quotesData.length - 2, 1);
        renderQuotes();
    });
    prevBtn.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + Math.max(quotesData.length - 2, 1)) % Math.max(quotesData.length - 2, 1);
        renderQuotes();
    });
}

// ============================================================
// 3. АНИМАЦИЯ ПОЯВЛЕНИЯ (для всех страниц)
// ============================================================
const animateElements = document.querySelectorAll('.animate-on-scroll');
if (animateElements.length > 0) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -50px 0px' });
    animateElements.forEach(el => observer.observe(el));
}

// ============================================================
// 4. КНОПКА "НАВЕРХ" (для всех страниц)
// ============================================================
const backToTopBtn = document.getElementById('backToTop');
if (backToTopBtn) {
    window.addEventListener('scroll', () => {
        backToTopBtn.classList.toggle('show', window.scrollY > 400);
    });
    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

// ============================================================
// 5. ЗАГРУЗКА ПРИ СТАРТЕ (для цитат на главной)
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    setDailyQuote();
    renderQuotes();
});

// ============================================================
// 6. ЛОГИКА ДЛЯ СТРАНИЦ ЯЗЫКОВ (ТЕМЫ, БИЛИНГВА, ИЗБРАННОЕ)
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    // ===== 6.1. ГОРИЗОНТАЛЬНЫЙ СКРОЛЛ ТЕМ =====
    const scrollContainer = document.getElementById('themeScroll');
    const scrollLeftBtn = document.getElementById('scrollLeft');
    const scrollRightBtn = document.getElementById('scrollRight');

    if (scrollContainer && scrollLeftBtn && scrollRightBtn) {
        function updateScrollButtons() {
            const maxScroll = scrollContainer.scrollWidth - scrollContainer.clientWidth;
            scrollLeftBtn.disabled = scrollContainer.scrollLeft <= 0;
            scrollRightBtn.disabled = scrollContainer.scrollLeft >= maxScroll - 1;
        }
        scrollLeftBtn.addEventListener('click', () => {
            scrollContainer.scrollBy({ left: -200, behavior: 'smooth' });
            setTimeout(updateScrollButtons, 300);
        });
        scrollRightBtn.addEventListener('click', () => {
            scrollContainer.scrollBy({ left: 200, behavior: 'smooth' });
            setTimeout(updateScrollButtons, 300);
        });
        scrollContainer.addEventListener('scroll', updateScrollButtons);
        window.addEventListener('resize', updateScrollButtons);
        setTimeout(updateScrollButtons, 100);
    }

    // ===== 6.2. ПЕРЕКЛЮЧЕНИЕ ТЕМ =====
    const themeChips = document.querySelectorAll('.theme-chip');
    const wordCards = document.querySelectorAll('.word-card');

    if (themeChips.length > 0 && wordCards.length > 0) {
        themeChips.forEach(chip => {
            chip.addEventListener('click', function() {
                themeChips.forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                const theme = this.dataset.theme;
                wordCards.forEach(card => {
                    card.style.display = (theme === 'all' || card.dataset.theme === theme) ? 'block' : 'none';
                });
            });
        });
    }

    // ===== 6.3. РЕЖИМ БИЛИНГВА =====
    const bilingBtns = document.querySelectorAll('.biling-btn');
    if (bilingBtns.length > 0) {
        bilingBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                bilingBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const mode = this.dataset.mode;
                document.querySelectorAll('.word-bilingue').forEach(block => {
                    block.style.display = (mode === 'bi') ? 'flex' : 'none';
                });
            });
        });
    }

    // ===== 6.4. ИЗБРАННОЕ (ДОБАВЛЕНИЕ) =====
    const favBtns = document.querySelectorAll('.fav-btn');

    favBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            
            const wordId = this.dataset.wordId;
            console.log('🔍 Клик по избранному, wordId:', wordId);

            if (!wordId) {
                alert('Ошибка: ID слова не найден.');
                return;
            }

            fetch('check_auth.php')
                .then(response => response.json())
                .then(authData => {
                    console.log('🔍 Ответ check_auth.php:', authData);
                    
                    if (!authData.logged_in) {
                        if (confirm('Чтобы добавлять слова в избранное, нужно войти в профиль. Перейти на страницу входа?')) {
                            window.location.href = 'login.html';
                        }
                        return;
                    }

                    const formData = new FormData();
                    formData.append('word_id', wordId);

                    fetch('add_favorite.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('🔍 Ответ add_favorite.php:', data);
                        
                        if (data.success) {
                            if (data.action === 'added') {
                                this.textContent = '❤️';
                                this.classList.add('active');
                            } else if (data.action === 'removed') {
                                this.textContent = '♡';
                                this.classList.remove('active');
                            }
                        } else {
                            alert('Ошибка: ' + data.error);
                        }
                    })
                    .catch(error => {
                        alert('Ошибка соединения: ' + error);
                    });
                })
                .catch(error => {
                    alert('Ошибка проверки авторизации: ' + error);
                });
        });
    });
});

// ============================================================
// 7. УДАЛЕНИЕ ИЗ ИЗБРАННОГО (для profile.php)
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 Загрузка удаления из избранного');
    
    const favRemoveBtns = document.querySelectorAll('.fav-remove');
    console.log('🔍 Найдено кнопок удаления:', favRemoveBtns.length);
    
    favRemoveBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const wordId = this.dataset.wordId;
            console.log('🔍 Клик по удалению, wordId:', wordId);
            
            if (!wordId) {
                alert('Ошибка: ID слова не найден');
                return;
            }

            if (!confirm('Удалить это слово из избранного?')) return;

            const formData = new FormData();
            formData.append('word_id', wordId);

            fetch('remove_favorite.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('🔍 Ответ remove_favorite.php:', data);
                
                if (data.success) {
                    const favItem = this.closest('.fav-item');
                    if (favItem) {
                        favItem.remove();
                    }
                    
                    const remainingItems = document.querySelectorAll('.fav-item');
                    if (remainingItems.length === 0) {
                        const favList = document.getElementById('favList');
                        if (favList) {
                            favList.innerHTML = `
                                <div class="fav-empty">
                                    😢 У тебя пока нет избранных слов<br>
                                    <a href="spanish.html">🇪🇸 Добавь в испанском</a> или <a href="portugues.html">🇧🇷 в португальском</a>
                                </div>
                            `;
                        }
                    }
                    
                    updateCounters();
                } else {
                    alert('Ошибка при удалении: ' + data.error);
                }
            })
            .catch(error => {
                alert('Ошибка соединения: ' + error);
                console.error('❌ Fetch error:', error);
            });
        });
    });
});

// ============================================================
// 8. ОБНОВЛЕНИЕ СЧЁТЧИКОВ (для profile.php)
// ============================================================
function updateCounters() {
    console.log('🔍 Обновление счётчиков');
    
    const esWords = document.querySelectorAll('.fav-item:not(.pt-fav)').length;
    const ptWords = document.querySelectorAll('.fav-item.pt-fav').length;
    
    const esCircle = document.querySelector('.progress-circle.es');
    const ptCircle = document.querySelector('.progress-circle.pt');
    
    if (esCircle) esCircle.textContent = esWords;
    if (ptCircle) ptCircle.textContent = ptWords;
    
    const esBar = document.querySelector('.progress-bar-fill.es');
    const ptBar = document.querySelector('.progress-bar-fill.pt');
    
    if (esBar) esBar.style.width = Math.min(esWords * 10, 100) + '%';
    if (ptBar) ptBar.style.width = Math.min(ptWords * 10, 100) + '%';
}
// ============================================================
// 9. КОЛЕСО ФОРТУНЫ (для profile.php)
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    const wheel = document.getElementById('wheel');
    const spinBtn = document.getElementById('spinBtn');
    const taskDisplay = document.getElementById('wheelTask');

    // Проверяем, есть ли элементы колеса на странице
    if (!wheel || !spinBtn || !taskDisplay) {
        console.log('⚠️ Колесо фортуны не найдено на этой странице');
        return;
    }

    console.log('🎡 Колесо фортуны загружено!');

    const tasks = [
        '📚 Выучи 5 новых слов по теме "Еда"',
        '📖 Повтори спряжение глаголов настоящего времени',
        '🎧 Послушай произношение 3 слов и повтори',
        '✍️ Напиши 3 предложения с новыми словами',
        '🔄 Сравни 5 слов на испанском и португальском',
        '🎯 Пройди тест на 5 вопросов',
        '📝 Запиши 5 новых слов в тетрадь',
        '🎤 Прочитай вслух 3 любых слова из словаря'
    ];

    let isSpinning = false;
    let currentRotation = 0;

    spinBtn.addEventListener('click', function() {
        if (isSpinning) return;

        isSpinning = true;
        spinBtn.disabled = true;
        taskDisplay.textContent = '🔄 Крутим...';

        // Случайный поворот (минимум 5 полных оборотов + случайный угол)
        const extraSpins = 5 + Math.random() * 5;
        const randomDegree = Math.floor(Math.random() * 360);
        const totalRotation = extraSpins * 360 + randomDegree;
        
        currentRotation += totalRotation;
        wheel.style.transform = `rotate(${currentRotation}deg)`;

        // Определяем задание по углу (через 4.2 секунды, когда колесо остановится)
        setTimeout(() => {
            // Выбираем случайное задание (не привязано к углу для простоты)
            const taskIndex = Math.floor(Math.random() * tasks.length);
            const task = tasks[taskIndex];
            taskDisplay.textContent = '🎯 ' + task;

            isSpinning = false;
            spinBtn.disabled = false;
        }, 4200);
    });
});
// ============================================================
// 10. ТАБЫ НА СТРАНИЦЕ СРАВНЕНИЯ
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.compare-tab');
    const contents = document.querySelectorAll('.tab-content');

    if (tabs.length === 0) return;

    console.log('🔍 Найдено табов:', tabs.length);

    // Функция для переключения вкладок
    function switchTab(tabId) {
        // Скрываем все вкладки
        contents.forEach(content => {
            content.classList.remove('active');
        });

        // Показываем нужную
        const targetContent = document.getElementById('tab-' + tabId);
        if (targetContent) {
            targetContent.classList.add('active');
            console.log('✅ Показана вкладка:', tabId);
        } else {
            console.error('❌ Контент не найден для:', tabId);
        }

        // Обновляем активный таб
        tabs.forEach(tab => {
            tab.classList.remove('active');
            if (tab.dataset.tab === tabId) {
                tab.classList.add('active');
            }
        });
    }

    // Навешиваем события на кнопки
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const tabId = this.dataset.tab;
            if (tabId) {
                switchTab(tabId);
            }
        });
    });

    // Показываем активную вкладку по умолчанию
    const activeTab = document.querySelector('.compare-tab.active');
    if (activeTab) {
        const defaultTab = activeTab.dataset.tab;
        if (defaultTab) {
            switchTab(defaultTab);
        }
    } else {
        // Если нет активной — показываем первую
        const firstTab = tabs[0];
        if (firstTab) {
            switchTab(firstTab.dataset.tab);
        }
    }
});

function playSound(text, lang = 'es-ES') {
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.lang = lang; // 'es-ES' или 'pt-BR'
    utterance.rate = 0.9; // скорость (0.9 - чуть медленнее)
    window.speechSynthesis.speak(utterance);
}
// ============================================================
// КУЛЬТУРНЫЙ ГИД (ДАННЫЕ)
// ============================================================
const cultureData = {
    es: {
        books: [
            { title: 'La sombra del viento', author: 'Carlos Ruiz Zafón', desc: 'Мистический роман о любви и книгах в послевоенной Барселоне. Отличный язык для уровня B1+.', tag: 'B1+' },
            { title: 'El principito', author: 'Antoine de Saint-Exupéry', desc: 'Классика, переведённая на множество языков. Простой и красивый испанский.', tag: 'A2' },
            { title: 'Cuentos de la selva', author: 'Horacio Quiroga', desc: 'Короткие рассказы о животных и природе. Идеально для начинающих.', tag: 'A2' }
        ],
        movies: [
            { title: 'Mar adentro', author: '2004, драма', desc: 'Фильм о праве на жизнь и смерть. Язык ясный, много философских диалогов.', tag: 'B1' },
            { title: 'La sociedad de la nieve', author: '2023, драма', desc: 'Реальная история выживания в Андах. Современный испанский.', tag: 'B1+' },
            { title: 'Coco', author: '2017, мультфильм', desc: 'Яркий, эмоциональный, с лёгким испанским языком. Подходит для A2-B1.', tag: 'A2' }
        ],
        podcasts: [
            { title: 'Hoy hablamos', author: 'RTVE', desc: 'Подкаст для изучающих испанский. Медленная и чёткая речь, разные темы.', tag: 'A2-B1' },
            { title: 'Radio Ambulante', author: 'NPR', desc: 'Реальные истории из Латинской Америки. Сложнее, но очень интересно.', tag: 'B1+' },
            { title: 'Entiende tu mente', author: 'Психология', desc: 'Подкаст о психологии на ясном испанском. Помогает понять живую речь.', tag: 'B1' }
        ]
    },
    pt: {
        books: [
            { title: 'O Alquimista', author: 'Paulo Coelho', desc: 'Символическая история о поиске своего пути. Лёгкий португальский.', tag: 'A2' },
            { title: 'Dom Casmurro', author: 'Machado de Assis', desc: 'Классическая бразильская литература. Язык сложнее, для уровня B2.', tag: 'B2' },
            { title: 'O pequeno príncipe', author: 'Antoine de Saint-Exupéry', desc: 'Знаменитая книга на португальском. Простой и красивый язык.', tag: 'A2' }
        ],
        movies: [
            { title: 'Cidade de Deus', author: '2002, драма', desc: 'Культовый фильм о жизни в фавелах. Очень живая речь.', tag: 'B1+' },
            { title: 'O Auto da Compadecida', author: '2000, комедия', desc: 'Яркий, смешной фильм с бразильским колоритом.', tag: 'B1' },
            { title: 'Rio', author: '2011, мультфильм', desc: 'Красочный мультфильм, лёгкий для восприятия.', tag: 'A2' }
        ],
        podcasts: [
            { title: 'Fala Gringo', author: 'Бразильский подкаст', desc: 'Подкаст для изучающих португальский. Чёткая речь, разнообразные темы.', tag: 'A2-B1' },
            { title: 'Café com leite', author: 'Культурный подкаст', desc: 'Разговоры о культуре и жизни в Бразилии.', tag: 'B1' },
            { title: 'Anticast', author: 'Интервью', desc: 'Глубокие разговоры с интересными людьми. Сложнее, но очень полезно.', tag: 'B1+' }
        ]
    }
};

// ============================================================
// РЕНДЕРИНГ КУЛЬТУРНОГО ГИДА
// ============================================================
let currentCultureLang = 'es';
let currentCultureCategory = 'books';

function renderCultureGuide() {
    const container = document.getElementById('cultureCards');
    const data = cultureData[currentCultureLang][currentCultureCategory];
    
    if (!data || data.length === 0) {
        container.innerHTML = `<div class="culture-empty">😢 Пока нет рекомендаций для этой категории</div>`;
        return;
    }

    const isPortuguese = currentCultureLang === 'pt';
    container.innerHTML = data.map(item => `
        <div class="culture-card ${isPortuguese ? 'pt-card' : ''}">
            <div class="card-emoji">${isPortuguese ? '🇧🇷' : '🇪🇸'}</div>
            <div class="card-title">${item.title}</div>
            <div class="card-author">${item.author}</div>
            <div class="card-desc">${item.desc}</div>
            <span class="card-tag">${item.tag}</span>
        </div>
    `).join('');
}

// ============================================================
// ПЕРЕКЛЮЧЕНИЕ ВКЛАДОК
// ============================================================
// Переключение языка
document.querySelectorAll('.culture-lang-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.culture-lang-tab').forEach(t => t.classList.remove('active', 'pt-active'));
        this.classList.add('active');
        if (this.dataset.lang === 'pt') {
            this.classList.add('pt-active');
        }
        currentCultureLang = this.dataset.lang;
        renderCultureGuide();
    });
});

// Переключение категорий
document.querySelectorAll('.culture-category-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.culture-category-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        currentCultureCategory = this.dataset.category;
        renderCultureGuide();
    });
});

// Загружаем при старте
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('cultureCards')) {
        renderCultureGuide();
    }
});