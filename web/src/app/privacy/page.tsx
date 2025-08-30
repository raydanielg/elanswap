export const dynamic = "force-static";

export default function PrivacyPage() {
  return (
    <section className="page-dark-bg">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <header className="mb-8">
          <h1 className="text-3xl font-extrabold text-white">Sera ya Faragha</h1>
          <p className="mt-2 text-white/80 max-w-2xl">
            Faragha yako ni muhimu. Ukurasa huu unaeleza tunachokusanya, jinsi tunavyotumia, na haki zako.
          </p>
        </header>

        {/* Table of Contents */}
        <nav aria-label="Orodha ya yaliyomo" className="mb-8 rounded-xl border border-white/15 bg-white/5 p-4">
          <div className="text-white font-semibold mb-2">Yaliyomo</div>
          <ol className="list-decimal pl-5 space-y-1 text-white/85 text-sm">
            <li><a className="hover:text-white" href="#info">Taarifa Tunazokusanya</a></li>
            <li><a className="hover:text-white" href="#use">Jinsi Tunavyotumia Taarifa</a></li>
            <li><a className="hover:text-white" href="#sharing">Ushirikishaji wa Data</a></li>
            <li><a className="hover:text-white" href="#retention">Uhifadhi wa Data</a></li>
            <li><a className="hover:text-white" href="#rights">Haki Zako</a></li>
            <li><a className="hover:text-white" href="#security">Usalama</a></li>
            <li><a className="hover:text-white" href="#cookies">Vidakuzi</a></li>
            <li><a className="hover:text-white" href="#contact">Mawasiliano</a></li>
          </ol>
        </nav>

        <div className="prose prose-invert max-w-none">
          <h2 id="info">Taarifa Tunazokusanya</h2>
          <p>
            Tunakusanya taarifa unapotumia huduma yetu, kama vile jina, barua pepe, na maudhui ya maombi. Pia tunaweza
            kukusanya taarifa za matumizi ya tovuti ili kuboresha huduma.
          </p>

          <figure className="my-6 rounded-xl overflow-hidden border border-white/15 bg-white/5">
            <img src="/black-cheerful-woman-smiling-sitting-table-stock-photo_195114-64871.jpg" alt="Faragha na uaminifu" className="w-full h-56 object-cover" />
            <figcaption className="p-3 text-white/75 text-sm">Faragha yako ni muhimu — tunatunza taarifa kwa uangalifu.</figcaption>
          </figure>

          <h2 id="use">Jinsi Tunavyotumia Taarifa</h2>
          <ul>
            <li>Kutoa na kuboresha huduma.</li>
            <li>Kuwasiliana nawe kuhusu masasisho au usaidizi.</li>
            <li>Kulinda usalama na uadilifu wa jukwaa.</li>
          </ul>

          <h2 id="sharing">Ushirikishaji wa Data</h2>
          <p>
            Hatuzuii faragha yako. Hatushiriki taarifa zako kwa wauzaji wa matangazo. Tunaweza kushiriki inapohitajika
            kisheria au kulinda haki zetu.
          </p>

          <h2 id="retention">Uhifadhi wa Data</h2>
          <p>Tunahifadhi taarifa kadiri inavyohitajika kwa madhumuni ya huduma, kisha tunazifuta kwa usalama.</p>

          <h2 id="rights">Haki Zako</h2>
          <ul>
            <li>Kuomba nakala ya taarifa zako.</li>
            <li>Kuomba kusahihishwa/kufutwa kwa data fulani.</li>
            <li>Kupinga uchakataji katika hali fulani.</li>
          </ul>

          {/* Security & Cookies with icons */}
          <div className="grid sm:grid-cols-2 gap-4 my-8">
            <div className="rounded-xl border border-white/15 bg-white/5 p-4">
              <div className="flex items-center gap-2 text-white font-medium"><svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>Usalama</div>
              <p className="mt-2 text-sm text-white/80">Tunatumia mbinu za kiusalama kulinda taarifa zako dhidi ya upotevu, matumizi mabaya, au ufikiaji usioidhinishwa.</p>
            </div>
            <div className="rounded-xl border border-white/15 bg-white/5 p-4">
              <div className="flex items-center gap-2 text-white font-medium"><svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M3 3h18v18H3z"/><path d="M16 8a4 4 0 1 1-8 0"/></svg>Vidakuzi</div>
              <p className="mt-2 text-sm text-white/80">Tunatumia cookies kuboresha uzoefu. Unaweza kudhibiti mipangilio ya cookies kupitia kivinjari chako.</p>
            </div>
          </div>

          <h2 id="security">Usalama</h2>
          <p>Tunapitia taratibu zetu mara kwa mara ili kuboresha ulinzi wa data na kufuata viwango husika.</p>

          <h2 id="cookies">Vidakuzi</h2>
          <p>Baadhi ya vipengele hutegemea cookies za utendaji na uchanganuzi; unaweza kuamua kuzima kupitia mipangilio ya kivinjari.</p>

          <h2 id="contact">Mawasiliano</h2>
          <p>Maswali ya faragha? Tupigie <strong>+255 712 345 678</strong> au tutumie barua pepe <strong>support@elanswap.tz</strong>.</p>
        </div>
      </div>
    </section>
  );
}
