export const dynamic = "force-static";

export default function TermsPage() {
  return (
    <section className="page-dark-bg">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <header className="mb-8">
          <h1 className="text-3xl font-extrabold text-white">Masharti ya Huduma</h1>
          <p className="mt-2 text-white/80 max-w-2xl">Tafadhali soma masharti haya kwa uangalifu kabla ya kutumia Elan Swap.</p>
        </header>

        {/* Table of Contents */}
        <nav aria-label="Orodha ya yaliyomo" className="mb-8 rounded-xl border border-white/15 bg-white/5 p-4">
          <div className="text-white font-semibold mb-2">Yaliyomo</div>
          <ol className="list-decimal pl-5 space-y-1 text-white/85 text-sm">
            <li><a className="hover:text-white" href="#acceptance">Ukubali wa Masharti</a></li>
            <li><a className="hover:text-white" href="#use">Matumizi ya Huduma</a></li>
            <li><a className="hover:text-white" href="#accounts">Akaunti & Usalama</a></li>
            <li><a className="hover:text-white" href="#content">Maudhui</a></li>
            <li><a className="hover:text-white" href="#payments">Malipo</a></li>
            <li><a className="hover:text-white" href="#disclaimer">Tahadhari</a></li>
            <li><a className="hover:text-white" href="#liability">Kikomo cha Uwajibikaji</a></li>
            <li><a className="hover:text-white" href="#changes">Mabadiliko</a></li>
            <li><a className="hover:text-white" href="#contact">Mawasiliano</a></li>
          </ol>
        </nav>

        <div className="prose prose-invert max-w-none">
          <h2 id="acceptance">Acceptance of Terms</h2>
          <p>
            Kwa kutumia jukwaa hili, unakubali masharti haya. Usipokubali, tafadhali usitumie huduma. Tunaweza kusasisha masharti na mabadiliko yataanza kutumika mara moja baada ya kuchapishwa.
          </p>

          <figure className="my-6 rounded-xl overflow-hidden border border-white/15 bg-white/5">
            <img src="/black-female-teacher-stands-front-explains-lesson-by-reading-from-book-from-desk_404612-572.jpg" alt="Makubaliano" className="w-full h-56 object-cover" />
            <figcaption className="p-3 text-white/75 text-sm">Makubaliano yanahakikisha uwazi kati ya watumiaji na jukwaa.</figcaption>
          </figure>

          <h2 id="use">Matumizi ya Huduma</h2>
          <ul>
            <li>Usitumie huduma kwa shughuli zisizo halali au za udanganyifu.</li>
            <li>Fuata sheria, kanuni, na taratibu za mwajiri wako.</li>
            <li>Tunahifadhi haki ya kurekebisha au kusitisha huduma bila taarifa.</li>
          </ul>

          <div className="grid sm:grid-cols-3 gap-4 my-6">
            {[
              {title: 'Uadilifu', desc: 'Tumia huduma kwa nia njema na kwa heshima kwa wengine.'},
              {title: 'Uwazi', desc: 'Weka taarifa sahihi na kwa uwazi.'},
              {title: 'Usalama', desc: 'Linda akaunti yako dhidi ya matumizi mabaya.'},
            ].map((b) => (
              <div key={b.title} className="rounded-xl border border-white/15 bg-white/5 p-4">
                <div className="text-white font-medium">{b.title}</div>
                <div className="text-white/75 text-sm mt-1">{b.desc}</div>
              </div>
            ))}
          </div>

          <h2 id="accounts">Akaunti & Usalama</h2>
          <p>Unawajibika kulinda maelezo ya kuingia na shughuli zinazofanywa kupitia akaunti yako.</p>

          <h2 id="content">Maudhui</h2>
          <p>
            Unaendelea kumiliki maudhui yako. Kwa kuweka maudhui, unatupa ruhusa yasiyo ya kipekee kuyatumia kutoa huduma.
          </p>

          <h2 id="payments">Malipo</h2>
          <p>Ikiwa sehemu ya huduma inahitaji malipo, masharti ya malipo yatawasilishwa wazi kabla ya kukamilisha muamala.</p>

          <h2 id="disclaimer">Tahadhari</h2>
          <p>
            Huduma hutolewa "kama ilivyo" bila dhamana ya aina yoyote. Elan Swap si taasisi ya serikali, wala haihusiki na maamuzi ya kiutawala.
          </p>

          <h2 id="liability">Kikomo cha Uwajibikaji</h2>
          <p>
            Katika hali yoyote, hatutawajibika kwa uharibifu wa moja kwa moja au usio wa moja kwa moja unaotokana na matumizi ya huduma.
          </p>

          <h2 id="changes">Mabadiliko</h2>
          <p>Tunaweza kubadilisha masharti haya mara kwa mara. Tutatangaza mabadiliko kupitia ukurasa huu.</p>

          <h2 id="contact">Mawasiliano</h2>
          <p>Maswali kuhusu masharti? <strong>support@elanswap.tz</strong> au piga <strong>+255 712 345 678</strong>.</p>
        </div>
      </div>
    </section>
  );
}
