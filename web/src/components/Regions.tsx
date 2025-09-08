"use client";

import { useEffect, useState } from "react";

type Region = { id: number; name: string; slug: string; districts_count: number; stations_count: number };

export default function Regions() {
  const [items, setItems] = useState<Region[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let mounted = true;
    fetch("/api/regions")
      .then((r) => r.json())
      .then((data) => {
        if (!mounted) return;
        const list: Region[] = data?.data ?? [];
        setItems(list.slice(0, 4)); // show a few on home
      })
      .catch(() => setItems([]))
      .finally(() => mounted && setLoading(false));
    return () => {
      mounted = false;
    };
  }, []);

  return (
    <section id="regions" className="py-16 lg:py-24 bg-gray-50 border-y">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-end justify-between gap-4">
          <div>
            <h2 className="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Mikoa</h2>
            <p className="mt-2 text-gray-600">Gundua fursa kwa mujibu wa mkoa.</p>
          </div>
          <a href="/out/regions" className="text-sm text-gray-600 hover:text-[var(--color-secondary)]">Tazama zote</a>
        </div>

        {loading ? (
          <div className="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {Array.from({ length: 4 }).map((_, i) => (
              <div key={i} className="rounded-lg border bg-white p-5 animate-pulse">
                <div className="h-4 w-2/3 bg-gray-200 rounded" />
                <div className="h-3 w-1/3 bg-gray-100 rounded mt-2" />
              </div>
            ))}
          </div>
        ) : (
          <div className="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {items.map((r) => (
              <a
                key={r.slug}
                href={`/login`}
                aria-label={`Vinjari mkoa ${r.name}`}
                className="group rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md transition shadow-gray-100 hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--color-secondary)]/50"
              >
                <span className="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-[var(--color-secondary)]/15 to-[var(--color-primary)]/15 text-[var(--color-secondary)] mb-3">
                  <svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M3 17h18M3 12h18M3 7h18"/></svg>
                </span>
                <div className="font-semibold text-gray-900 group-hover:text-gray-950">{r.name}</div>
                <div className="mt-2 flex flex-wrap gap-2">
                  <span className="inline-flex items-center gap-1 rounded-full border border-gray-200 bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-700">
                    <svg viewBox="0 0 24 24" className="h-3.5 w-3.5" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    {r.districts_count} wilaya
                  </span>
                </div>
              </a>
            ))}
          </div>
        )}
      </div>
    </section>
  );
}

