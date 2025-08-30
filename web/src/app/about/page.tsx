export const dynamic = "force-static";

export default function AboutPage() {
  return (
    <section className="page-dark-bg">
      {/* Hero */}
      <div className="relative overflow-hidden border-b border-white/10">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
          <div className="grid lg:grid-cols-2 gap-8 items-center">
            <div>
              <h1 className="text-3xl sm:text-4xl font-extrabold text-white">Kuhusu Elan Swap</h1>
              <p className="mt-4 text-white/80 leading-relaxed">
                Elan Swap ni jukwaa huru linalowaunganisha wafanyakazi wanaopanga mabadilishano ya vituo kwa hiari.
                Tunarahisisha mawasiliano na upangaji, kwa kuzingatia taratibu za waajiri wao.
              </p>
              <div className="mt-6 inline-flex items-center gap-3">
                <a href="/regions" className="inline-flex items-center rounded-full px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] shadow hover:brightness-105">
                  Gundua Mikoa
                </a>
                <a href="#mission" className="inline-flex items-center rounded-full px-5 py-2.5 text-sm font-semibold text-white bg-white/10 border border-white/15 hover:bg-white/20">
                  Dhamira Yetu
                </a>
              </div>
            </div>
            <div className="relative">
              <div className="aspect-[16/10] rounded-2xl overflow-hidden border border-white/15 bg-white/5">
                <img
                  src="/black-female-teacher-stands-front-explains-lesson-by-reading-from-book-from-desk_404612-572.jpg"
                  alt="Watu wanaoshirikiana"
                  className="w-full h-full object-cover"
                />
              </div>
            </div>
          </div>

          {/* Disclaimer banner */}
          <div className="mt-8 rounded-xl border border-yellow-400/40 bg-yellow-500/10 p-4 text-yellow-200 text-sm">
            <strong className="block text-yellow-300 mb-1">TAHADHARI</strong>
            <p>
              Tafadhali kumbuka: ELAN SWAP si taasisi ya serikali, wala haihusiki na maamuzi ya kiutawala. Ni jukwaa huru
              linalosaidia wafanyakazi kuwasiliana na kupanga mabadilishano kwa hiari, kwa kuzingatia taratibu za waajiri wao.
            </p>
          </div>
        </div>
      </div>

      {/* Mission, Vision, Goals */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14" id="mission">
        <div className="grid lg:grid-cols-3 gap-6">
          <div className="rounded-2xl border border-white/15 bg-white/5 p-6">
            <h3 className="text-white font-semibold text-lg">Dhamira</h3>
            <p className="mt-2 text-white/80 text-sm leading-relaxed">
              Kuwezesha mabadilishano ya haki na rahisi kwa wafanyakazi kwa kutumia teknolojia rahisi na ya kuaminika.
            </p>
          </div>
          <div className="rounded-2xl border border-white/15 bg-white/5 p-6">
            <h3 className="text-white font-semibold text-lg">Maono</h3>
            <p className="mt-2 text-white/80 text-sm leading-relaxed">
              Kuwa jukwaa namba moja Afrika Mashariki la mabadilishano ya vituo, likiunganisha watu kwa uwazi na ufanisi.
            </p>
          </div>
          <div className="rounded-2xl border border-white/15 bg-white/5 p-6">
            <h3 className="text-white font-semibold text-lg">Malengo</h3>
            <ul className="mt-2 text-white/80 text-sm leading-relaxed list-disc pl-5 space-y-1">
              <li>Kupunguza muda na gharama za kupanga mabadilishano.</li>
              <li>Kukuza uwazi, usalama, na mawasiliano ya moja kwa moja.</li>
              <li>Kupanua huduma kwa mikoa yote na sekta nyingi.</li>
            </ul>
          </div>
        </div>
      </div>

      {/* Image gallery with captions */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {[ 
            {
              src: "/black-cheerful-woman-smiling-sitting-table-stock-photo_195114-64871.jpg",
              alt: "Mtaalamu mwenye furaha",
              caption: "Urahisi wa kupanga mabadilishano",
            },
            {
              src: "/african-woman-teaching-children-class_23-2148892563 (1).jpg",
              alt: "Kufundisha darasani",
              caption: "Kuhusu jamii na maendeleo",
            },
            {
              src: "/black-female-teacher-stands-front-explains-lesson-by-reading-from-book-from-desk_404612-572.jpg",
              alt: "Timu ikielezea",
              caption: "Uwazi na uaminifu",
            },
          ].map((img, i) => (
            <figure key={i} className="rounded-xl overflow-hidden border border-white/15 bg-white/5">
              <img src={img.src} alt={img.alt} className="w-full h-52 object-cover" />
              <figcaption className="p-3 text-white/80 text-sm">{img.caption}</figcaption>
            </figure>
          ))}
        </div>
      </div>

      {/* Values */}
      <div className="border-t border-white/10">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
          <h2 className="text-white font-semibold text-xl">Misingi Yetu</h2>
          <div className="mt-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {[
              { title: "Uwazi", desc: "Tunaweka taarifa wazi ili kuwezesha maamuzi sahihi." },
              { title: "Usalama", desc: "Faragha na usalama wa data ni kipaumbele chetu." },
              { title: "Ushirikiano", desc: "Tunawaunganisha watu kwa ufanisi na heshima." },
              { title: "Ubunifu", desc: "Tunaboresha kila mara kwa kutatua changamoto halisi." },
            ].map((v) => (
              <div key={v.title} className="rounded-xl border border-white/15 bg-white/5 p-4">
                <div className="text-white font-medium">{v.title}</div>
                <div className="text-white/75 text-sm mt-1">{v.desc}</div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Final disclaimer repeat (compact) */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div className="mt-8 rounded-lg border border-yellow-400/30 bg-yellow-500/10 p-3 text-yellow-200 text-xs">
          <strong className="mr-2 text-yellow-300">TAHADHARI:</strong>
          Tafadhali kumbuka: ELAN SWAP si taasisi ya serikali, wala haihusiki na maamuzi ya kiutawala. Ni jukwaa huru linalosaidia
          wafanyakazi kuwasiliana na kupanga mabadilishano kwa hiari, kwa kuzingatia taratibu za waajiri wao.
        </div>
      </div>
    </section>
  );
}
