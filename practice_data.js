// ============================================================
// 1. СЛОВА ДЛЯ КАРТОЧЕК И ТЕСТОВ
// ============================================================
const words = [
    { es: 'pan', pt: 'pão', ru: 'хлеб' },
    { es: 'vino', pt: 'vinho', ru: 'вино' },
    { es: 'playa', pt: 'praia', ru: 'пляж' },
    { es: 'madre', pt: 'mãe', ru: 'мама' },
    { es: 'hablar', pt: 'falar', ru: 'говорить' },
    { es: 'sol', pt: 'sol', ru: 'солнце' },
    { es: 'rojo', pt: 'vermelho', ru: 'красный' },
    { es: 'hola', pt: 'olá', ru: 'привет' },
    { es: 'médico', pt: 'médico', ru: 'врач' },
    { es: 'casa', pt: 'casa', ru: 'дом' }
];

// ============================================================
// 2. ПРЕДЛОЖЕНИЯ ДЛЯ "СОБЕРИ ПРЕДЛОЖЕНИЕ"
// ============================================================
const sentences = [
    { es: 'Yo quiero comer pan', pt: 'Eu quero comer pão' },
    { es: 'Ella vive en Madrid', pt: 'Ela vive em Madrid' },
    { es: 'Vamos a la playa', pt: 'Vamos para a praia' }
];

// ============================================================
// 3. ТЕКСТЫ ДЛЯ "ПОЙМАЙ ШПИОНА" (ЕСЛИ НУЖНО)
// ============================================================
const spyTexts = [
    {
        lang: 'pt',
        text: 'Hoje eu vou a la praia com meus amigos. O sol está muy fuerte. Vamos nadar e comer pão com vino.',
        spies: ['a la', 'muy', 'fuerte', 'vino']
    }
];

// ============================================================
// 4. ПРЕДЛОГИ (ТОЛЬКО ОДИН РАЗ!)
// ============================================================
const prepositions = [
    { sentence: 'Voy ___ cine', options: ['al', 'a la', 'en el', 'por'], correct: 'al' },
    { sentence: 'Estou ___ Brasil', options: ['no', 'em', 'na', 'ao'], correct: 'no' },
    { sentence: 'El libro está ___ mesa', options: ['en la', 'sobre la', 'de la', 'a la'], correct: 'sobre la' },
    { sentence: 'Vamos ___ praia', options: ['para a', 'na', 'em', 'à'], correct: 'para a' }
];

// ============================================================
// 5. НОСОВЫЕ ЗВУКИ (ТОЛЬКО ОДИН РАЗ!)
// ============================================================
const nasalSounds = [
    { word: 'pão', options: ['pao', 'pão', 'pam', 'pau'], correct: 'pão' },
    { word: 'mãe', options: ['mae', 'mãe', 'mai', 'mam'], correct: 'mãe' },
    { word: 'coração', options: ['coracao', 'coração', 'corason', 'corasão'], correct: 'coração' },
    { word: 'irmã', options: ['irma', 'irmã', 'irman', 'irmam'], correct: 'irmã' },
    { word: 'põe', options: ['poe', 'põe', 'poem', 'pom'], correct: 'põe' },
    { word: 'limão', options: ['limao', 'limão', 'limam', 'liman'], correct: 'limão' }
];

// ============================================================
// 6. R-КАРУСЕЛЬ (ТОЛЬКО ОДИН РАЗ!)
// ============================================================
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

