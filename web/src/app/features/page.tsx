"use client";

const features = [
  {
    title: "Fast Matching",
    desc: "Quickly find compatible transfer matches based on role, region, and preferences.",
    icon: (
      <svg viewBox="0 0 24 24" className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <path d="M13 17h8"/><path d="M13 13h6"/><path d="M13 9h4"/>
        <path d="M3 7l4 4-4 4"/>
      </svg>
    )
  },
  {
    title: "Secure Profiles",
    desc: "Your data stays safe with access controls and privacy-first design.",
    icon: (
      <svg viewBox="0 0 24 24" className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <rect x="3" y="11" width="18" height="10" rx="2"/>
        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
      </svg>
    )
  },
  {
    title: "Guided Process",
    desc: "Step-by-step flow to submit, track, and complete transfer requests.",
    icon: (
      <svg viewBox="0 0 24 24" className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <circle cx="12" cy="12" r="10"/>
        <path d="M10 8h4l-1 8h-2l-1-8z"/>
      </svg>
    )
  },
  {
    title: "Real-time Tracking",
    desc: "Enter a tracking ID to view the current status and history.",
    icon: (
      <svg viewBox="0 0 24 24" className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <path d="M21 21l-4.35-4.35"/>
        <circle cx="10" cy="10" r="7"/>
      </svg>
    )
  },
  {
    title: "Notifications",
    desc: "Get timely updates on approvals, matches, and messages.",
    icon: (
      <svg viewBox="0 0 24 24" className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
      </svg>
    )
  },
  {
    title: "Support",
    desc: "Reach our team for help, guidance, and feedback.",
    icon: (
      <svg viewBox="0 0 24 24" className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <path d="M9 18v-1a3 3 0 0 1 3-3h2"/>
        <path d="M9 10a3 3 0 1 1 3 3"/>
        <circle cx="12" cy="12" r="10"/>
      </svg>
    )
  }
];

export default function FeaturesPage() {
  return (
    <section className="relative overflow-hidden min-h-[60vh] border-b border-black/10 page-dark-bg">

      {/* Content */}
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div className="max-w-3xl">
          <h1 className="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white drop-shadow">
            Platform Features
          </h1>
          <p className="mt-4 text-white/90 max-w-prose">
            Explore the tools that make Elan Swap seamless, transparent and efficient.
          </p>
        </div>

        <div className="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {features.map((f) => (
            <div key={f.title} className="group rounded-2xl border border-white/15 bg-white/5 backdrop-blur shadow-xl p-6 hover:bg-white/10 transition">
              <div className="flex items-center gap-3">
                <span className="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[var(--color-primary)]/25 to-[var(--color-secondary)]/25 text-white shadow">
                  {f.icon}
                </span>
                <h3 className="text-white font-semibold text-lg">{f.title}</h3>
              </div>
              <p className="mt-3 text-white/80 leading-relaxed">{f.desc}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
