export const dynamic = "force-static";

export default function HelpPage() {
  const categories = [
    { title: 'Kuanza Haraka', icon: (
      <svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M12 2v20"/><path d="M5 12h14"/></svg>
    ), items: ['Unda akaunti', 'Thibitisha maelezo', 'Pitia vipengele vikuu'] },
    { title: 'Akaunti & Usalama', icon: (
      <svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>
    ), items: ['Badilisha nenosiri', 'Uthibitishaji wa hatua mbili', 'Kulinda taarifa binafsi'] },
    { title: 'Maombi & Mabadilishano', icon: (
      <svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 15V6"/><path d="m8 6 2-2 2 2 2-2 2 2"/><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
    ), items: ['Wasilisha ombi', 'Fuata hatua za uthibitisho', 'Tuma ujumbe kwa mwenzako'] },
  ];

  const faqs = [
    { q: 'Elan Swap inafanya nini?', a: 'Ni jukwaa huru linalosaidia wafanyakazi kuwasiliana na kupanga mabadilishano ya vituo kwa hiari, kwa kuzingatia taratibu za waajiri wao.' },
    { q: 'Je, ni taasisi ya serikali?', a: 'Hapana. Elan Swap si taasisi ya serikali, wala haihusiki na maamuzi ya kiutawala.' },
    { q: 'Ninawezaje kuwasilisha ombi?', a: 'Fungua akaunti yako, nenda kwenye sehemu ya Maombi, na fuata hatua zilizoelekezwa.' },
    { q: 'Nikisahau nenosiri?', a: 'Tumia kipengele cha “Forgot password?” kurekebisha kupitia barua pepe yako.' },
    { q: 'Je, taarifa zangu ziko salama?', a: 'Ndiyo, tunatumia mbinu za kiusalama na hatuuzwi kwa matangazo ya kibiashara.' },
    { q: 'Naweza kuwasiliana vipi na msaada?', a: 'Tupigie +255 712 345 678 au barua pepe support@elanswap.tz, pia tumia fomu ya mawasiliano.' },
  ];

  return (
    <section className="page-dark-bg">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        {/* Banner image */}
        <figure className="mb-8 rounded-2xl overflow-hidden border border-white/15 bg-white/5">
          <img src="/african-woman-teaching-children-class_23-2148892563 (1).jpg" alt="Msaada wa jamii" className="w-full h-52 object-cover" />
        </figure>

        <header className="mb-6">
          <h1 className="text-3xl font-extrabold text-white">Kituo cha Msaada</h1>
          <p className="mt-2 text-white/80 max-w-2xl">Maswali ya mara kwa mara, mwongozo wa kuanza, na rasilimali za msaada.</p>
        </header>

        {/* Search stub */}
        <div className="mb-8 flex items-center gap-2 rounded-xl border border-white/15 bg-white/5 p-3">
          <svg viewBox="0 0 24 24" className="h-5 w-5 text-white/70" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input aria-label="Tafuta msaada" placeholder="Tafuta swali au mada..." className="bg-transparent outline-none text-sm text-white/90 w-full placeholder:text-white/50" />
          <span className="text-[10px] text-white/60 border border-white/20 rounded px-1.5 py-0.5">Inakuja hivi karibuni</span>
        </div>

        {/* Categories */}
        <div className="grid sm:grid-cols-3 gap-4 mb-10">
          {categories.map((c) => (
            <div key={c.title} className="rounded-2xl border border-white/15 bg-white/5 p-5">
              <div className="flex items-center gap-2 text-white font-semibold">
                {c.icon}
                {c.title}
              </div>
              <ul className="mt-3 space-y-1 text-sm text-white/80">
                {c.items.map((it) => (
                  <li key={it} className="flex items-center gap-2">
                    <svg viewBox="0 0 24 24" className="h-4 w-4" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="m20 6-11 11-5-5"/></svg>
                    {it}
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>

        <div className="grid lg:grid-cols-3 gap-8">
          <div className="lg:col-span-2">
            <div className="space-y-4">
              {faqs.map((f, i) => (
                <details key={i} className="rounded-xl border border-white/15 bg-white/5 p-4">
                  <summary className="cursor-pointer text-white font-medium">{f.q}</summary>
                  <p className="mt-2 text-white/80 text-sm leading-relaxed">{f.a}</p>
                </details>
              ))}
            </div>

            {/* Disclaimer */}
            <div className="mt-8 rounded-xl border border-yellow-400/40 bg-yellow-500/10 p-4 text-yellow-200 text-sm">
              <strong className="block text-yellow-300 mb-1">TAHADHARI</strong>
              Tafadhali kumbuka: ELAN SWAP si taasisi ya serikali, wala haihusiki na maamuzi ya kiutawala. Ni jukwaa huru
              linalosaidia wafanyakazi kuwasiliana na kupanga mabadilishano kwa hiari, kwa kuzingatia taratibu za waajiri wao.
            </div>
          </div>

          <aside>
            <div className="rounded-2xl border border-white/15 bg-white/5 p-6">
              <h2 className="text-white font-semibold text-lg">Bado unahitaji msaada?</h2>
              <p className="mt-2 text-white/80 text-sm">Wasiliana nasi moja kwa moja.</p>
              <div className="mt-4 space-y-2 text-sm text-white/85">
                <div className="flex items-center gap-2"><svg viewBox="0 0 24 24" className="h-4 w-4" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M4 4h16v16H4z"/><path d="m22 6-10 7L2 6"/></svg> support@elanswap.tz</div>
                <div className="flex items-center gap-2"><svg viewBox="0 0 24 24" className="h-4 w-4" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 16.92V21a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2 4.18 2 2 0 0 1 4 2h4.09a2 2 0 0 1 2 1.72c.12.9.3 1.77.54 2.6a2 2 0 0 1-.45 2L9 9a16 16 0 0 0 6 6l.66-1.18a2 2 0 0 1 2-1c.83.24 1.7.42 2.6.54A2 2 0 0 1 22 16.92z"/></svg> +255 712 345 678</div>
              </div>
              <a href="#contact" className="mt-4 inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] shadow hover:brightness-105">Fomu ya mawasiliano</a>
            </div>

            <figure className="mt-6 rounded-2xl overflow-hidden border border-white/15 bg-white/5">
              <img src="/black-cheerful-woman-smiling-sitting-table-stock-photo_195114-64871.jpg" alt="Msaada" className="w-full h-40 object-cover" />
              <figcaption className="p-3 text-white/70 text-xs">Tupo kukusaidia kila hatua.</figcaption>
            </figure>
          </aside>
        </div>
      </div>
    </section>
  );
}
