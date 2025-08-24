import './bootstrap';

// Early dark-mode setup to avoid flash of incorrect theme
(() => {
  try {
    const stored = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = stored || (prefersDark ? 'dark' : 'light');
    document.documentElement.classList.toggle('dark', theme === 'dark');
  } catch (e) {}
})();

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Global UI store: theme + language + translations
Alpine.store('ui', (() => {
  const dict = {
    en: {
      home: 'Home',
      regions: 'Regions',
      about: 'About',
      contact: 'Contact',
      faq: 'FAQ',
      login: 'Login',
      get_started: 'Get Started',
      dashboard: 'Dashboard',
      learn_more: 'Learn More',
      register: 'Register',
      ready_start: 'Ready to start?',
      start_now: 'Start Now',
    },
    sw: {
      home: 'Nyumbani',
      regions: 'Mikoa',
      about: 'Kuhusu',
      contact: 'Mawasiliano',
      faq: 'Maswali',
      login: 'Ingia',
      get_started: 'Anza Sasa',
      dashboard: 'Dashibodi',
      learn_more: 'Jifunze Zaidi',
      register: 'Jisajili',
      ready_start: 'Tayari kuanza?',
      start_now: 'Anza Sasa',
    },
  };

  let lang = 'en';
  let theme = 'light';
  try { lang = localStorage.getItem('lang') || lang; } catch (e) {}
  try { theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'); } catch (e) {}

  const applyTheme = () => document.documentElement.classList.toggle('dark', theme === 'dark');
  applyTheme();

  return {
    get theme() { return theme; },
    get lang() { return lang; },
    t(key) { return (dict[lang] && dict[lang][key]) || key; },
    toggleTheme() {
      theme = theme === 'dark' ? 'light' : 'dark';
      try { localStorage.setItem('theme', theme); } catch (e) {}
      applyTheme();
    },
    setLang(newLang) {
      lang = newLang;
      try { localStorage.setItem('lang', lang); } catch (e) {}
    },
  };
})());

Alpine.start();
