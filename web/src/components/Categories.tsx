"use client";

import { useEffect, useState } from "react";

type Category = { id: number; name: string; slug: string; count: number };

export default function Categories() {
  const [items, setItems] = useState<Category[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const load = () => {
    setLoading(true);
    setError(null);
    fetch("https://www.swap.elanbrands.net/api/categories")
      .then(async (r) => {
        if (!r.ok) throw new Error(`HTTP ${r.status}`);
        return r.json();
      })
      .then((data) => {
        const list: Category[] = data?.data ?? [];
        setItems(list.slice(0, 4)); // show a few on home
      })
      .catch((e: any) => {
        setItems([]);
        setError(e?.message || "Failed to load categories");
      })
      .finally(() => setLoading(false));
  };

  useEffect(() => {
    load();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  return (
    <section id="categories" className="py-16 lg:py-24 bg-gray-50 border-y">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-end justify-between gap-4">
          <div>
            <h2 className="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Categories</h2>
            <p className="mt-2 text-gray-600">Browse by category.</p>
          </div>
          <a href="/categories" className="text-sm text-gray-600 hover:text-[var(--color-secondary)]">View all</a>
        </div>

        {loading ? (
          <div className="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {Array.from({ length: 4 }).map((_, i) => (
              <div key={i} className="rounded-lg border bg-white p-5 animate-pulse">
                <div className="h-8 w-8 rounded bg-gray-200 mb-3" />
                <div className="h-4 w-2/3 bg-gray-200 rounded" />
                <div className="h-3 w-1/3 bg-gray-100 rounded mt-2" />
              </div>
            ))}
          </div>
        ) : error ? (
          <div className="mt-8 rounded-lg border bg-white p-5 text-sm text-red-600">
            Failed to load categories. <button onClick={load} className="underline hover:text-red-700">Retry</button>
          </div>
        ) : items.length === 0 ? (
          <div className="mt-8 rounded-lg border bg-white p-5 text-sm text-gray-600">No categories available.</div>
        ) : (
          <div className="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {items.map((c) => (
              <a
                key={c.slug}
                href={`/categories#${c.slug}`}
                aria-label={`Browse category ${c.name}`}
                className="group rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md transition shadow-gray-100 hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--color-secondary)]/50"
              >
                <span className="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-[var(--color-secondary)]/15 to-[var(--color-primary)]/15 text-[var(--color-secondary)] mb-3">
                  <svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <rect x="3" y="4" width="18" height="14" rx="2" />
                  </svg>
                </span>
                <div className="font-semibold text-gray-900 group-hover:text-gray-950">{c.name}</div>
                <span className="mt-2 inline-flex items-center gap-1 rounded-full border border-gray-200 bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-700">
                  <svg viewBox="0 0 24 24" className="h-3.5 w-3.5" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M3 17h18M3 12h18M3 7h18"/></svg>
                  {c.count} listings
                </span>
              </a>
            ))}
          </div>
        )}
      </div>
    </section>
  );
}

