"use client";

import { useEffect, useState } from "react";

type Region = { id: number; name: string; slug: string; districts_count: number; stations_count: number };
type StationWithApps = { id: number; name: string; district: string; applications_count: number };
type StationApplication = { id: number; code: string; status: string; submitted_at: string | null };

export default function RegionsPage() {
  const [items, setItems] = useState<Region[]>([]);
  const [loading, setLoading] = useState(true);
  const [modalOpen, setModalOpen] = useState(false);
  const [selected, setSelected] = useState<Region | null>(null);
  const [stations, setStations] = useState<StationWithApps[]>([]);
  const [sLoading, setSLoading] = useState(false);
  const [sError, setSError] = useState<string | null>(null);
  const [expandedStationId, setExpandedStationId] = useState<number | null>(null);
  const [appsByStation, setAppsByStation] = useState<Record<number, { loading: boolean; error: string | null; data: StationApplication[] }>>({});

  useEffect(() => {
    let mounted = true;
    fetch("https://www.swap.elanbrands.net/api/regions")
      .then((r) => r.json())
      .then((data) => {
        if (!mounted) return;
        setItems((data?.data as Region[]) ?? []);
      })
      .catch(() => setItems([]))
      .finally(() => mounted && setLoading(false));
    return () => {
      mounted = false;
    };
  }, []);

  const openModal = async (region: Region) => {
    setSelected(region);
    setModalOpen(true);
    setSLoading(true);
    setSError(null);
    setStations([]);
    try {
      const res = await fetch(`https://www.swap.elanbrands.net/api/regions/${region.id}/stations-with-apps`);
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const json = await res.json();
      setStations((json?.data as StationWithApps[]) ?? []);
    } catch (e: any) {
      setSError(e?.message || "Failed to load stations");
    } finally {
      setSLoading(false);
    }
  };

  useEffect(() => {
    const onEsc = (e: KeyboardEvent) => {
      if (e.key === "Escape") setModalOpen(false);
    };
    if (modalOpen) document.addEventListener("keydown", onEsc);
    return () => document.removeEventListener("keydown", onEsc);
  }, [modalOpen]);

  return (
    <section className="relative overflow-hidden py-16 lg:py-24 page-dark-bg min-h-[60vh]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-end justify-between gap-4">
          <div>
            <h1 className="text-3xl sm:text-4xl font-extrabold tracking-tight text-white drop-shadow">All Regions</h1>
            <p className="mt-2 text-white/90">Browse all regions with stations and district counts.</p>
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
            {items.map((r) => (
              <button
                key={r.id}
                id={r.slug}
                type="button"
                onClick={() => openModal(r)}
                aria-label={`Open ${r.name} districts`}
                className="group rounded-2xl border border-white/15 bg-white/5 hover:bg-white/10 backdrop-blur p-6 shadow transition text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--color-secondary)]/50"
              >
                <div className="flex items-center gap-3">
                  <span className="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[var(--color-primary)]/25 to-[var(--color-secondary)]/25 text-white shadow">
                    <svg viewBox="0 0 24 24" className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M3 11h18M3 7h18M3 15h18"/></svg>
                  </span>
                  <div>
                    <div className="font-semibold text-white">{r.name}</div>
                    <div className="text-sm text-white/75">{r.districts_count} districts</div>
                  </div>
                </div>
              </button>
            ))}
          </div>
        )}
      </div>

      {/* Modal */}
      {modalOpen && selected && (
        <div className="fixed inset-0 z-50">
          <div className="absolute inset-0 bg-black/60" onClick={() => setModalOpen(false)} />
          <div className="absolute inset-0 flex items-center justify-center p-4">
            <div className="w-full max-w-3xl rounded-2xl border border-white/15 bg-white/10 backdrop-blur shadow-xl">
              <div className="flex items-center justify-between p-4 sm:p-5 border-b border-white/10">
                <div>
                  <h2 className="text-xl font-semibold text-white">{selected.name}</h2>
                  <p className="text-sm text-white/80">Stations with applications</p>
                </div>
                <button onClick={() => setModalOpen(false)} aria-label="Close" className="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/15 bg-white/10 text-white hover:bg-white/20">
                  <svg viewBox="0 0 24 24" className="h-4 w-4" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
              </div>
              <div className="p-4 sm:p-5">
                {sLoading ? (
                  <div className="rounded-lg border border-white/10 bg-white/5 p-4 text-white/85">Loading stations...</div>
                ) : sError ? (
                  <div className="rounded-lg border border-red-400/30 bg-red-500/10 p-4 text-red-200 text-sm">
                    Failed to load stations. <button onClick={() => selected && openModal(selected)} className="underline">Retry</button>
                  </div>
                ) : stations.length === 0 ? (
                  <div className="rounded-lg border border-white/10 bg-white/5 p-4 text-white/75">No stations with applications.</div>
                ) : (
                  <ul className="grid gap-3">
                    {stations.map((s) => {
                      const st = appsByStation[s.id] || { loading: false, error: null, data: [] };
                      const open = expandedStationId === s.id;
                      const toggle = async () => {
                        const next = open ? null : s.id;
                        setExpandedStationId(next);
                        if (next && !st.data.length && !st.loading) {
                          setAppsByStation((prev) => ({ ...prev, [s.id]: { loading: true, error: null, data: [] } }));
                          try {
                            const r = await fetch(`https://www.swap.elanbrands.net/api/stations/${s.id}/applications`);
                            if (!r.ok) throw new Error(`HTTP ${r.status}`);
                            const j = await r.json();
                            setAppsByStation((prev) => ({ ...prev, [s.id]: { loading: false, error: null, data: (j?.data as StationApplication[]) ?? [] } }));
                          } catch (e: any) {
                            setAppsByStation((prev) => ({ ...prev, [s.id]: { loading: false, error: e?.message || "Failed to load applications", data: [] } }));
                          }
                        }
                      };
                      return (
                        <li key={s.id} className="rounded-lg border border-white/10 bg-white/5">
                          <button type="button" onClick={toggle} className="w-full p-3 text-left flex items-center justify-between gap-3 focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--color-secondary)]/50">
                            <div>
                              <div className="text-white font-medium">{s.name}</div>
                              <div className="text-xs text-white/75">{s.district}</div>
                            </div>
                            <span className="inline-flex items-center gap-1 rounded-full border border-white/15 bg-white/10 px-2 py-0.5 text-xs font-medium text-white/85">
                              {s.applications_count} apps
                            </span>
                          </button>
                          <div className={`${open ? "block" : "hidden"} border-t border-white/10 p-3 pt-2`}> 
                            {st.loading ? (
                              <div className="text-white/85 text-sm">Loading applications...</div>
                            ) : st.error ? (
                              <div className="text-red-200 text-sm">Failed to load. <button onClick={toggle} className="underline">Retry</button></div>
                            ) : st.data.length === 0 ? (
                              <div className="text-white/75 text-sm">No applications.</div>
                            ) : (
                              <ul className="space-y-2">
                                {st.data.map((a) => (
                                  <li key={a.id} className="rounded-md border border-white/10 bg-white/5 px-3 py-2 flex items-center justify-between">
                                    <div>
                                      <div className="text-white text-sm font-medium">{a.code}</div>
                                      <div className="text-white/70 text-xs">{a.submitted_at ? new Date(a.submitted_at).toLocaleString() : "—"}</div>
                                    </div>
                                    <span className="ml-3 inline-flex items-center gap-1 rounded-full border border-white/15 bg-white/10 px-2 py-0.5 text-xs font-medium text-white/85">
                                      {a.status}
                                    </span>
                                  </li>
                                ))}
                              </ul>
                            )}
                          </div>
                        </li>
                      );
                    })}
                  </ul>
                )}
              </div>
            </div>
          </div>
        </div>
      )}
    </section>
  );
}
