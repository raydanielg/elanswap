"use client";

import Waves from "./Waves";

export default function Hero() {

  return (
    <section className="relative overflow-hidden border-b border-black/10">
      {/* Background */}
      <div className="absolute inset-0">
        {/* Solid dark base */}
        <div className="absolute inset-0 bg-neutral-900" />
        {/* Subtle gradients on top of dark */}
        <div className="absolute inset-0 bg-gradient-to-b from-white/5 via-transparent to-black/20" />
        <div className="absolute inset-0 bg-gradient-to-tr from-[var(--color-primary)]/20 via-transparent to-[var(--color-secondary)]/20 mix-blend-overlay" />
        <div className="absolute inset-0 bg-[radial-gradient(80%_60%_at_20%_20%,_white_0%,_transparent_60%)] opacity-10" />
        {/* Waves overlay */}
        <Waves
          lineColor="#fff"
          backgroundColor="rgba(255, 255, 255, 0.2)"
          waveSpeedX={0.02}
          waveSpeedY={0.01}
          waveAmpX={40}
          waveAmpY={20}
          friction={0.9}
          tension={0.01}
          maxCursorMove={120}
          xGap={12}
          yGap={36}
        />
      </div>

      {/* Foreground content */}
      <div className="relative z-10">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid lg:grid-cols-2 gap-10 items-center">
          <div className="backdrop-blur-[1px]">
            <h1 className="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white drop-shadow">
              Uhamisho wa Wafanyakazi Usio na Usumbufu
            </h1>
            <p className="mt-4 text-white/90 max-w-prose">
              Jukwaa la kidijitali linalorahisisha mchakato wa kubadilishana vituo kwa wafanyakazi sekta mbalimbali. Unda akaunti, taja mahitaji yako, na pata mechi zinazofaa kulingana na vigezo vyako.
            </p>
            <div className="mt-8 flex flex-wrap gap-3">
              <a
                href="https://swap.elanbrands.net/register"
                className="inline-flex items-center rounded-lg px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] shadow-lg hover:shadow-xl hover:brightness-105 active:brightness-95 transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[var(--color-primary)]"
              >
                Jisajili
              </a>
              <a
                href="https://swap.elanbrands.net/login"
                className="inline-flex items-center rounded-lg px-5 py-2.5 text-sm font-medium text-white/90 border border-white/40 bg-white/10 hover:bg-white/20 hover:text-white shadow-md transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[var(--color-secondary)]"
              >
                Ingia
              </a>
              <a
                href="#contact"
                className="inline-flex items-center rounded-lg px-5 py-2.5 text-sm font-medium text-white/90 border border-white/40 bg-white/10 hover:bg-white/20 hover:text-white shadow-md transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[var(--color-secondary)]"
              >
                Mawasiliano
              </a>
            </div>
          </div>
          {/* Tracking card */}
          <div className="hidden lg:flex items-center justify-center">
            <form
              onSubmit={(e) => {
                e.preventDefault();
                const form = e.currentTarget as HTMLFormElement;
                const data = new FormData(form);
                const id = String(data.get("trackingId") || "").trim();
                if (!id) return;
                // TODO: replace with actual route e.g., router.push(`/track/${id}`)
                window.location.href = `#track-${encodeURIComponent(id)}`;
              }}
              className="w-full max-w-xl"
            >
              <div className="bg-white/90 backdrop-blur rounded-2xl shadow-xl ring-1 ring-black/5 p-3">
                <div className="flex items-center gap-2">
                  <div className="flex-1 relative">
                    <span className="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                      {/* icon */}
                      <svg className="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </span>
                    <input
                      name="trackingId"
                      type="text"
                      placeholder="Weka nambari ya ufuatiliaji"
                      className="w-full rounded-xl border border-gray-200 bg-white/90 pl-10 pr-4 py-3 text-gray-800 placeholder-gray-400 shadow-inner focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]"
                    />
                  </div>
                  <button
                    type="submit"
                    className="shrink-0 inline-flex items-center rounded-xl px-4 py-3 text-sm font-semibold text-white bg-[var(--color-secondary)] hover:brightness-110 active:brightness-95 shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[var(--color-secondary)]"
                  >
                    Fuatilia
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  );
}
