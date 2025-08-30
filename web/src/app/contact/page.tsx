import Contact from "@/components/Contact";

export const dynamic = "force-static";

export default function ContactPage() {
  return (
    <section className="page-dark-bg">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        {/* Hero */}
        <div className="grid lg:grid-cols-2 gap-8 items-center mb-12">
          <div>
            <h1 className="text-3xl font-extrabold text-white">Wasiliana Nasi</h1>
            <p className="mt-3 text-white/80">
              Tupo hapa kukusaidia. Tuma ujumbe, piga simu, au tutumie barua pepe. Timu yetu itakujibu haraka iwezekanavyo.
            </p>
            <div className="mt-4 grid sm:grid-cols-3 gap-3">
              <div className="rounded-xl border border-white/15 bg-white/5 p-4">
                <div className="flex items-center gap-2 text-white font-medium"><svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 16.92V21a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2 4.18 2 2 0 0 1 4 2h4.09a2 2 0 0 1 2 1.72c.12.9.3 1.77.54 2.6a2 2 0 0 1-.45 2L9 9a16 16 0 0 0 6 6l.66-1.18a2 2 0 0 1 2-1c.83.24 1.7.42 2.6.54A2 2 0 0 1 22 16.92z"/></svg> Simu</div>
                <div className="text-sm text-white/80">+255 712 345 678</div>
              </div>
              <div className="rounded-xl border border-white/15 bg-white/5 p-4">
                <div className="flex items-center gap-2 text-white font-medium"><svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M4 4h16v16H4z"/><path d="M22 6 12 13 2 6"/></svg> Barua pepe</div>
                <div className="text-sm text-white/80">support@elanswap.tz</div>
              </div>
              <div className="rounded-xl border border-white/15 bg-white/5 p-4">
                <div className="flex items-center gap-2 text-white font-medium"><svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 1 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg> Ofisi</div>
                <div className="text-sm text-white/80">Dar es Salaam, Tanzania</div>
              </div>
            </div>
          </div>
          <figure className="rounded-2xl overflow-hidden border border-white/15 bg-white/5">
            <img src="/black-female-teacher-stands-front-explains-lesson-by-reading-from-book-from-desk_404612-572.jpg" alt="Wasiliana nasi" className="w-full h-64 object-cover" />
          </figure>
        </div>

        {/* Contact component (form + details + newsletter) */}
        <div className="rounded-2xl border border-white/10 bg-white/5 p-6">
          <Contact />
        </div>

        {/* Map (live Google Maps) & hours */}
        <div className="grid lg:grid-cols-2 gap-6 mt-10">
          <div className="rounded-2xl overflow-hidden border border-white/15 bg-white/5">
            <iframe
              title="Eneo la Elan Swap - Dar es Salaam"
              src="https://www.google.com/maps?q=-2.4987598269060056,32.922086337442195&output=embed"
              className="w-full h-64"
              loading="lazy"
              referrerPolicy="no-referrer-when-downgrade"
              allowFullScreen
            />
            <div className="p-3 text-white/70 text-xs">
              Ramani hai ya Google (eneo halisi la ofisi).
            </div>
          </div>
          <div className="rounded-2xl border border-white/15 bg-white/5 p-6">
            <h2 className="text-white font-semibold text-lg">Saa za Kazi</h2>
            <ul className="mt-3 space-y-2 text-sm text-white/80">
              <li className="flex items-center gap-2"><svg viewBox="0 0 24 24" className="h-4 w-4" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg> Jumatatu – Ijumaa: 09:00 – 17:00</li>
              <li className="flex items-center gap-2"><svg viewBox="0 0 24 24" className="h-4 w-4" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg> Jumamosi: 10:00 – 14:00</li>
              <li className="flex items-center gap-2"><svg viewBox="0 0 24 24" className="h-4 w-4" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg> Jumapili na Sikukuu: Haifanyi Kazi</li>
            </ul>
          </div>
        </div>

        {/* Disclaimer */}
        <div className="mt-10 rounded-xl border border-yellow-400/40 bg-yellow-500/10 p-4 text-yellow-200 text-sm">
          <strong className="block text-yellow-300 mb-1">TAHADHARI</strong>
          Tafadhali kumbuka: ELAN SWAP si taasisi ya serikali, wala haihusiki na maamuzi ya kiutawala. Ni jukwaa huru
          linalosaidia wafanyakazi kuwasiliana na kupanga mabadilishano kwa hiari, kwa kuzingatia taratibu za waajiri wao.
        </div>
      </div>
    </section>
  );
}
