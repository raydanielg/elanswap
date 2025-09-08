"use client";

import { useEffect, useState } from "react";

type Category = { id: number; name: string; slug: string; count: number };

export default function CategoriesPage() {
  const [items, setItems] = useState<Category[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let mounted = true;
    fetch("/api/categories")
      .then((r) => r.json())
      .then((data) => {
        if (!mounted) return;
        setItems((data?.data as Category[]) ?? []);
      })
      .catch(() => setItems([]))
      .finally(() => mounted && setLoading(false));
    return () => {
      mounted = false;
    };
  }, []);

  return (
    <section className="relative overflow-hidden py-16 lg:py-24 page-dark-bg min-h-[60vh]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-end justify-between gap-4">
          <div>
            <h1 className="text-3xl sm:text-4xl font-extrabold tracking-tight text-white drop-shadow">Jamii Zote</h1>
            <p className="mt-2 text-white/85">Vinjari jamii zote na idadi ya matangazo yake.</p>
          </div>
        </div>

        {loading ? (
          <div className="mt-10 grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {Array.from({ length: 8 }).map((_, i) => (
              <div key={i} className="rounded-2xl border border-white/15 bg-white/5 backdrop-blur p-6 animate-pulse">
                <div className="h-10 w-10 rounded bg-white/20 mb-4" />
                <div className="h-4 w-2/3 bg-white/20 rounded" />
                <div className="h-3 w-1/3 bg-white/10 rounded mt-2" />
              </div>
            ))}
          </div>
        ) : (
          <div className="mt-10 grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {items.map((c) => (
              <a
                key={c.id}
                id={c.slug}
                href={`#${c.slug}`}
                aria-label={`Vinjari jamii ${c.name}`}
                className="group rounded-2xl border border-white/15 bg-white/5 hover:bg-white/10 backdrop-blur p-6 shadow transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--color-secondary)]/50"
              >
                <div className="flex items-center gap-3">
                  <span className="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[var(--color-primary)]/25 to-[var(--color-secondary)]/25 text-white shadow">
                    <svg viewBox="0 0 24 24" className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="4" width="18" height="14" rx="2"/></svg>
                  </span>
                  <div>
                    <div className="font-semibold text-white">{c.name}</div>
                    <span className="mt-1 inline-flex items-center gap-1 rounded-full border border-white/15 bg-white/10 px-2.5 py-1 text-xs font-medium text-white/85">
                      <svg viewBox="0 0 24 24" className="h-3.5 w-3.5" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M3 17h18M3 12h18M3 7h18"/></svg>
                      {c.count} matangazo
                    </span>
                  </div>
                </div>
              </a>
            ))}
          </div>
        )}
      </div>
    </section>
  );
}

