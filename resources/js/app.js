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
      cta_desc: 'Register today and start using ElanSwap.',
      // Homepage content
      hero_title: 'Transform staff transfers — fast, secure and transparent.',
      hero_para: 'ElanSwap connects you to matching stations with intelligent automation, simplifying decisions and saving admin time.',
      bullet_match: 'Match positions using your criteria (region, profession, duration).',
      bullet_transparency: 'Process transparency with real-time updates.',
      bullet_security: 'Data security with step tracking.',
      regions_title: 'Regions',
      regions_desc: 'The list of regions and stations will appear here (we will link to region.json).',
      about_title: 'About ElanSwap',
      about_desc: 'ElanSwap brings simplicity to staff transfers using safe, easy technology.',
      contact_title: 'Contact',
      contact_desc: 'Reach us via email: support@elanswap.com',
      faq_title: 'Frequently Asked Questions (FAQ)',
      faq_q1: 'How do I get started?',
      faq_a1: 'Click Get Started and register.',
      faq_q2: 'What phone number format should I use?',
      faq_a2: 'Use the format 2557XXXXXXXX.',
      features_title: 'Key Features',
      feature1_title: 'Easy Transfers',
      feature1_desc: 'Manage the transfer process in a few steps.',
      feature2_title: 'Data Protection',
      feature2_desc: 'High-level security to protect your information.',
      feature3_title: 'Quick Reports',
      feature3_desc: 'Get statistics and reports on time.',
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
      cta_desc: 'Jisajili leo na uanze kutumia ElanSwap.',
      // Homepage content
      hero_title: 'Badili namna ya uhamisho wa watumishi — haraka, salama na wazi.',
      hero_para: 'ElanSwap inakuunganisha na vituo vinavyolingana kwa akili ya kiotomatiki, ikirahisisha maamuzi na kuokoa muda wa usimamizi.',
      bullet_match: 'Ulinganifu wa nafasi kwa kutumia vigezo vyako (mkoa, taaluma, muda).',
      bullet_transparency: 'Uwazi wa mchakato na taarifa za wakati halisi.',
      bullet_security: 'Usalama wa taarifa na ufuatiliaji wa hatua.',
      regions_title: 'Mikoa',
      regions_desc: 'Orodha ya mikoa na vituo vitapatikana hapa (tutaunganisha na region.json).',
      about_title: 'Kuhusu ElanSwap',
      about_desc: 'ElanSwap inaleta urahisi katika uhamisho wa watumishi kwa kutumia teknolojia rahisi na salama.',
      contact_title: 'Mawasiliano',
      contact_desc: 'Wasiliana nasi kupitia barua pepe: support@elanswap.com',
      faq_title: 'Maswali ya Mara kwa Mara (FAQ)',
      faq_q1: 'Je, nawezaje kuanza?',
      faq_a1: 'Bofya Anza Sasa kisha jisajili.',
      faq_q2: 'Je, namba ya simu inatakiwa kwenye muundo gani?',
      faq_a2: 'Tumia muundo 2557XXXXXXXX.',
      features_title: 'Vipengele Muhimu',
      feature1_title: 'Uhamisho Rahisi',
      feature1_desc: 'Dhibiti mchakato wa uhamisho kwa hatua chache.',
      feature2_title: 'Ulinzi wa Taarifa',
      feature2_desc: 'Usalama wa kiwango cha juu kulinda taarifa zako.',
      feature3_title: 'Ripoti za Haraka',
      feature3_desc: 'Pata takwimu na ripoti kwa wakati.',
    },
  };

  // Default language: Swahili unless user picked one before
  let lang = 'sw';
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