// ============================================================
// 7. ДАННЫЕ ДЛЯ ГАДАЛКИ
// ============================================================
const fortuneData = [
    { emoji: '🌟', label: 'Удача', es: '¡Buena suerte!', pt: 'Boa sorte!', desc: 'Ты выбрала правильную форму — удача на твоей стороне!', isCorrect: true },
    { emoji: '📚', label: 'Учёба', es: 'Estudia más', pt: 'Estude mais', desc: 'Немного практики — и ты освоишь это правило!', isCorrect: false },
    { emoji: '💪', label: 'Сила', es: '¡Sigue así!', pt: 'Continue assim!', desc: 'Ты на правильном пути, продолжай в том же духе!', isCorrect: true },
    { emoji: '🌀', label: 'Путаница', es: 'No te confundas', pt: 'Não se confunda', desc: 'Осторожно с похожими словами! Они коварны.', isCorrect: false },
    { emoji: '🎯', label: 'Цель', es: '¡Lograrás tu meta!', pt: 'Você vai alcançar seu objetivo!', desc: 'Ты точно знаешь, чего хочешь — иди к своей цели!', isCorrect: true },
    { emoji: '🌈', label: 'Вдохновение', es: 'La inspiración te encuentra', pt: 'A inspiração te encontra', desc: 'Языки — это мост к новым мирам. Ты на верном пути!', isCorrect: true }
];

// ============================================================
// ДАННЫЕ ДЛЯ "ПЕРЕВОДЧИК-ДЕТЕКТИВ"
// ============================================================
const detectiveData = [
    {
        es: 'Voy a la playa con mis amigos.',
        pt: 'Vou para a praia com meus amigos.',
        // Варианты перевода (испанский → португальский)
        options: [
            'Vou para a praia com meus amigos.', // правильный
            'Voy a la playa con mis amigos.', // не переведено
            'Vou à praia com meus amigos.', // ошибка в предлоге
            'Vou para a praia com meus amigas.' // ошибка в роде
        ],
        hint: 'Обрати внимание на предлог (a / para) и род существительного (amigos / amigas).',
        correctIndex: 0
    },
    {
        es: 'El gato negro está en el tejado.',
        pt: 'O gato preto está no telhado.',
        options: [
            'O gato preto está no telhado.', // правильный
            'El gato negro está en el tejado.', // не переведено
            'O gato preto está em o telhado.', // ошибка в предлоге
            'O gato preto está no telhada.' // ошибка в роде
        ],
        hint: 'Проверь предлоги (no / em o) и род слова telhado (мужской род).',
        correctIndex: 0
    },
    {
        es: 'Mañana voy a Madrid en tren.',
        pt: 'Amanhã vou a Madrid de trem.',
        options: [
            'Amanhã vou a Madrid de trem.', // правильный
            'Mañana voy a Madrid en tren.', // не переведено
            'Amanhã vou para Madrid de trem.', // ошибка в предлоге (para вместо a)
            'Amanhã vou a Madrid de tren.' // правильный, но это дубликат (не должен быть в вариантах)
        ],
        hint: 'В португальском для движения в город используется предлог a, а не para.',
        correctIndex: 0
    },
    {
        es: '¿Puedes ayudarme con mi tarea?',
        pt: 'Você pode me ajudar com minha tarefa?',
        options: [
            'Você pode me ajudar com minha tarefa?', // правильный
            '¿Puedes ayudarme con mi tarea?', // не переведено
            'Você pode ajudar-me com minha tarefa?', // местоимение не на месте
            'Você pode me ajudar com sua tarefa?' // ошибка в притяжательном местоимении
        ],
        hint: 'Обрати внимание на позицию местоимения (me ajudar / ajudar-me) и притяжательное местоимение (minha / sua).',
        correctIndex: 0
    },
    {
        es: 'Estoy buscando un regalo para mi hermana.',
        pt: 'Estou procurando um presente para minha irmã.',
        options: [
            'Estou procurando um presente para minha irmã.', // правильный
            'Estoy buscando un regalo para mi hermana.', // не переведено
            'Estou procurando um presente para meu irmã.', // ошибка в роде
            'Estou procurando um presente para minha irmã.' // дубликат (не должен быть в вариантах)
        ],
        hint: 'Проверь род слова irmã (женский) и притяжательное местоимение (minha / meu).',
        correctIndex: 0
    }
];