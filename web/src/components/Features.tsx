"use client";

import { features } from "@/data/site";
import { useEffect, useRef, useState } from "react";

export default function Features() {
  // Inline icon set matching the first 6 features in data/site.ts
  const icons = [
    // Fast Search
    (
      <svg viewBox="0 0 24 24" className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden>
        <path d="M21 21l-4.35-4.35" />
        <circle cx="10" cy="10" r="7" />
      </svg>
    ),
    // Secure
    (
      <svg viewBox="0 0 24 24" className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden>
        <rect x="3" y="11" width="18" height="10" rx="2" />
        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
      </svg>
    ),
    // Responsive
    (
      <svg viewBox="0 0 24 24" className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden>
        <rect x="3" y="4" width="14" height="12" rx="2" />
        <path d="M7 20h10" />
        <rect x="5" y="6" width="10" height="8" rx="1" />
      </svg>
    ),
    // Real-time Tracking
    (
      <svg viewBox="0 0 24 24" className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden>
        <path d="M3 12h6l4 8 4-16 4 8h-6" />
      </svg>
    ),
    // Smart Matching
    (
      <svg viewBox="0 0 24 24" className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden>
        <path d="M12 2v4" />
        <path d="M12 18v4" />
        <path d="M2 12h4" />
        <path d="M18 12h4" />
        <circle cx="12" cy="12" r="3" />
      </svg>
    ),
    // Notifications
    (
      <svg viewBox="0 0 24 24" className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden>
        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9" />
        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
      </svg>
    ),
  ];
  const gridRef = useRef<HTMLDivElement | null>(null);
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    const el = gridRef.current;
    if (!el) return;
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((e) => {
          if (e.isIntersecting) setVisible(true);
        });
      },
      { threshold: 0.15 }
    );
    io.observe(el);
    return () => io.disconnect();
  }, []);

  return (
    <section id="features" className="relative overflow-hidden py-16 lg:py-24 border-t border-black/10 page-dark-bg">

      {/* Content */}
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Heading area: keep accessible label, show View all on the right */}
        <div className="flex items-center justify-between gap-4">
          <h2 className="sr-only">Features</h2>
          <a
            href="/features"
            className="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold text-white bg-white/10 hover:bg-white/15 border border-white/20 shadow-sm transition"
          >
            View all
          </a>
        </div>

        <div ref={gridRef} className="mt-6 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {features.map((f, i) => (
            <div
              key={f.title}
              className={`group rounded-2xl border border-white/15 bg-white/5 backdrop-blur shadow-xl p-6 hover:bg-white/10 transition ${
                visible ? "anim-fade-in-up" : "opacity-0 translate-y-3"
              }`}
              style={visible ? { animationDelay: `${i * 90}ms` } : undefined}
            >
              <div className="flex items-center gap-3">
                <span className="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[var(--color-primary)]/25 to-[var(--color-secondary)]/25 text-white shadow">
                  {icons[i % icons.length]}
                </span>
                <h3 className="text-white font-semibold text-lg">{f.title}</h3>
              </div>
              <p className="mt-3 text-white/80 leading-relaxed">{f.description}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
